'use strict';

const gulp = require('gulp')
const lazypipe = require('lazypipe')
const gulpif = require('gulp-if')
const sass = require('gulp-sass')
const sourcemaps = require('gulp-sourcemaps')
//const autoprefixer = require('gulp-autoprefixer')
const cleanCSS = require('gulp-clean-css')
const rename = require('gulp-rename')
const config = require('../config.js')

const postcss = require('gulp-postcss')
const autoprefixer = require('autoprefixer')

console.log(config.production);

module.exports = {
  common: (options) => lazypipe()
  .pipe(() => gulpif(!config.production, sourcemaps.init()))
    .pipe(function(opt, cb) {
      return sass(opt).on('error', cb)
    }, options.sass, sass.logError)
    .pipe(() => gulpif(config.production, postcss([autoprefixer()]) ))
    .pipe(() => gulpif(!config.production, sourcemaps.write('./')))()
      .on('error', function() {
        this.emit('end')
     }),

  production: (options) => lazypipe()
    .pipe(() => cleanCSS({
        inline: ['none'],
        level: 2
    }))
    .pipe(() => rename({
      suffix: '.min'
    }))
    .pipe(() => gulp.dest(options.paths.dest))()
}
