module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        banner: require('fs').readFileSync('banner.txt').toString(),
        copy: {
            fonts: {
                expand: true,
                flatten: true,
                filter: 'isFile',
                src: 'bower_components/font-awesome/fonts/*',
                dest: 'web/fonts/'
            }
        },
        concat: {
            options: {
                separator: ';'
            },
            ie8: {
                src: [
                    'bower_components/html5shiv/src/html5shiv.js',
                    'bower_components/respond/src/respond.js'
                ],
                dest: 'web/js/ie8.js'
            },
            amsterdamphpjs: {
                src: [
                    'bower_components/jquery/jquery.js',
                    'bower_components/bootstrap/js/transition.js',
                    'bower_components/bootstrap/js/alert.js',
                    'bower_components/bootstrap/js/button.js',
                    'bower_components/bootstrap/js/carousel.js',
                    'bower_components/bootstrap/js/collapse.js',
                    'bower_components/bootstrap/js/dropdown.js',
                    'bower_components/bootstrap/js/modal.js',
                    'bower_components/bootstrap/js/tooltip.js',
                    'bower_components/bootstrap/js/popover.js',
                    'bower_components/bootstrap/js/scrollspy.js',
                    'bower_components/bootstrap/js/tab.js',
                    'bower_components/bootstrap/js/affix.js'
                ],
                dest: 'web/js/amsterdamphp.js'
            }
        },
        less: {
            amsterdamphpcss: {
                src: [
                    'app/Resources/less/amsterdamphp.less'
                ],
                dest: 'web/css/amsterdamphp.css'
            }
        },
        uglify: {
            ie8: {
                options: {
                    report: 'min',
                    banner: '<%= banner %>'
                },
                files: {
                    'web/js/ie8.js': [
                        'web/js/ie8.js'
                    ]
                }
            },
            amsterdamphpjs: {
                options: {
                    report: 'min',
                    banner: '<%= banner %>'
                },
                files: {
                    'web/js/amsterdamphp.js': [
                        'web/js/amsterdamphp.js'
                    ]
                }
            }
        },
        cssmin: {
            amsterdamphpcss: {
                options: {
                    report: 'min',
                    banner: '<%= banner %>'
                },
                src: [
                    'web/css/amsterdamphp.css'
                ],
                dest: 'web/css/amsterdamphp.css'
            }
        },
        watch: {
            files: [
                'bower_components/**', // Admittedly this might be a bit overkill...
                'app/Resources/less/elements.less',
                'app/Resources/less/amsterdamphp.less'
            ],
            tasks: [
                'concat',
                'less'
            ]
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('default', [
        'copy',
        'concat',
        'less',
        'uglify',
        'cssmin'
    ]);

};
