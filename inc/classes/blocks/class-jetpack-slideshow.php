<?php
/**
 * Assets class.
 *
 * @package blocks-bento-variations
 */

namespace Blocks_Bento_Variations\Features\Inc\Blocks;

use Blocks_Bento_Variations\Features\Inc\Traits\Singleton;

/**
 * Class Jetpack_Slideshow
 */
class Jetpack_Slideshow {

	use Singleton;

	/**
	 * Block's name.
	 *
	 * @var string
	 */
	const NAME = 'jetpack/slideshow';

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		/**
		 * Priority 9, because Jetpack plugin registers at default (10) priority.
		 * We want to override the original block registration.
		 */
		add_action( 'init', [ $this, 'register_block' ], 9 );

	}

	/**
	 * Registers the 'Jetpack Slideshow' block.
	 */
	public function register_block() {
		if ( class_exists( '\Automattic\Jetpack\Blocks' ) ) {
			\Automattic\Jetpack\Blocks::jetpack_register_block(
				self::NAME,
				[
					'render_callback' => [ $this, 'render_block' ],
				]
			);
		}
	}

	/**
	 * Slideshow block registration/dependency declaration.
	 *
	 * @param array  $attributes Array containing the slideshow block attributes.
	 * @param string $content    String containing the slideshow block content.
	 *
	 * @return string
	 */
	public function render_block( $attributes, $content ) {

		if ( class_exists( 'Jetpack_Gutenberg' ) ) {
			Jetpack_Gutenberg::load_assets_as_required( 'slideshow' );
		}

		return $content;
	}
}
