'use strict';

const gulp = require('gulp');
const gulpif = require('gulp-if')
const es = require('event-stream')
const rename = require('gulp-rename')
const config = require('../config')
const watch = require('gulp-watch')
const scriptsTasks = require('../helpers/scripts_tasks')

var entries = []

if (config.scripts.entries) {
    for (let output in config.scripts.entries) {
        entries.push(config.scripts.entries[output]);
    }
}

gulp.task('scripts', () => {
  var streams = [];

  // Object.keys(config.scripts).forEach((key) => {
    streams.push(
      gulp.src(entries)
        .pipe(scriptsTasks.common())
        .pipe(gulp.dest('./assets/scripts/')) // webpack dest
    );

  // });

  return es.merge.call(undefined, streams);
})

gulp.task('scripts:watch', ['scripts'], () => {
  watch(config.scripts.trackGlobs, () => {
    gulp.start('scripts');
  })
})

gulp.task('scripts:production', ['scripts'], () => {
  var streams = [];

  // Object.keys(config.scripts).forEach((key) => {
    streams.push(
      gulp.src(entries)
        .pipe(scriptsTasks.production())
		.pipe(gulp.dest('./assets/scripts/'))
      // .pipe(gulp.dest(scriptConfig.paths.dest)) webpack dest
    );

  // });

  return es.merge.call(undefined, streams);
})

gulp.task('scripts:production-template', () => {
	gulp.src(config.scripts.components.src)
		.pipe(scriptsTasks.components())
		.pipe(gulp.dest(config.scripts.components.dest))
});
