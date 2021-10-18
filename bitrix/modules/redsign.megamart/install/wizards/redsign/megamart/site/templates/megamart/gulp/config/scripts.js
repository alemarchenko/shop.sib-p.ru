'use strict';

const path = require('path')

module.exports = {
	entries: {
		'main': './resources/js/main.js',
	},
	components: {
		src: ['./components/**/*.js', '!./components/**/*.min.js', '!./components/**/*.map.js'],
		dest: path.resolve('./components')
	},
	trackGlobs: ['./resources/js/**/*.js'],
}
