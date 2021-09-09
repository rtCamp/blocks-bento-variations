/**
 * WordPress dependencies.
 */
import { registerBlockVariation } from '@wordpress/blocks';

/**
 * Internal dependencies.
 */
import {
	BLOCK_NAME,
	BENTO_VARIATION_NAME,
	BENTO_VARIATION_TITLE,
	BENTO_VARIATION_ICON,
	BENTO_VARIATION_SCOPE,
} from './constants';

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
