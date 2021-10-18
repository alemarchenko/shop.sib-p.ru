'use strict';

const path = require('path')
const stylesOptions = require('./config/styles')
const scriptsOptions = require('./config/scripts');

const config = {
  styles: stylesOptions,
  scripts: scriptsOptions,

  svgSprite: {
    paths: {
      src: path.resolve('./resources/svg-icons/**/*.svg'),
      dest: path.resolve('./assets/images/'),
      file: '../icons.svg',
      scss: path.resolve('../../resources/styles/_sprite_svg.scss'),
      template: path.resolve('./resources/styles/_sprite_svg_template.scss')
    }
  },

  production: false
}

module.exports = config;
