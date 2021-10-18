'use strict';

const gulp = require('gulp')
const lazypipe = require('lazypipe')
const emptypipe = require('gulp-empty')
const gulpif = require('gulp-if')
const rename = require('gulp-rename')
const minify = require('gulp-minify')
const config = require('../config')

const Encore = require('@symfony/webpack-encore');
const webpackStream = require('webpack-stream');

const autoProvideVariables = {
	$: 'jquery',
	jQuery: 'jquery',
	'window.jQuery': 'jquery',
	Popper: ['popper.js', 'default'],
	velocty: 'velocity'
};

const vendorEntries = [
	'jquery',
	// 'bootstrap',
	'owl.carousel',
	'jquery-lazy',
	//'readmore-js',
	'velocity-animate',
	'sticky-sidebar',
	'@fancyapps/fancybox',
	'jquery.scrollbar',
];

module.exports = {

	common: (options) => {

		//Encore.reset();

		Encore
			.configureRuntimeEnvironment('dev-server')
			.enableVersioning(false)
			.setOutputPath('assets/scripts/')
			.setPublicPath('/')

		Encore.autoProvideVariables(autoProvideVariables);
		Encore.createSharedEntry('vendor', vendorEntries);

		if (config.scripts.entries) {
			for (let output in config.scripts.entries) {
				Encore.addEntry(output, config.scripts.entries[output]);
			}
		}

		const webpackConfig = Encore.getWebpackConfig();
		if (webpackConfig.module && webpackConfig.module.rules) {
			for (const rule of webpackConfig.module.rules) {
				if (rule.use) {
					for (const loader of rule.use) {
						if (loader.loader === 'babel-loader') {
							rule.exclude = undefined;
						}
					}
				}
			}
		}

		return lazypipe()
			.pipe(() => {
				return webpackStream(webpackConfig)
			})()
			.on('error', function() {
				this.emit('end')
			});
	},

	production: (options) => {

		Encore.reset();

		Encore
			.configureRuntimeEnvironment('production')
			.enableVersioning(false)
			.setOutputPath('assets/scripts/')
			.setPublicPath('/')

		Encore.autoProvideVariables(autoProvideVariables);
		Encore.createSharedEntry('vendor.min', vendorEntries);

		if (config.scripts.entries) {
			for (let output in config.scripts.entries) {
				Encore.addEntry(output + '.min', config.scripts.entries[output]);
			}
		}

		const webpackConfig = Encore.getWebpackConfig();
		if (webpackConfig.module && webpackConfig.module.rules) {
			for (const rule of webpackConfig.module.rules) {
				if (rule.use) {
					for (const loader of rule.use) {
						if (loader.loader === 'babel-loader') {
							rule.exclude = undefined;
						}
					}
				}
			}
		}

		return lazypipe()
			.pipe(() => {
				return webpackStream(webpackConfig)
			})()
			.on('error', function() {
				this.emit('end')
			});
	},

	components: (options) => lazypipe()
		//.pipe(() => clean())
		.pipe(() => minify({
			ext: {
				min: '.min.js'
			}
		}))()
}
