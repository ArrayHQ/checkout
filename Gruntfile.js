module.exports = function(grunt) {

    // Configure tasks
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        watch: {
            css: {
                files: ['**/*.scss'],
                tasks: ['autoprefixer', 'csscomb', 'sass'],
                options: {
                    spawn: false,
                }
            },

            livereload: {
                options: {
                    livereload: true
                },
                files: [
                    'style.css'
                ]
            }
        },

        // Compile SASS
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'style.css': 'inc/sass/style.scss'
                }
            }
        },

        autoprefixer: {
            options: {
                browsers: ['> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1', 'ie 9']
            },
            single_file: {
                src: 'style.css',
                dest: 'style.css'
            }
        },

        'csscomb': {
            dist: {
                files: {
                    'style.css': ['style.css']
                }
            }
        },

        // Generate RTL stylesheet
        cssjanus: {
            theme: {
                options: {
                    swapLtrRtlInUrl: false
                },
                files: [
                    {
                        src: 'style.css',
                        dest: 'rtl.css'
                    }
                ]
            }
        },

        // Generate POT
        makepot: {
            theme: {
                options: {
                    type: 'wp-theme'
                }
            }
        },

        // Build for Creative Market
        'string-replace': {
        dist: {
            files: {
                    './':
                    'inc/admin/getting-started/getting-started.php'
                },
                options: {
                    replacements: [
                        {
                            pattern: 'page to view the help file, access theme updates or ask us a question.',
                            replacement: 'page to view the help file and latest theme updates.'
                        },
                        {
                            pattern: /hide-cm/g,
                            replacement: 'hide-creative'
                        },
                        {
                            pattern: /show-cm/g,
                            replacement: 'show-creative'
                        }
                    ]
                }
            }
        },

        shell: {
            makeDir: {
                command: 'zip -r ../checkout.zip ../checkout -x@../exclude.lst'
            }
        }
    });

    // Watch
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Sass
    grunt.loadNpmTasks('grunt-contrib-sass');

    // CSSComb
    grunt.loadNpmTasks('grunt-csscomb');

    // Autoprefixer
    grunt.loadNpmTasks('grunt-autoprefixer');

    // Replace
    grunt.loadNpmTasks('grunt-string-replace');

    // Shell
    grunt.loadNpmTasks('grunt-shell');

    // Generate POT
    grunt.loadNpmTasks( 'grunt-wp-i18n' );

    // Generate RTL
    grunt.loadNpmTasks('grunt-cssjanus');

    // Register tasks
    grunt.registerTask('default', [
        'watch',
        'sass',
        'autoprefixer',
        'csscomb',
        'string-replace',
        'shell',
        //'wp-i18n',
        'cssjanus'
    ]);

    // Build for Creative Market
    grunt.registerTask('creative', ['shell', 'string-replace' ])

};