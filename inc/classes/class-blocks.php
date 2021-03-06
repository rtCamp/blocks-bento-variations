<?php
/**
 * Registers assets for all blocks, and additional global functionality for gutenberg blocks.
 *
 * @package blocks-bento-variations
 */

namespace Blocks_Bento_Variations\Features\Inc;

use Blocks_Bento_Variations\Features\Inc\Traits\Singleton;
use Blocks_Bento_Variations\Features\Inc\Blocks\Jetpack_Slideshow;
use Blocks_Bento_Variations\Features\Inc\Blocks\Web_Stories;
use Blocks_Bento_Variations\Features\Inc\Blocks\CoBlocks_Accordion;
use Blocks_Bento_Variations\Features\Inc\Blocks\Atomic_Sharing;

/**
 * Class Blocks
 */
class Blocks {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		Jetpack_Slideshow::get_instance();
		Web_Stories::get_instance();
		CoBlocks_Accordion::get_instance();
		Atomic_Sharing::get_instance();
	}

}
