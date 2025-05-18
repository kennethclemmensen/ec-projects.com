import { fromEvent } from 'rxjs';
import { EventType } from './eventType';

/**
 * Provides functionality for a slider component
 */
export class Slider {

    private readonly slides: NodeListOf<HTMLElement>;
    private currentSlideIndex: number;

    /**
     * The constructor initializes the slider component
     */
    public constructor() {
        this.slides = document.querySelectorAll<HTMLElement>('.slider__slide');
        this.currentSlideIndex = 0;
        this.setupSlider();
    }

    /**
     * Sets up the slider by adding event listeners for the previous and next arrows
     */
    private setupSlider() {
        const numberOfSlides = this.slides.length;
        let index = 0;
        const previousArrow = document.getElementById('previous-arrow');
        const nextArrow = document.getElementById('next-arrow');
        this.slides.forEach((slide, i) => {
            slide.style.transform = `translateX(${i * 100}%)`;
        });
        if (previousArrow != null && nextArrow != null) {
            fromEvent(previousArrow, EventType.Click).subscribe(() => {
                index--;
                if (index < 0) {
                    index = numberOfSlides - 1;
                }
                this.currentSlideIndex = index;
                this.moveSlides();
            });
            fromEvent(nextArrow, EventType.Click).subscribe(() => {
                index++;
                if (index === numberOfSlides) {
                    index = 0;
                }
                this.currentSlideIndex = index;
                this.moveSlides();
            });
        }
    }

    /**
     * Moves the slides to the current slide index
     */
    private moveSlides() {
        this.slides.forEach((slide, i) => {
            slide.style.transform = `translateX(${(i - this.currentSlideIndex) * 100}%)`;
            slide.style.transition = 'transform 0.5s ease';
        });
    }
}