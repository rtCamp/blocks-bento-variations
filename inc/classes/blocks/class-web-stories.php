<?php
/**
 * 'Web Stories' block related functionalities.
 *
 * @package blocks-bento-variations
 */

namespace Blocks_Bento_Variations\Features\Inc\Blocks;

use Blocks_Bento_Variations\Features\Inc\Assets;
use Blocks_Bento_Variations\Features\Inc\Traits\Singleton;

/**
 * Class Web_Stories
 */
class Web_Stories {

	use Singleton;

	/**
	 * Block's name.
	 *
	 * @var string
	 */
	const NAME = 'web-stories/embed';

	/**
	 * Bento component's asset handle.
	 */
	const BENTO_BASE_CAROUSEL_HANDLE = 'bento-base-carousel';

	/**
	 * Bento light-box's asset handle.
	 */
	const BENTO_BASE_LIGHTBOX_HANDLE = 'bento-light-box';

	/**
	 * Bento Runtime script's handle.
	 */
	const BENTO_RUNTIME_HANDLE = 'bento-runtime';

	/**
	 * Block assets' handle.
	 */
	const ASSETS_HANDLE = 'web-stories-bento';

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
	 * Modifies the 'Web Stories' block's markup.
	 *
	 * @param string $block_content Block's HTML markup in string format.
	 * @param array  $block         An array containing block information.
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

		$dom = new \DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( mb_convert_encoding( $block_content, 'HTML-ENTITIES', 'UTF-8' ) );

		$finder      = new \DomXPath( $dom );
		$nodes       = $finder->query( "//*[contains(concat(' ', normalize-space(@class), ' '), 'web-stories-list__carousel')]" );
		$arrow_nodes = $finder->query( "//*[contains(concat(' ', normalize-space(@class), ' '), 'glider')]" );

		if ( \is_amp_request() ) {
			$base_carousel = $dom->getElementsByTagName( 'amp-carousel' );
			$nodes         = $base_carousel;
		}

		foreach ( $arrow_nodes as $node ) {
			$arrow_node_classes = $node->getAttribute( 'class' );

			if ( strpos( $arrow_node_classes, 'glider-next' ) !== false ) {
				$arrow_node_classes .= ' bento-next';
			}

			if ( strpos( $arrow_node_classes, 'glider-prev' ) !== false ) {
				$arrow_node_classes .= ' bento-prev';
			}

			$node->setAttribute( 'class', $arrow_node_classes );
		}

		$carousel_attributes = [
			'mixed-length' => 'true',
			'style'        => 'height: 300px;',
			'auto-advance' => 'false',
			'loop'         => 'false',
		];

		if ( ! \is_amp_request() ) {
			// These two attributes are only supported on the AMP version page/block.
			$carousel_attributes['controls']  = 'never';
			$carousel_attributes['snap-true'] = 'center';
		}

		foreach ( $nodes as $node ) {
			foreach ( $carousel_attributes as $attribute => $attribute_value ) {
				$node->setAttribute( $attribute, $attribute_value );
			}

			if ( \is_amp_request() ) {
				$node->removeAttribute( 'type' );
			}

			$classes_string = $node->getAttribute( 'class' );

			$classes_string = str_replace( 'web-stories-list__carousel', 'web-stories-list__carousel--bento', $classes_string );
			$node->setAttribute( 'class', $classes_string );

			$modified_node = $this->rename_tag( $node, 'bento-base-carousel' );

			$posters = $finder->query( "//*[contains(concat(' ', normalize-space(@class), ' '), 'web-stories-list__story-poster')]" );

			$stories_meta = [];

			foreach ( $posters as $poster ) {

				foreach ( $poster->childNodes as $poster_anchor ) {

					if ( 'a' === $poster_anchor->nodeName ) {

						foreach ( $poster_anchor->childNodes as $poster_img ) {

							if ( 'img' === $poster_img->nodeName ) {
								$stories_meta[] = [
									'href' => $poster_anchor->getAttribute( 'href' ),
									'name' => $poster_img->getAttribute( 'alt' ),
								];
							}
						}
					}
				}
			}
			$this->add_light_box( $modified_node, $stories_meta );
		}

		$block_content = $dom->saveHTML();

		return $block_content;
	}

	/**
	 * Wrap Carousel with Bento light-box.
	 *
	 * @param \DOMElement $original_node  DomElement to be wrapped with light-box.
	 *
	 * @param array       $stories_meta  Contains href & alt attributes of each story poster.
	 *
	 * @return void
	 */
	protected function add_light_box( \DOMElement $original_node, $stories_meta ) {

		$document = $original_node->ownerDocument;

		$bento_light_box = $document->createElement( 'bento-lightbox' );
		$bento_light_box->setAttribute( 'class', 'bento-stories-lightbox' );
		$bento_light_box->setAttribute( 'id', $original_node->getAttribute( 'data-id' ) );

		$amp_story_container = $document->createElement( 'div' );
		$amp_story_container->setAttribute( 'id', 'web-stories-list__lightbox' );

		$bento_amp_story = $document->createElement( 'amp-story-player' );
		$bento_amp_story->setAttribute( 'id', 'lightbox-story-container' );
		$bento_amp_story->setAttribute( 'width', '3.6' );
		$bento_amp_story->setAttribute( 'height', '6' );
		$bento_amp_story->setAttribute( 'layout', 'responsive' );

		$story_script_data = [
			'controls' => [
				[
					'name'     => 'close',
					'position' => 'start',
				],
				[
					'name' => 'skip-next',
				],
			],
			'behavior' => [
				'autoplay' => false,
			],
		];

		if ( ! \is_amp_request() ) {
			$amp_story_script = $document->createElement( 'script', wp_json_encode( $story_script_data ) );
			$amp_story_script->setAttribute( 'type', 'application/json' );
			$bento_amp_story->appendChild( $amp_story_script );
		}

		foreach ( $stories_meta as $story_meta ) {
			$amp_story = $document->createElement( 'a', $story_meta['name'] );
			$amp_story->setAttribute( 'href', $story_meta['href'] );
			$bento_amp_story->appendChild( $amp_story );
		};

		$amp_story_container->appendChild( $bento_amp_story );
		$bento_light_box->appendChild( $amp_story_container );
		$original_node->parentNode->insertBefore( $bento_light_box, $original_node );

	}

	/**
	 * Rename carousel element to bento-carousel.
	 *
	 * @param \DOMElement $old_tag_name DomElement which's Tag should be renamed.
	 * @param string      $new_tag_name Tag name to be replaced with.
	 *
	 * @return mixed
	 */
	protected function rename_tag( \DOMElement $old_tag_name, $new_tag_name ) {

		$document        = $old_tag_name->ownerDocument;
		$new_tag_element = $document->createElement( $new_tag_name );

		$old_tag_name->parentNode->replaceChild( $new_tag_element, $old_tag_name );

		foreach ( $old_tag_name->attributes as $attribute ) {
			$new_tag_element->setAttribute( $attribute->name, $attribute->value );
		}

		foreach ( iterator_to_array( $old_tag_name->childNodes ) as $child ) {
			$new_tag_element->appendChild( $old_tag_name->removeChild( $child ) );
		}

		return $new_tag_element;
	}


	/**
	 * Enqueue required scripts & styles for the block.
	 * Enqueues Bento component compatibility assets conditionally.
	 *
	 * @return void
	 */
	protected function enqueue_block_assets() {

		Assets::get_instance()->register_style( self::ASSETS_HANDLE, 'css/style-web-stories.css' );

		wp_enqueue_style( self::ASSETS_HANDLE );

		if ( \is_amp_request() ) {
			return;
		}

		Assets::get_instance()->register_script( self::ASSETS_HANDLE, 'js/web-stories.js' );

		wp_enqueue_script( self::ASSETS_HANDLE );

		$src                      = sprintf( 'https://cdn.ampproject.org/v0/bento-base-carousel-%s.js', $this->bento_base_carousel_version );
		$amp_base_carousel_script = wp_scripts()->query( self::BENTO_BASE_CAROUSEL_HANDLE );

		if ( $amp_base_carousel_script ) {
			// Make sure that 1.0 (Bento) is used instead of 0.1 (latest).
			$amp_base_carousel_script->src = $src;
		} else {
			wp_register_script( self::BENTO_RUNTIME_HANDLE, 'https://cdn.ampproject.org/bento.js', [], 'v0', false );
			wp_register_script( self::BENTO_BASE_CAROUSEL_HANDLE, $src, [ self::BENTO_RUNTIME_HANDLE ], $this->bento_base_carousel_version, false );
		}

		wp_enqueue_script( self::BENTO_BASE_CAROUSEL_HANDLE );

		wp_register_script( self::BENTO_BASE_LIGHTBOX_HANDLE, 'https://cdn.ampproject.org/v0/bento-lightbox-1.0.js', [ self::BENTO_RUNTIME_HANDLE ], $this->bento_base_carousel_version, false );
		wp_enqueue_script( self::BENTO_BASE_LIGHTBOX_HANDLE );

		$src                     = sprintf( 'https://cdn.ampproject.org/v0/bento-base-carousel-%s.css', $this->bento_base_carousel_version );
		$amp_base_carousel_style = wp_styles()->query( self::BENTO_BASE_CAROUSEL_HANDLE );

		if ( $amp_base_carousel_style ) {
			// Make sure that 1.0 (Bento) is used instead of 0.1 (latest).
			$amp_base_carousel_style->src = $src;
		} else {
			wp_register_style( self::BENTO_BASE_CAROUSEL_HANDLE, $src, [], $this->bento_base_carousel_version, false );
		}

		wp_register_style( self::BENTO_BASE_LIGHTBOX_HANDLE, 'https://cdn.ampproject.org/v0/bento-lightbox-1.0.css', [], $this->bento_base_carousel_version, false );
		wp_enqueue_style( self::BENTO_BASE_CAROUSEL_HANDLE );

		wp_enqueue_style( self::BENTO_BASE_LIGHTBOX_HANDLE );

	}
}
