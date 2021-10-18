'use strict';

const path = require('path')
const gulp = require('gulp')
const es = require('event-stream')
const watch = require('gulp-watch')
const config = require('../config.js')
const stylesTasks = require('../helpers/styles_tasks.js')

var stylesSrc = [];

Object.keys(config.styles).forEach((key) => {

  var styleConfig = config.styles[key];
  const taskName = 'styles.' + key;

  gulp.task(taskName, () => {
    return gulp.src(styleConfig.paths.src, {dots: true})
      .pipe(stylesTasks.common(styleConfig))
      .pipe(gulp.dest(styleConfig.paths.dest))
  })

  gulp.task(taskName + ':watch', [taskName], () => {
    gulp.watch(styleConfig.paths.src, () => {
      gulp.start(taskName);
    })
  })

  stylesSrc.push(styleConfig.paths.src);

})

gulp.task('styles', () => {
  var streams = [],
    styleConfig;

  Object.keys(config.styles).forEach((key) => {

    styleConfig = config.styles[key];
    streams.push(
      gulp.src(styleConfig.paths.src, {dot: true})
        .pipe(stylesTasks.common(styleConfig))
        .pipe(gulp.dest(styleConfig.paths.dest))
    );

  });

  return es.merge.call(undefined, streams);
});

gulp.task('styles:watch', ['styles'], () => {
  var files = []
  
  watch(stylesSrc, {dot: true}, () => {
    gulp.start('styles');
  })
});

gulp.task('styles:production', ['styles'], () => {

  var streams = [],
    styleConfig;

  Object.keys(config.styles).forEach((key) => {

    styleConfig = config.styles[key];

    if (!styleConfig.prodPaths) {
      return true;
    }

    streams.push(
      gulp.src(styleConfig.prodPaths.src)
        .pipe(stylesTasks.production(styleConfig))
    );

  });

  return es.merge.call(undefined, streams);
});
