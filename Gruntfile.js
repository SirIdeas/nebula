module.exports = function(grunt) {
  'use strict';
  
  // Project configuration.
  grunt.initConfig({

    pkg: grunt.file.readJSON('package.json'),

    app: {
      root: 'app',
      dist: 'dist',
    },
    
    clean: {
      all: [
        '<%= app.dist %>/*',
        '!<%= app.dist %>/.git'
      ]
    },

    imagemin: {
      options: {
        cache: false,
        optimizationLevel: 3
      },
      all:{
        files: [{
          expand: true,
          cwd: '<%= app.root %>',
          src: 'public/images/**/*.{png,jpg,jpeg}',
          dest: '<%= app.dist %>'
        }]
      }
    },

    svgmin: {
      all:{
        files: [{
          expand: true,
          cwd: '<%= app.root %>',
          src: 'public/images/**/*.svg',
          dest: '<%= app.dist %>'
        }]
      }
    },
    
    copy: {
      all:{
        files: [{
          expand: true,
          dot: true,
          cwd: '<%= app.root %>',
          dest: '<%= app.dist %>',
          src: [
            'am.php',
            '.htaccess',
            'public/fonts/**/*',
            'public/images/**/*.{webp,gif}',
            '{am,conf,views,control,data}/**/*',
            '!conf/compile.conf.php',
          ]
        }, {
          expand: true,
          cwd: 'bower_components/bootstrap/dist',
          dest: '<%= app.dist %>/public/',
          src: 'fonts/**/*'
        }]
      }
    },

    cssmin: {
      all: {
        files: [{
          expand: true,
          cwd: '<%= app.root %>',
          dest: '<%= app.dist %>',
          src: [
            'public/styles/**/*.css',
          ]
        }]
      }
    },

    uglify: {
      all: {
        files: [{
          expand: true,
          cwd: '<%= app.root %>',
          dest: '<%= app.dist %>',
          src: [
            'public/scripts/**/*.js',
          ]
        }]
      }
    },

    htmlmin: {
      all: {
        options: {
          collapseWhitespace: true,
          conservativeCollapse: true,
          collapseBooleanAttributes: true,
          removeComments: false,
          removeCommentsFromCDATA: true,
          removeOptionalTags: true
        },
        files: [{
          expand: true,
          src: [
            '<%= app.dist %>/views/**/*.php',
          ]
        }]
      }
    },

  });

  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-imagemin');
  grunt.loadNpmTasks('grunt-svgmin');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-htmlmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  grunt.registerTask('build', [
    'clean',
    'imagemin',
    'svgmin',
    'copy',
    'cssmin',
    'uglify',
    // 'htmlmin'
  ]);

};