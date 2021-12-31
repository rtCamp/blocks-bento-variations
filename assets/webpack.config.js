const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const plugins = [];

function resolve(...paths) {
	return path.resolve(__dirname, ...paths);
}

defaultConfig.plugins.forEach((item) => {
	if ('minicssextractplugin' === item.constructor.name.toLowerCase()) {
		item.options.filename = '../css/[name].css';
		item.options.chunkFilename = '../css/[name].css';
		item.options.esModule = true;
	}

	if ('livereloadplugin' === item.constructor.name.toLowerCase()) {
		return;
	}

	plugins.push(item);
});

module.exports = {
	...defaultConfig,

	plugins,

	entry: {
		editor: resolve('src/js/editor.js'),
		'jetpack-slideshow': resolve('src/js/blocks/jetpack-slideshow/view.js'),
		'coblocks-accordion': resolve('src/js/blocks/coblocks-accordion/view.js'),
	},

	output: {
		filename: '[name].js',
		path: resolve('build/js'),
	},
};
