/**
 * WordPress dependencies.
 */
import { addFilter } from '@wordpress/hooks';

/**
 * Internal dependencies.
 */
import { BLOCK_NAME } from './constants';

/**
 * Function to extend attributes of the 'Jetpack Slideshow' block.
 *
 * @param {Object} settings  settings object of the block.
 * @param {string} blockName name of the block.
 * @return {Object} settings object.
 */
const extendAttributes = (settings, blockName) => {
	if (BLOCK_NAME !== blockName) {
		return settings;
	}

	const newSettings = {
		...settings,

		attributes: {
			...settings.attributes,

			isBento: {
				type: 'boolean',
				default: false,
			},
		},
	};

	return newSettings;
};

addFilter(
	'blocks.registerBlockType',
	'blocks-bento-variations/extend-coblocks-accordion-attributes',
	extendAttributes
);
