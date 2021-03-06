/**
 * WordPress dependencies.
 */
import { addFilter } from '@wordpress/hooks';

/**
 * Allowed Block names
 */
const BLOCKS = [
	'coblocks/accordion',
	'jetpack/slideshow',
	'web-stories/embed',
	'atomic-blocks/ab-sharing',
];

/**
 * Function to extend attributes of the 'CoBlocks Accordion' block.
 *
 * @param {Object} settings  settings object of the block.
 * @param {string} blockName name of the block.
 * @return {Object} settings object.
 */
const extendAttributes = (settings, blockName) => {
	if (!BLOCKS.includes(blockName)) {
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
