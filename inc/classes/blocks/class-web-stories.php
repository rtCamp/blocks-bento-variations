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

		foreach ( $nodes as $node ) {
			$node->setAttribute( 'mixed-length', 'true' );
			$node->setAttribute( 'style', 'height: 300px;' );
			$node->setAttribute( 'auto-advance', 'false' );
			$node->setAttribute( 'snap-true', 'center' );
			$node->setAttribute( 'loop', 'false' );
			$node->setAttribute( 'controls', 'never' );

			$classes_string = $node->getAttribute( 'class' );

			$classes_string = str_replace( 'web-stories-list__carousel', 'web-stories-list__carousel--bento', $classes_string );
			$node->setAttribute( 'class', $classes_string );

			$this->renameTag( $node, 'bento-base-carousel' );
		}

		$block_content = $dom->saveHTML();

		return $block_content;
	}


	protected function renameTag( \DOMElement $old_tag_name, $new_tag_name ) {

		$document = $old_tag_name->ownerDocument;

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

	protected function enqueue_block_assets() {

		if ( \is_amp_request() ) {
			return;
		}

		Assets::get_instance()->register_style( self::ASSETS_HANDLE, 'css/style-web-stories.css' );
		Assets::get_instance()->register_script( self::ASSETS_HANDLE, 'js/web-stories.js' );

		wp_enqueue_style( self::ASSETS_HANDLE );
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

		$src                     = sprintf( 'https://cdn.ampproject.org/v0/bento-base-carousel-%s.css', $this->bento_base_carousel_version );
		$amp_base_carousel_style = wp_styles()->query( self::BENTO_BASE_CAROUSEL_HANDLE );

		if ( $amp_base_carousel_style ) {
			// Make sure that 1.0 (Bento) is used instead of 0.1 (latest).
			$amp_base_carousel_style->src = $src;
		} else {
			wp_register_style( self::BENTO_BASE_CAROUSEL_HANDLE, $src, [], $this->bento_base_carousel_version, false );
		}

		wp_enqueue_style( self::BENTO_BASE_CAROUSEL_HANDLE );
	}
}
