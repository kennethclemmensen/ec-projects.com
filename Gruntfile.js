module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        //Setup the browserSync task to synchronize browsers on different devices
        browserSync: {
            bsFiles: {
                src: '<%= pkg.config.cssFolderPath %>/*.css'
            },
            options: {
                debugInfo: true,
                files: [
                    '<%= pkg.config.cssFolderPath %>*.css',
                    '<%= pkg.config.themeFolderPath %>**/*.php',
                    '<%= pkg.config.jsFolderPath %>*.js'
                ],
                logConnections: true,
                notify: true,
                proxy: '<%= pkg.name %>.test',
                watchTask: true
            }
        },
        //Translate less to css
        less: {
            development: {
                files: {
                    '<%= pkg.config.cssFolderPath %>style.css': '<%= pkg.config.lessFolderPath %>style.less'
                },
                options: {
                    compress: true,
                    optimization: 1
                }
            }
        },
        //Uglify the JavaScript files
        terser: {
            your_target: {
                files: {
                    '<%= pkg.config.jsFolderPath %>minified/script.min.js': ['<%= pkg.config.jsFolderPath %>*.js']
                }
            }
        },
        //Setup the watch task to look for changes in files
        watch: {
            options: {
                livereload: true
            },
            scripts: {
                files: ['<%= pkg.config.jsFolderPath %>**/*.js'],
                options: {
                    spawn: false
                },
                tasks: ['terser']
            },
            styles: {
                files: ['<%= pkg.config.lessFolderPath %>**/*.less'],
                options: {
                    spawn: false
                },
                tasks: ['less']
            }
        }
    });

    //Load all tasks
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-terser');

    //Register the default tasks
    grunt.registerTask('default', ['browserSync', 'watch']);
};