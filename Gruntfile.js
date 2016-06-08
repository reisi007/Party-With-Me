module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        babel: {
            options: {
                presets: ["es2015"],
                sourceMap: true,
                compact: true,
                babelrc: false
            },
            files: {
                expand: true,
                src: ['<%= concat.dist.dest %>'],
                ext: '-babel.js'
            }
        },
        clean: {
            src: ['deploy/**']
        },
        concat: {
            options: {
                separator: ';'
            },
            dist: {
                src: ['node_modules/dialog-polyfill/dialog-polyfill.js', 'hidden/resources/js/**/*.js'],
                dest: 'hidden/resources/tmp/<%= pkg.name %>.js'
            }
        },
        copy: {
            deploy: {
                files: [
                    {
                        expand: true,
                        dot: true,
                        src: ['api/**', 'png/**', 'res/**', 'templates/**'],
                        dest: 'deploy/'
                    },
                    {
                        expand: true,
                        dot: true,
                        src: ['hidden/*', 'hidden/admin/**', 'hidden/at/**', 'hidden/fbSdk/**', 'hidden/partials/**', 'hidden/resources/**'],
                        dest: 'deploy/'
                    }, {
                        expand: true,
                        dot: true,
                        src: ['*'],
                        dest: 'deploy/'
                    }
                ]
            },
            dev: {
                files: [
                    {
                        expand: true,
                        flatten: true,
                        src: ['node_modules/dialog-polyfill/dialog-polyfill.css'],
                        dest: 'res/'
                    }
                ]
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'res/pwm.css': 'hidden/resources/css/ui/pwm.scss'
                }
            }
        },
        uglify: {
            options: {
                preserveComments: false,
                screwIE8: true
            },
            default: {
                options: {mangle: true},
                files: {
                    'res/<%= pkg.name %>.min.js': ['hidden/resources/tmp/<%= pkg.name %><%=babel.files.ext %>']
                }
            },
            dev: {
                options: {mangle: false, beautify: true},
                files: {
                    'res/<%= pkg.name %>.min.js': ['hidden/resources/tmp/<%= pkg.name %><%=babel.files.ext %>']
                }
            }
        },
        watch: {
            JS: {
                files: ['<%= concat.dist.src %>'],
                tasks: ['watcherDoJsNoUgly'],
                options: {spawn: true}
            },
            SCSS: {
                files: ['hidden/resources/css/**/*.scss'],
                tasks: ['sass'],
                options: {spawn: true}
            }
        }
    });

    grunt.loadNpmTasks('grunt-babel');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('watcherDoJsNoUgly', ['concat', 'babel', 'uglify:dev']);
    grunt.registerTask('watcherDoJs', ['concat', 'babel', 'uglify:default']);
    grunt.registerTask('default', ['watcherDoJsNoUgly', 'copy:dev', 'sass', 'watch']);
    grunt.registerTask('deploy', ['watcherDoJs', 'clean', 'copy:dev', 'sass', 'copy:deploy'])
};