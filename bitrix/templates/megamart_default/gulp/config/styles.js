'use strict';

const path = require('path')

const autoprefixerOptions = {
  // 'browsers': ['last 50 versions', 'ie >= 9']
}

const sassOptions = {
  includePaths: [
    './node_modules/'
  ]
}

module.exports = {

  resources: {
    paths: {
      src: path.resolve('./resources/sass/**/*.scss'),
      dest: path.resolve('./assets/styles')
    },
    prodPaths: {
      src: ['./assets/styles/**/*.css', '!./assets/styles/**/*.min.css'],
      dest: path.resolve('./assets/styles')
    },
    autoprefixer: autoprefixerOptions,
    sass: sassOptions
  },

   components: {
      paths: {
       src: path.resolve('./components/**/*.scss'),
       dest: path.resolve('./components'),
      },
      prodPaths: {
       src: ['./components/**/*.css', '!./components/**/*.min.css'],
       dest: path.resolve('./components')
      },
      autoprefixer: autoprefixerOptions,
      sass: sassOptions
    },

  // template: {
    // paths: {
      // src: path.resolve('./*.scss'),
      // dest: path.resolve('./'),
    // },
    // prodPaths: {
      // src: path.resolve('./*.css'),
      // dest: path.resolve('./'),
    // },
    // autoprefixer: autoprefixerOptions,
    // sass: sassOptions
  // }

}
