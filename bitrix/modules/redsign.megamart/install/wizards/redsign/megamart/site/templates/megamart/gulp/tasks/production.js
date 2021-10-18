'use strict';

const gulp = require('gulp')
const config = require('../config')

gulp.task('production', () => {
  config.production = true;
  gulp.start(['styles:production', 'scripts:production-template', 'scripts:production']);
})
