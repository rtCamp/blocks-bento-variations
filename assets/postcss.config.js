/**
 * Post css configuration.
 *
 * @type {Object}
 */
module.exports = {

	syntax: 'postcss-scss',

	plugins: {
		'autoprefixer': {},

		'postcss-assets': {
			loadPaths: [ 'img/', 'fonts/' ],
			relative: true
		},
	}
};
