<?php
/**
 * 'Atomic block: Sharing' block related functionalities.
 *
 * @package  blocks-bento-variations
 */

namespace Blocks_Bento_Variations\Features\Inc\Blocks;

use Blocks_Bento_Variations\Features\Inc\Assets;
use Blocks_Bento_Variations\Features\Inc\Traits\Singleton;

/**
 * Class Atomic blocks Sharing.
 */
class Atomic_Sharing {

	use Singleton;

	/**
	 * Block's Name.
	 *
	 * @var string
	 */
	const NAME = 'atomic-blocks/ab-sharing';

	/*
	 * Bento component's asset handle.
	 */
	const BENTO_SOCIAL_SHARE_HANDLE = 'bento-social-share';

	/**
	 * Block assets' handle.
	 */
	const ASSETS_HANDLE = 'atomic-sharing-bento';

	/**
	 * Bento Runtime script's handle.
	 */
	const BENTO_RUNTIME_HANDLE = 'bento-runtime';

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		add_filter( 'render_block', [ $this, 'render_block' ], 10, 2 );
	}

	/**
	 *  Necessary modifications to inject Bento.
	 *
	 * @param string $block_content Block's HTML markup in string format.
	 * @param array  $block An array containing block information.
	 *
	 * @return string Block's modified HTML markup in string format.
	 */
	public function render_block( $block_content, $block ) {

		if (
			is_admin() ||
			self::NAME !== $block['blockName'] ||
			! is_bento( $block['attrs'] )
		) {
			return $block_content;
		}

		$this->enqueue_block_assets();
		$is_amp = is_amp_request();

		// Todo: Change markup to inject bento social share component.
		return $block_content;
	}

	/**
	 * Enqueue required scripts & styles for the block.
	 * Enqueues Bento component compatibility assets conditionally.
	 *
	 * @return void
	 */
	protected function enqueue_block_assets() {

		Assets::get_instance()->register_style( self::ASSETS_HANDLE, 'css/style-atomic-sharing.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );

		if ( \is_amp_request() ) {
			return;
		}

		$bento_social_share_script_src = 'https://cdn.ampproject.org/v0/bento-social-share-1.0.js';
		$bento_social_share_script     = wp_scripts()->query( self::BENTO_SOCIAL_SHARE_HANDLE );

		if ( ! $bento_social_share_script ) {
			wp_register_script( self::BENTO_RUNTIME_HANDLE, 'https://cdn.ampproject.org/bento.js', [], 'v0', false );
			wp_register_script( self::BENTO_SOCIAL_SHARE_HANDLE, $bento_social_share_script_src, [ self::BENTO_RUNTIME_HANDLE ], 'v0', false );
		}

		wp_enqueue_script( self::BENTO_SOCIAL_SHARE_HANDLE );

		$bento_social_share_style_src = 'https://cdn.ampproject.org/v0/bento-social-share-1.0.css';
		$bento_social_share_style     = wp_styles()->query( self::BENTO_SOCIAL_SHARE_HANDLE );

		if ( ! $bento_social_share_style ) {
			wp_register_style( self::BENTO_SOCIAL_SHARE_HANDLE, $bento_social_share_style_src, [], 'v0', false );
		}

		wp_enqueue_style( self::BENTO_SOCIAL_SHARE_HANDLE );
	}
}
