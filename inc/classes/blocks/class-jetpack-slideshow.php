<?php
/**
 * 'Jetpack: Slideshow' block related functionalities.
 *
 * @package blocks-bento-variations
 */

namespace Blocks_Bento_Variations\Features\Inc\Blocks;

use Blocks_Bento_Variations\Features\Inc\Assets;
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
	 * Bento component's asset handle.
	 */
	const AMP_BASE_CAROUSEL_HANDLE = 'amp-base-carousel';

	/**
	 * Bento component's asset version.
	 */
	const AMP_BASE_CAROUSEL_VERSION = '1.0';

	/**
	 * AMP Runtime script's handle.
	 */
	const AMP_RUNTIME_HANDLE = 'amp-runtime';

	/**
	 * Block assets' handle.
	 */
	const ASSETS_HANDLE = 'jetpack-slideshow-bento';

	/**
	 * Current block's block attributes.
	 *
	 * @var array Block Attributes.
	 */
	protected $block_attributes = [];

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

		if ( ! $this->initialize_block_attributes( $attributes ) ) {
			return '';
		}

		// Loads block's required front-end scripts & styles.
		$this->enqueue_block_assets();

		if ( class_exists( '\Jetpack_Gutenberg' ) && function_exists( '\Automattic\Jetpack\Extensions\Slideshow\render_amp' ) ) {
			\Jetpack_Gutenberg::load_assets_as_required( 'slideshow' );

			if ( is_bento( $this->block_attributes ) ) {
				return $this->render_bento();
			}

			if ( is_amp_request() ) {
				return \Automattic\Jetpack\Extensions\Slideshow\render_amp( $this->block_attributes );
			}
		}

		return $content;
	}

	/**
	 * Render slideshow block for AMP
	 *
	 * @return string
	 */
	protected function render_bento() {
		if ( empty( $this->block_attributes['ids'] ) ) {
			return '';
		}

		static $wp_block_jetpack_slideshow_id = 0;
		$wp_block_jetpack_slideshow_id++;

		$ids      = $this->block_attributes['ids'];
		$autoplay = empty( $this->block_attributes['autoplay'] ) ? false : true;
		$classes  = 'wp-block-jetpack-slideshow_container swiper-container';

		return sprintf(
			'<div class="%1$s" id="wp-block-jetpack-slideshow__%2$d"><div class="wp-block-jetpack-slideshow_container swiper-container">%3$s%4$s</div></div>',
			esc_attr( $classes ),
			absint( $wp_block_jetpack_slideshow_id ),
			$this->amp_base_carousel( $wp_block_jetpack_slideshow_id ),
			''
		);
	}

	/**
	 * Generate amp-base-carousel markup
	 *
	 * @param int $block_ordinal The ordinal number of the block, used in unique ID.
	 *
	 * @return string amp-base-carousel markup.
	 */
	protected function amp_base_carousel( $block_ordinal ) {
		$ids         = empty( $this->block_attributes['ids'] ) ? [] : $this->block_attributes['ids'];
		$first_image = wp_get_attachment_metadata( $ids[0] );
		$delay       = empty( $this->block_attributes['delay'] ) ? 3 : absint( $this->block_attributes['delay'] );
		$autoplay    = empty( $this->block_attributes['autoplay'] ) ? false : $this->block_attributes['autoplay'];
		$width       = empty( $first_image['width'] ) ? 800 : $first_image['width'];
		$height      = empty( $first_image['height'] ) ? 600 : $first_image['height'];
		return sprintf(
			'<amp-base-carousel width="%1$d" height="%2$d" layout="responsive" data-next-button-aria-label="%3$s" data-prev-button-aria-label="%4$s" %5$s id="wp-block-jetpack-slideshow__amp-base-carousel__%6$s" loop snap="true">%7$s</amp-base-carousel>',
			esc_attr( $width ),
			esc_attr( $height ),
			esc_attr__( 'Next Slide', 'jetpack' ),
			esc_attr__( 'Previous Slide', 'jetpack' ),
			$autoplay ? 'auto-advance="true" auto-advance-interval=' . esc_attr( $delay * 1000 ) : '',
			absint( $block_ordinal ),
			implode( '', $this->slides( $ids, $width, $height ) )
		);
	}

	/**
	 * Generate array of slides markup. Alias of the original function, with necessary markup changes.
	 *
	 * @param array $ids Array of image ids.
	 * @param int   $width Width of the container.
	 * @param int   $height Height of the container.
	 *
	 * @return array Array of slides markup.
	 */
	protected function slides( $ids = [], $width = 400, $height = 300 ) {
		return array_map(
			function ( $id ) use ( $width, $height ) {
				$caption    = wp_get_attachment_caption( $id );
				$figcaption = $caption ? sprintf(
					'<figcaption class="wp-block-jetpack-slideshow_caption gallery-caption">%s</figcaption>',
					wp_kses_post( $caption )
				) : '';
				$image      = wp_get_attachment_image(
					$id,
					[ $width, $height ],
					false,
					[
						'class'      => 'wp-block-jetpack-slideshow_image',
						'object-fit' => 'contain',
					]
				);
				return sprintf(
					'<div class="wp-block-jetpack-slideshow_slide"><figure>%s%s</figure></div>',
					$image,
					$figcaption
				);
			},
			$ids
		);
	}

	/**
	 * Initializes class variable $block_attributes.
	 *
	 * @param array $attributes Array containing block attributes.
	 *
	 * @return bool Whether or not block attributes have been initialized with given value.
	 */
	protected function initialize_block_attributes( $attributes ) {
		if ( empty( $attributes ) || ! is_array( $attributes ) ) {
			return false;
		}

		$this->block_attributes = $attributes;
		return true;
	}

	/**
	 * Enqueue required scripts & styles for the block.
	 * Enqueues Bento component compatibility assets conditionally.
	 *
	 * @return void
	 */
	protected function enqueue_block_assets() {

		Assets::get_instance()->register_style( self::ASSETS_HANDLE, 'css/style-jetpack-slideshow.css' );

		wp_enqueue_style( self::ASSETS_HANDLE );

		if ( ! is_bento( $this->block_attributes ) ) {
			return; // Rest of the assets are Bento specific.
		}

		$src                      = sprintf( 'https://cdn.ampproject.org/v0/amp-base-carousel-%s.js', self::AMP_BASE_CAROUSEL_VERSION );
		$amp_base_carousel_script = wp_scripts()->query( self::AMP_BASE_CAROUSEL_HANDLE );

		if ( $amp_base_carousel_script ) {
			// Make sure that 1.0 (Bento) is used instead of 0.1 (latest).
			$amp_base_carousel_script->src = $src;
		} else {
			wp_register_script( self::AMP_BASE_CAROUSEL_HANDLE, $src, [ self::AMP_RUNTIME_HANDLE ], self::AMP_BASE_CAROUSEL_VERSION, false );
		}

		wp_enqueue_script( self::AMP_BASE_CAROUSEL_HANDLE );

		$src                     = sprintf( 'https://cdn.ampproject.org/v0/amp-base-carousel-%s.css', self::AMP_BASE_CAROUSEL_VERSION );
		$amp_base_carousel_style = wp_styles()->query( self::AMP_BASE_CAROUSEL_HANDLE );

		if ( $amp_base_carousel_style ) {
			// Make sure that 1.0 (Bento) is used instead of 0.1 (latest).
			$amp_base_carousel_style->src = $src;
		} else {
			wp_register_style( self::AMP_BASE_CAROUSEL_HANDLE, $src, [], self::AMP_BASE_CAROUSEL_VERSION, false );
		}

		if ( ! is_amp_request() ) { // AMP plugin flags this stylesheet's validation error, not sure why.
			wp_enqueue_style( self::AMP_BASE_CAROUSEL_HANDLE );
		}

	}

}
