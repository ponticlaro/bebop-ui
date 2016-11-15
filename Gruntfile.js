/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({

    meta: {
      ui_js_path: 'src/UI/assets/js',
    },

    jshint: {
      ui_main: [
        '<%= meta.ui_main_js_path %>'
      ],
    },

    requirejs: {
      main: {
        options: {
          baseUrl: './src/UI/assets/js',
          include: [ 
            'bebop-ui.js'
          ],
          out: './src/UI/assets/js/bebop-ui.min.js'
        }
      },
      media: {
        options: {
          baseUrl: './src/UI/assets/js',
          include: [ 
            'bebop-ui--media.js' 
          ],
          out: './src/UI/assets/js/bebop-ui--media.min.js'
        }
      },
      list: {
        options: {
          baseUrl: './src/UI/assets/js',
          include: [ 
            'bebop-ui--list.js' 
          ],
          out: './src/UI/assets/js/bebop-ui--list.min.js'
        }
      },
      multilist: {
        options: {
          baseUrl: './src/UI/assets/js',
          include: [ 
            'bebop-ui--multilist.js' 
          ],
          out: './src/UI/assets/js/bebop-ui--multilist.min.js'
        }
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-requirejs');

  // Default task.
  grunt.registerTask('default', [
    'jshint',
    'requirejs'
  ]);
};