module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        less: {
            development: {
                options: {
                    compress: true,
                    optimization: 1
                },
                files: {
                    'public/themes/<%= pkg.name %>/css/style.css': 'public/themes/<%= pkg.name %>/less/style.less' //dest : src
                }
            }
        },
        watch: {
            options: {
                livereload: true
            },
            scripts: {
                files: ['public/themes/<%= pkg.name %>/js/**/*.js'], //the files to watch
                tasks: ['uglify'], //the task to do
                options: {
                    spawn: false
                }
            },
            styles: {
                files: ['public/themes/<%= pkg.name %>/less/**/*.less'], //the files to watch
                tasks: ['less'], //the task to do
                options: {
                    spawn: false
                }
            }
        },
        uglify: {
            my_target: {
                files: {
                    'public/themes/<%= pkg.name %>/js/minified/script.min.js': ['public/themes/<%= pkg.name %>/js/*.js']
                }
            }
        },
        browserSync: {
            bsFiles: {
                src: 'public/themes/<%= pkg.name %>/css/*.css'
            },
            options: {
                watchTask: true,
                debugInfo: true,
                logConnections: true,
                notify: true,
                host: '192.168.1.88',
                proxy: '<%= pkg.name %>.test',
                files: ['public/themes/<%= pkg.name %>/css/*.css', 'public/themes/<%= pkg.name %>/**/*.twig', 'public/themes/<%= pkg.name %>/js/*.js']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify-es');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-browser-sync');

    grunt.registerTask('default', ['browserSync', 'watch']);
};