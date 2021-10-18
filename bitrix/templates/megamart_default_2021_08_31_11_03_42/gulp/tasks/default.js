'use strict';

const gulp = require('gulp')

gulp.task(
	'default',
	[
		'images',
		'vendor',
		'styles:watch',
		'scripts:watch'
	]
);
