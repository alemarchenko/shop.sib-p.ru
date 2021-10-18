'use strict';

const path = require('path')
const gulp = require('gulp')

gulp.task('vendor', () => {

	gulp
		.src('./resources/vendor/**/*.*')
		.pipe(gulp.dest('./assets/vendor/'));
});
