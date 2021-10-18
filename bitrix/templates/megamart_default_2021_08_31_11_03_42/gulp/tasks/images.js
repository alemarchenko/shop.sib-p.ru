'use strict';

const path = require('path')
const gulp = require('gulp')

gulp.task('images', () => {

  gulp
    .src('./resources/images/**/*.*')
    .pipe(gulp.dest('./assets/images/'));
});
