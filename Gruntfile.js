module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        //Setup the browserSync task to synchronize browsers on different devices
        browserSync: {
            bsFiles: {
                src: '<%= pkg.cssFolderPath %>/*.css'
            },
            options: {
                debugInfo: true,
                files: [
                    '<%= pkg.cssFolderPath %>*.css',
                    '<%= pkg.themeFolderPath %>**/*.php',
                    '<%= pkg.jsFolderPath %>*.js'
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
                    '<%= pkg.cssFolderPath %>style.css': '<%= pkg.lessFolderPath %>style.less'
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
                    '<%= pkg.jsFolderPath %>minified/script.min.js': ['<%= pkg.jsFolderPath %>*.js']
                }
            }
        },
        //Setup the watch task to look for changes in files
        watch: {
            options: {
                livereload: true
            },
            scripts: {
                files: ['<%= pkg.jsFolderPath %>**/*.js'],
                options: {
                    spawn: false
                },
                tasks: ['terser']
            },
            styles: {
                files: ['<%= pkg.lessFolderPath %>**/*.less'],
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