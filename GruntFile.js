// GRUNT COMMANDS
module.exports = function(grunt) {

  require('jit-grunt')(grunt);

  // CONFIGURE SETTINGS
  grunt.initConfig({

    // BASIC SETTINGS ABOUT PLUGINS
    pkg: grunt.file.readJSON('package.json'),

    // SASS PLUGINS
    sass: {
      dist: {                            // Target
        options: {                       // Target options
          sourcemap: 'none',
          style: 'compressed'
        },
        files: {                                                  // Dictionary of files
          'assets/css/main.css': 'assets/css/sass/style.scss'  // 'destination': 'source'
        }
      }
    },

    // ADD PREFIXES TO MAIN.CSS AND RE-COMPRESS
    postcss: {
      options: {
        map: false,
        processors: [
          require('autoprefixer')
        ]
      },
      dist: {
        src: 'assets/css/main.css'
      }
    },

    // UGLIFY PLUGIN
    uglify: {
      options: {
        mangle: false,
        beautify: false
      },
      modules: {
        files: {
          'assets/js/modules.min.js': [
            'node_modules/dist/baguetteBox.min.js',
            'node_modules/smooth-scroll/dist/js/smooth-scroll.js'
          ]
        }
      },
      libraries: {
        files: {'assets/js/libraries.min.js': 'assets/js/lib/*'}
      },
      scripts: {
        files: {'assets/js/scripts.min.js': 'assets/js/src/*'}
      }
    },

    // WATCH FILES FOR CHANGES
    watch: {
      sass: {
        files: ['assets/css/sass/**'],
        tasks: ['sass']
      },
      postcss: {
        files: ['assets/css/sass/**'],
        tasks: ['postcss']
      },
      jslibraries: {
        files: ['assets/js/lib/*'],
        tasks: ['uglify:libraries']
      },
      jsscripts: {
        files: ['assets/js/src/*'],
        tasks: ['uglify:scripts']
      }
    }

  });

  // DO THE TASK
  grunt.registerTask('default', ['sass']);
  grunt.registerTask('default', ['postcss:dist']);
  grunt.registerTask('default', ['uglify']);
  grunt.registerTask('default', ['watch']);
};
