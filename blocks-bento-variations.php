<?php
/**
 * Plugin Name: Blocks Bento Variations
 * Description: Adds new block variations of existing blocks, which will utilize the Bento components.
 * Plugin URI:  https://rtcamp.com
 * Author:      rtCamp
 * Author URI:  https://rtcamp.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version:     1.0
 * Text Domain: blocks-bento-variations
 *
 * @package blocks-bento-variations
 */

define( 'BLOCKS_BENTO_VARIATIONS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'BLOCKS_BENTO_VARIATIONS_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'BLOCKS_BENTO_VARIATIONS_BUILD_URI', BLOCKS_BENTO_VARIATIONS_PATH . '/assets/build' );

require_once BLOCKS_BENTO_VARIATIONS_PATH . '/inc/helpers/autoloader.php';

/**
 * To load plugin manifest class.
 *
 * @return void
 */
function blocks_bento_variations_features_plugin_loader() {
	\Blocks_Bento_Variations\Features\Inc\Plugin::get_instance();
}

blocks_bento_variations_features_plugin_loader();
