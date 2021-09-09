<?php
/**
 * Helper functions to be used across the plugin.
 *
 * @package blocks-bento-variations
 */

/**
 * Check if it's an AMP request or not.
 *
 * @return bool $is_amp_request Are we on an AMP view.
 */
function is_amp_request() {
	return ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() );
}

/**
 * Determines whether the given block is a Bento variation or not, based on the block attributes.
 *
 * @param array $block_attributes Block attributes.
 *
 * @return bool Whether or not the block is a Bento variation.
 */
function is_bento( $block_attributes ) {
	if ( empty( $block_attributes ) || ! is_array( $block_attributes ) ) {
		return false;
	}

	if ( isset( $block_attributes['isBento'] ) && true === $block_attributes['isBento'] ) {
		return true;
	}

	return false;
}
