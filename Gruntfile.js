module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

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
        uglify: {
            my_target: {
                files: {
                    '<%= pkg.jsFolderPath %>minified/script.min.js': ['<%= pkg.jsFolderPath %>*.js']
                }
            }
        },
        watch: {
            options: {
                livereload: true
            },
            scripts: {
                files: ['<%= pkg.jsFolderPath %>**/*.js'],
                options: {
                    spawn: false
                },
                tasks: ['uglify']
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

    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify-es');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['browserSync', 'watch']);
};