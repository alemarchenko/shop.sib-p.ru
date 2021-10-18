'use strict';

const gulp = require('gulp')
const runSequence = require('run-sequence')
const path = require('path')
const svgSprite = require('gulp-svg-sprite')
const config = require('../config.js')

gulp.task('svg-sprites', () => {
  return gulp.src(config.svgSprite.paths.src)
    .pipe(svgSprite({
      shape: {
        id: {
          generator(name) {
            return 'svg-' + path.basename(name, '.svg')
          }
        }
      },
      mode: {
        symbol: {
          sprite: config.svgSprite.paths.file,
          symbol: true,
          render: {
            scss: {
              dest: config.svgSprite.paths.scss,
              template: config.svgSprite.paths.template
            }
          }
        }
      },
      svg: {
        namespaceClassnames: false
      }
    }))
    .pipe(gulp.dest(config.svgSprite.paths.dest))
})
