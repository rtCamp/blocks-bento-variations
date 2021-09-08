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

		if ( class_exists( '\Jetpack_Gutenberg' ) && function_exists( '\Automattic\Jetpack\Extensions\Slideshow\render_amp' ) ) {
			\Jetpack_Gutenberg::load_assets_as_required( 'slideshow' );

			if ( is_bento( $this->block_attributes ) ) {
				$this->enqueue_bento_compatibility_assets();
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
			'<div class="%1$s" id="wp-block-jetpack-slideshow__%2$d"><div class="wp-block-jetpack-slideshow_container swiper-container">%3$s%4$s%5$s</div></div>',
			esc_attr( $classes ),
			absint( $wp_block_jetpack_slideshow_id ),
			$this->amp_base_carousel( $wp_block_jetpack_slideshow_id ),
			$autoplay ? \Automattic\Jetpack\Extensions\Slideshow\autoplay_ui( $wp_block_jetpack_slideshow_id ) : '',
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
	 * Generate array of slides markup
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
					array( $width, $height ),
					false,
					array(
						'class'      => 'wp-block-jetpack-slideshow_image',
						'object-fit' => 'contain',
					)
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
	 * Generate array of bullets markup
	 *
	 * @param array $ids Array of image ids.
	 * @param int   $block_ordinal The ordinal number of the block, used in unique ID.
	 *
	 * @return array Array of bullets markup.
	 */
	protected function bullets( $ids = array(), $block_ordinal = 0 ) {
		$buttons = array_map(
			function ( $index ) {
				$aria_label = sprintf(
					/* translators: %d: Slide number. */
					__( 'Go to slide %d', 'jetpack' ),
					absint( $index + 1 )
				);
				return sprintf(
					'<button option="%d" class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="%s" %s></button>',
					absint( $index ),
					esc_attr( $aria_label ),
					0 === $index ? 'selected' : ''
				);
			},
			array_keys( $ids )
		);

		return sprintf(
			'<amp-selector id="wp-block-jetpack-slideshow__amp-pagination__%1$d" class="wp-block-jetpack-slideshow_pagination swiper-pagination swiper-pagination-bullets amp-pagination" on="select:wp-block-jetpack-slideshow__amp-base-carousel__%1$d.goToSlide(index=event.targetOption)" layout="container">%2$s</amp-selector>',
			absint( $block_ordinal ),
			implode( '', $buttons )
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
	 * Enqueue required scripts & styles for the bento component(s).
	 *
	 * @return void
	 */
	protected function enqueue_bento_compatibility_assets() {

		$src                      = 'https://cdn.ampproject.org/v0/amp-base-carousel-1.0.js';
		$amp_base_carousel_script = wp_scripts()->query( 'amp-base-carousel' );

		if ( $amp_base_carousel_script ) {
			// Make sure that 1.0 (Bento) is used instead of 0.1 (latest).
			$amp_base_carousel_script->src = $src;
		} else {
			wp_register_script( 'amp-base-carousel', $src, array( 'amp-runtime' ), null, false );
		}

		wp_enqueue_script( 'amp-base-carousel' );

		$src                     = 'https://cdn.ampproject.org/v0/amp-base-carousel-1.0.css';
		$amp_base_carousel_style = wp_styles()->query( 'amp-base-carousel' );

		if ( $amp_base_carousel_style ) {
			// Make sure that 1.0 (Bento) is used instead of 0.1 (latest).
			$amp_base_carousel_style->src = $src;
		} else {
			wp_register_style( 'amp-base-carousel', $src, array(), null, false );
		}

		if ( ! is_amp_request() ) { // AMP plugin flags this stylesheet's validation error, not sure why.
			wp_enqueue_style( 'amp-base-carousel' );
		}

	}

}
