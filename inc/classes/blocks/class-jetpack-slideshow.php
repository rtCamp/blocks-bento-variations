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
	const BENTO_BASE_CAROUSE_HANDLE = 'bento-base-carousel';

	/**
	 * AMP Runtime script's handle.
	 */
	const BENTO_RUNTIME_HANDLE = 'bento-runtime';

	/**
	 * Block assets' handle.
	 */
	const ASSETS_HANDLE = 'jetpack-slideshow-bento';

	/**
	 * Component's asset version.
	 *
	 * @var string Component Version.
	 */
	private $bento_base_carousel_version = '1.0';

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

		add_filter( 'render_block', [ $this, 'render_block' ], 10, 2 );

	}

	/**
	 * Necessary modifications to inject Bento.
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

		if ( false === $this->initialize_block_attributes( $block['attrs'] ) ) {
			return $block_content;
		}

		// Loads block's required bento front-end scripts & styles.
		$this->enqueue_block_assets();

		if ( is_bento( $this->block_attributes ) ) {
			return (string) $this->render_bento( $block_content );
		}

		return $block_content;
	}

	/**
	 * Render slideshow block's Bento markup.
	 *
	 * @param string $content The block's markup.
	 *
	 * @return string
	 */
	protected function render_bento( $content ) {
		if ( empty( $this->block_attributes['ids'] ) ) {
			return $content;
		}

		static $wp_block_jetpack_slideshow_id = 0;
		$wp_block_jetpack_slideshow_id++;

		$ids      = $this->block_attributes['ids'];
		$autoplay = empty( $this->block_attributes['autoplay'] ) ? false : true;
		$classes  = 'wp-block-jetpack-slideshow_container swiper-container';

		return sprintf(
			'<div class="%1$s" id="wp-block-jetpack-slideshow__%2$d"><div class="wp-block-jetpack-slideshow_container swiper-container">%3$s</div></div>',
			esc_attr( $classes ),
			absint( $wp_block_jetpack_slideshow_id ),
			$this->bento_base_carousel( $wp_block_jetpack_slideshow_id )
		);
	}

	/**
	 * Generate bento-base-carousel markup
	 *
	 * @param int $block_ordinal The ordinal number of the block, used in unique ID.
	 *
	 * @return string bento-base-carousel markup.
	 */
	protected function bento_base_carousel( $block_ordinal ) {
		$ids         = empty( $this->block_attributes['ids'] ) ? [] : $this->block_attributes['ids'];
		$first_image = wp_get_attachment_metadata( $ids[0] );
		$delay       = empty( $this->block_attributes['delay'] ) ? 3 : absint( $this->block_attributes['delay'] );
		$autoplay    = empty( $this->block_attributes['autoplay'] ) ? false : $this->block_attributes['autoplay'];
		$width       = empty( $first_image['width'] ) ? 800 : $first_image['width'];
		$height      = empty( $first_image['height'] ) ? 600 : $first_image['height'];
		$style       = "height: {$height}px;";

		return sprintf(
			'<bento-base-carousel width="%1$d" height="%2$d" style="%3$s" data-next-button-aria-label="%4$s" data-prev-button-aria-label="%5$s" %6$s id="wp-block-jetpack-slideshow__amp-base-carousel__%7$s" loop>%8$s</bento-base-carousel>',
			esc_attr( $width ),
			esc_attr( $height ),
			esc_attr( $style ),
			esc_attr__( 'Next Slide', 'blocks-bento-variations' ),
			esc_attr__( 'Previous Slide', 'blocks-bento-variations' ),
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

		if (
			is_admin() || // Don't enqueue Bento assets in the editor.
			\is_amp_request() // Assets on AMP are handled by the AMP plugin.
		) {
			return;
		}

		$src                        = sprintf( 'https://cdn.ampproject.org/v0/bento-base-carousel-%s.js', $this->bento_base_carousel_version );
		$bento_base_carousel_script = wp_scripts()->query( self::BENTO_BASE_CAROUSE_HANDLE );

		if ( $bento_base_carousel_script ) {
			// Make sure that 1.0 (Bento) is used instead of 0.1 (latest).
			$bento_base_carousel_script->src = $src;
		} else {
			wp_register_script( self::BENTO_RUNTIME_HANDLE, 'https://cdn.ampproject.org/bento.js', [], 'v0', false );
			wp_register_script( self::BENTO_BASE_CAROUSE_HANDLE, $src, [ self::BENTO_RUNTIME_HANDLE ], $this->bento_base_carousel_version, false );
		}

		wp_enqueue_script( self::BENTO_BASE_CAROUSE_HANDLE );

		$src                       = sprintf( 'https://cdn.ampproject.org/v0/bento-base-carousel-%s.css', $this->bento_base_carousel_version );
		$bento_base_carousel_style = wp_styles()->query( self::BENTO_BASE_CAROUSE_HANDLE );

		if ( $bento_base_carousel_style ) {
			// Make sure that 1.0 (Bento) is used instead of 0.1 (latest).
			$bento_base_carousel_style->src = $src;
		} else {
			wp_register_style( self::BENTO_BASE_CAROUSE_HANDLE, $src, [], $this->bento_base_carousel_version, false );
		}

		wp_enqueue_style( self::BENTO_BASE_CAROUSE_HANDLE );

	}

}
