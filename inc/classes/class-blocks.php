<?php
/**
 * Registers assets for all blocks, and additional global functionality for gutenberg blocks.
 *
 * @package blocks-bento-variations
 */

namespace Blocks_Bento_Variations\Features\Inc;

use Blocks_Bento_Variations\Features\Inc\Traits\Singleton;
use Blocks_Bento_Variations\Features\Inc\Blocks\Jetpack_Slideshow;

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
	}

}
