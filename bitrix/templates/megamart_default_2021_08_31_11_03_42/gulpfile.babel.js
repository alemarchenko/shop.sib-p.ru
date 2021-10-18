import { series, parallel, src, dest, watch} from 'gulp';
import del from 'del';
import sass from 'gulp-sass';
import rollup from 'gulp-better-rollup';
import resolve from 'rollup-plugin-node-resolve';
import commonjs from 'rollup-plugin-commonjs';
import babel from 'rollup-plugin-babel';
import sourcemaps from 'gulp-sourcemaps';
import minify from 'gulp-minify';
import cleanCSS from 'gulp-clean-css';
import rename from 'gulp-rename';
import path from 'path';
import filter from 'gulp-filter';

import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';

const stylesTranspile = (input, output) => {
	return src(input)
		.pipe(sourcemaps.init())
		.pipe(sass({
			includePaths: ['./node_modules/']
		}))
		.pipe(postcss([
			autoprefixer()
		]))
		.pipe(sourcemaps.write('./'))
		.pipe(dest(output))
};

const jsTranspile = (input, output, rollupCustomOptions = {}) => {

	const rollupOptions = Object.assign(
		{
			plugins: [
				resolve(),
				commonjs(),
				babel({
					babelrc: false,
					presets: [['@babel/env', { modules: false }]],
				})
			],
		},
		rollupCustomOptions
	);

	return src(input)
		.pipe(sourcemaps.init())
		.pipe(rollup(rollupOptions, {
			format: 'iife',
			globals: {
				'jquery': 'jQuery',
			}
		}))
		.pipe(sourcemaps.write('./'))
		.pipe(dest(output));
};

export const clean = done => del('./assets/', done);

const templateStyles = () => stylesTranspile('./resources/sass/**/*.scss', './assets/styles');
const componentStyles = () => stylesTranspile('./components/**/*.scss', './components');
const blocksStyles = parallel(
	() => stylesTranspile('../../blocks/**/*.scss', '../../blocks/'),
	() => stylesTranspile('./resources/sass/blocks24.scss', '../../../b24app/version1/app/assets/styles')
);

export const styles = parallel(
	templateStyles,
	componentStyles
);
styles.displayName = 'styles';
styles.description = '';

export const watchStyles = parallel(
	() => watch('./resources/sass/**/*.scss', templateStyles),
	() => watch('./components/**/*.scss', componentStyles),
);
watchStyles.displayName = 'styles:watch';
watchStyles.description = '';

const jsVendor = () => jsTranspile('./resources/js/vendor.js', './assets/scripts');
const jsTemplate = () => jsTranspile('./resources/js/main.js', './assets/scripts', { external: ['jquery']});

const jsBlocks = parallel(
	() => jsTranspile('../../blocks/redsign/**/src/*.js', function(file){
		file.dirname = path.resolve(file.dirname, '../');
	    return file.base;
	}, { external: ['jquery']}),
	() => jsTranspile('./resources/js/blocks24.js', '../../../b24app/version1/app/assets/scripts')
);

export const blocks = parallel(
	series(
		blocksStyles,
		() => watch(['../../blocks/**/*.scss', './resources/sass/**/*.scss'], blocksStyles),
	),
	series(
		jsBlocks,
		() => watch(['../../blocks/redsign/**/src/*.js', './resources/js/**/*.js'], jsBlocks),
	)
);
blocks.displayName = 'blocks';
blocks.description = '';

export const js = series(
	jsVendor,
	jsTemplate
);

export const watchJs = parallel(
	() => watch('./resources/js/**/*.js', jsTemplate),
);
watchJs.displayName = 'js:watch';
watchJs.description = '';

export const _watch = parallel(watchStyles, watchJs);
_watch.displayName = 'watch';
_watch.description = '';

export const moveVendor = () => (
	src('./resources/vendor/**/*.*')
		.pipe(dest('./assets/vendor/'))
);
moveVendor.displayName = 'vendor';
moveVendor.description = '';

export const moveImages = () => (
	src('./resources/images/**/*.*')
		.pipe(dest('./assets/images/'))
);
moveImages.displayName = 'images';
moveImages.description = '';


//const buildJs = () => ();

export const buildJs = parallel(
	() => (
		src(['./assets/scripts/**/*.js', '!./assets/scripts/**/*.min.js', '!./assets/scripts/**/*.map.js'], { dot: true })
			.pipe(minify({
				ext: {
					min: '.min.js'
				},
				ignoreFiles: ['.min.js']
			}))
			.pipe(filter(['**/*.min.js']))
			.pipe(dest('./assets/scripts'))
	),
	() => (
		src(['./components/**/*.js', '!./components/**/*.min.js', '!./components/**/*.map.js'], { dot: true })
			.pipe(minify({
				ext: {
					min: '.min.js'
				},
				ignoreFiles: ['.min.js']
			}))
			.pipe(dest('./components'))
	)
);

const buildStyles = parallel(
	() => (
		src(['./assets/styles/**/*.css', '!./assets/styles/**/*.min.css'], { dot: true })
			.pipe(cleanCSS({
				inline: ['none'],
				level: 2
			}))
			.pipe(rename({
			suffix: '.min'
			}))
			.pipe(dest('./assets/styles'))
	),
	() => (
		src(['./components/**/*.css', '!./components/**/*.min.css'], { dot: true })
			.pipe(cleanCSS({
				inline: ['none'],
				level: 2
			}))
			.pipe(rename({
				suffix: '.min'
			}))
			.pipe(dest('./components'))
	)
);

export const build = series(
	clean,
	parallel(
		moveVendor,
		moveImages,
		styles, 
		js
	),
	parallel(
		buildStyles,
		buildJs
	)
);

build.displayName = 'build';
build.description = '';

export default series(
	clean,
	parallel(moveVendor, moveImages, styles, js),
	_watch
);