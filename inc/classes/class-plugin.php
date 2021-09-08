<?php
/**
 * Plugin manifest class.
 *
 * @package blocks-bento-variations
 */

namespace Blocks_Bento_Variations\Features\Inc;

use \Blocks_Bento_Variations\Features\Inc\Traits\Singleton;

/**
 * Class Plugin
 */
class Plugin {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {

		// Load plugin classes.
		Assets::get_instance();
		Blocks::get_instance();

	}

}
