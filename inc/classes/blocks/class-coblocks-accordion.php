<?php
/**
 * 'CoBlocks: Accordion' block related functionalities.
 *
 * @package  blocks-bento-variations
 */

namespace Blocks_Bento_Variations\Features\Inc\Blocks;

use Blocks_Bento_Variations\Features\Inc\Assets;
use Blocks_Bento_Variations\Features\Inc\Traits\Singleton;

/**
 * Class CoBlocks Accordion.
 */
class CoBlocks_Accordion {

	use Singleton;

	/**
	 * Block's Name.
	 *
	 * @var string
	 */
	const NAME = 'coblocks/accordion';

	/*
	 * Bento component's asset handle.
	 */
	const BENTO_ACCORDION_HANDLE = 'bento-accordion';

	/**
	 * Block assets' handle.
	 */
	const ASSETS_HANDLE = 'coblocks-accordion-bento';

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

		return sprintf(
			'<div class="wp-block-coblocks-accordion %1s"><bento-accordion>%2s</bento-accordion></div>',
			$is_amp ? 'is_amp' : 'is_bento',
			implode( '', $this->get_sections( $block['innerBlocks'] ) )
		);
	}

	/**
	 * Converts Accordion items to AMP/Bento accordion sections.
	 *
	 * @param array $accordion_items Innerblocks of Each Accordion item.
	 *
	 * @return array Bento Accordion section generated from innerblocks.
	 */
	public function get_sections( $accordion_items ) {

		return array_map(
			function ( $accordion_item ) {
				return sprintf(
					'<section class=wp-block-coblocks-accordion-item" %1s>%2s<div class="wp-block-coblocks-accordion-item__content">%3s</div></section>',
					1 === $accordion_item['attrs']['open'] ? 'expanded' : '',
					$this->accordion_item_title( $accordion_item['innerHTML'] ),
					serialize_blocks( $this->parse_blocks( $accordion_item['innerBlocks'] ) )
				);
			},
			$accordion_items
		);
	}

	/**
	 * Retrieve Accordion title from original markup ( To match the inline styles ).
	 * Amp or Bento Accordion title must be a heading element such as <h1>-<h6> or <header>
	 *
	 * @param string $accordion_item Current Accordion item's HTML markup.
	 *
	 * @return string Accordion item title's HTML markup.
	 */
	public function accordion_item_title( $accordion_item ) {
		$matches = [];
		preg_match( '/<summary class="(.*?)">(.*?)<\/summary>/', $accordion_item, $matches );
		return str_replace( 'summary', 'header', $matches[0] );
	}

	/**
	 * Parse Accordion item's innerblocks
	 *
	 * @param array $blocks Accordion item's innerblocks.
	 *
	 * @return array
	 */
	public function parse_blocks( $blocks ) {

		$parsed_blocks = [];

		foreach ( $blocks as $block ) {
			if ( ! empty( $block['innerBlocks'] ) ) {
				$block['innerBlocks'] = self::parse_blocks( $block['innerBlocks'] );
			}
			$parsed_blocks[] = $block;
		}

		return $parsed_blocks;
	}

	/**
	 * Enqueue required scripts & styles for the block.
	 * Enqueues Bento component compatibility assets conditionally.
	 *
	 * @return void
	 */
	protected function enqueue_block_assets() {

		Assets::get_instance()->register_style( self::ASSETS_HANDLE, 'css/style-coblocks-accordion.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );

		if ( \is_amp_request() ) {
			return;
		}

		$bento_accordion_script_src = 'https://cdn.ampproject.org/v0/bento-accordion-1.0.js';
		$bento_accordion_script     = wp_scripts()->query( self::BENTO_ACCORDION_HANDLE );

		if ( ! $bento_accordion_script ) {
			wp_register_script( self::BENTO_RUNTIME_HANDLE, 'https://cdn.ampproject.org/bento.js', [], 'v0', false );
			wp_register_script( self::BENTO_ACCORDION_HANDLE, $bento_accordion_script_src, [ self::BENTO_RUNTIME_HANDLE ], 'v0', false );
		}

		wp_enqueue_script( self::BENTO_ACCORDION_HANDLE );

		$bento_accordion_style_src = 'https://cdn.ampproject.org/v0/bento-accordion-1.0.css';
		$bento_accordion_style     = wp_styles()->query( self::BENTO_ACCORDION_HANDLE );

		if ( ! $bento_accordion_style ) {
			wp_register_style( self::BENTO_ACCORDION_HANDLE, $bento_accordion_style_src, [], 'v0', false );
		}

		wp_enqueue_style( self::BENTO_ACCORDION_HANDLE );
	}
}
