<?php

namespace Drupal\workspaces;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\Utility\Error;
use Psr\Log\LoggerInterface;

/**
 * Default implementation of the workspace merger.
 *
 * @internal
 */
class WorkspaceMerger implements WorkspaceMergerInterface {

  /**
   * The source workspace entity.
   *
   * @var \Drupal\workspaces\WorkspaceInterface
   */
  protected $sourceWorkspace;

  /**
   * The target workspace entity.
   *
   * @var \Drupal\workspaces\WorkspaceInterface
   */
  protected $targetWorkspace;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The workspace association service.
   *
   * @var \Drupal\workspaces\WorkspaceAssociationInterface
   */
  protected $workspaceAssociation;

  /**
   * Constructs a new WorkspaceMerger.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Database\Connection $database
   *   Database connection.
   * @param \Drupal\workspaces\WorkspaceAssociationInterface $workspace_association
   *   The workspace association service.
   * @param \Drupal\workspaces\WorkspaceInterface $source
   *   The source workspace.
   * @param \Drupal\workspaces\WorkspaceInterface $target
   *   The target workspace.
   * @param \Psr\Log\LoggerInterface|null $logger
   *   The logger.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, Connection $database, WorkspaceAssociationInterface $workspace_association, WorkspaceInterface $source, WorkspaceInterface $target, protected ?LoggerInterface $logger = NULL) {
    $this->entityTypeManager = $entity_type_manager;
    $this->database = $database;
    $this->workspaceAssociation = $workspace_association;
    $this->sourceWorkspace = $source;
    $this->targetWorkspace = $target;
    if ($this->logger === NULL) {
      @trigger_error('Calling ' . __METHOD__ . '() without the $logger argument is deprecated in drupal:10.1.0 and it will be required in drupal:11.0.0. See https://www.drupal.org/node/2932520', E_USER_DEPRECATED);
      $this->logger = \Drupal::service('logger.channel.workspaces');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function merge() {
    if (!$this->sourceWorkspace->hasParent() || $this->sourceWorkspace->parent->target_id != $this->targetWorkspace->id()) {
      throw new \InvalidArgumentException('The contents of a workspace can only be merged into its parent workspace.');
    }

    if ($this->checkConflictsOnTarget()) {
      throw new WorkspaceConflictException();
    }

    try {
      $transaction = $this->database->startTransaction();
      $max_execution_time = ini_get('max_execution_time');
      $step_size = Settings::get('entity_update_batch_size', 50);
      $counter = 0;

      foreach ($this->getDifferringRevisionIdsOnSource() as $entity_type_id => $revision_difference) {
        $entity_type = $this->entityTypeManager->getDefinition($entity_type_id);
        $revisions_on_source = $this->entityTypeManager->getStorage($entity_type_id)
          ->loadMultipleRevisions(array_keys($revision_difference));

        /** @var \Drupal\Core\Entity\ContentEntityInterface $revision */
        foreach ($revisions_on_source as $revision) {
          // Track all the differing revisions from the source workspace in
          // the context of the target workspace. This will automatically
          // update all the descendants of the target workspace as well.
          $this->workspaceAssociation->trackEntity($revision, $this->targetWorkspace);

          // Set the workspace in which the revision was merged.
          $field_name = $entity_type->getRevisionMetadataKey('workspace');
          $revision->{$field_name}->target_id = $this->targetWorkspace->id();
          $revision->setSyncing(TRUE);
          $revision->save();
          $counter++;

          // Extend the execution time in order to allow processing workspaces
          // that contain a large number of items.
          if ((int) ($counter / $step_size) >= 1) {
            set_time_limit($max_execution_time);
            $counter = 0;
          }
        }
      }
    }
    catch (\Exception $e) {
      if (isset($transaction)) {
        $transaction->rollBack();
      }
      Error::logException($this->logger, $e);
      throw $e;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceLabel() {
    return $this->sourceWorkspace->label();
  }

  /**
   * {@inheritdoc}
   */
  public function getTargetLabel() {
    return $this->targetWorkspace->label();
  }

  /**
   * {@inheritdoc}
   */
  public function checkConflictsOnTarget() {
    // Nothing to do for now, we can not get to a conflicting state because an
    // entity which is being edited in a workspace can not be edited in any
    // other workspace.
  }

  /**
   * {@inheritdoc}
   */
  public function getDifferringRevisionIdsOnTarget() {
    $target_revision_difference = [];

    $tracked_entities_on_source = $this->workspaceAssociation->getTrackedEntities($this->sourceWorkspace->id());
    $tracked_entities_on_target = $this->workspaceAssociation->getTrackedEntities($this->targetWorkspace->id());
    foreach ($tracked_entities_on_target as $entity_type_id => $tracked_revisions) {
      // Now we compare the revision IDs which are tracked by the target
      // workspace to those that are tracked by the source workspace, and the
      // difference between these two arrays gives us all the entities which
      // have a different revision ID on the target.
      if (!isset($tracked_entities_on_source[$entity_type_id])) {
        $target_revision_difference[$entity_type_id] = $tracked_revisions;
      }
      elseif ($revision_difference = array_diff_key($tracked_revisions, $tracked_entities_on_source[$entity_type_id])) {
        $target_revision_difference[$entity_type_id] = $revision_difference;
      }
    }

    return $target_revision_difference;
  }

  /**
   * {@inheritdoc}
   */
  public function getDifferringRevisionIdsOnSource() {
    $source_revision_difference = [];

    $tracked_entities_on_source = $this->workspaceAssociation->getTrackedEntities($this->sourceWorkspace->id());
    $tracked_entities_on_target = $this->workspaceAssociation->getTrackedEntities($this->targetWorkspace->id());
    foreach ($tracked_entities_on_source as $entity_type_id => $tracked_revisions) {
      // Now we compare the revision IDs which are tracked by the source
      // workspace to those that are tracked by the target workspace, and the
      // difference between these two arrays gives us all the entities which
      // have a different revision ID on the source.
      if (!isset($tracked_entities_on_target[$entity_type_id])) {
        $source_revision_difference[$entity_type_id] = $tracked_revisions;
      }
      elseif ($revision_difference = array_diff_key($tracked_revisions, $tracked_entities_on_target[$entity_type_id])) {
        $source_revision_difference[$entity_type_id] = $revision_difference;
      }
    }

    return $source_revision_difference;
  }

  /**
   * {@inheritdoc}
   */
  public function getNumberOfChangesOnTarget() {
    $total_changes = $this->getDifferringRevisionIdsOnTarget();
    return count($total_changes, COUNT_RECURSIVE) - count($total_changes);
  }

  /**
   * {@inheritdoc}
   */
  public function getNumberOfChangesOnSource() {
    $total_changes = $this->getDifferringRevisionIdsOnSource();
    return count($total_changes, COUNT_RECURSIVE) - count($total_changes);
  }

}
