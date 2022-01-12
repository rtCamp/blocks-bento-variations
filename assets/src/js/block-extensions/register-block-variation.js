/**
 * WordPress dependencies.
 */
import { registerBlockVariation } from '@wordpress/blocks';

/**
 * Function to register an individual block's bento variation.
 *
 * @param {Object} variation The variation to be registered.
 */
export const registerBentoVariation = (variation) => {
	if (!variation) {
		return;
	}
	const {
		BLOCK_NAME,
		BENTO_VARIATION_NAME,
		BENTO_VARIATION_TITLE,
		BENTO_VARIATION_ICON,
		BENTO_VARIATION_SCOPE,
	} = variation;

	registerBlockVariation(BLOCK_NAME, {
		name: BENTO_VARIATION_NAME,
		title: BENTO_VARIATION_TITLE,
		icon: BENTO_VARIATION_ICON,
		scop: BENTO_VARIATION_SCOPE,
		attributes: {
			isBento: true,
		},
		isActive: ['isBento'],
	});
};
