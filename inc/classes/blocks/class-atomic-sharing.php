<?php
/**
 * 'Atomic block: Sharing' block related functionalities.
 *
 * @package  blocks-bento-variations
 */

namespace Blocks_Bento_Variations\Features\Inc\Blocks;

use Blocks_Bento_Variations\Features\Inc\Assets;
use Blocks_Bento_Variations\Features\Inc\Traits\Singleton;

/**
 * Class Atomic blocks Sharing.
 */
class Atomic_Sharing {

	use Singleton;

	/**
	 * Block's Name.
	 *
	 * @var string
	 */
	const NAME = 'atomic-blocks/ab-sharing';

	/*
	 * Bento component's asset handle.
	 */
	const BENTO_SOCIAL_SHARE_HANDLE = 'bento-social-share';

	/**
	 * Block assets' handle.
	 */
	const ASSETS_HANDLE = 'atomic-sharing-bento';

	/**
	 * Bento Runtime script's handle.
	 */
	const BENTO_RUNTIME_HANDLE = 'bento-runtime';

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {
		add_filter( 'render_block', [ $this, 'render_block' ], 10, 2 );
	}

	/**
	 *  Necessary modifications to inject Bento.
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

		$this->enqueue_block_assets();
		$is_amp = is_amp_request();

		return $this->atomic_blocks_bento_render_sharing( $block['attrs'] );
	}

	/**
	 * Enqueue required scripts & styles for the block.
	 * Enqueues Bento component compatibility assets conditionally.
	 *
	 * @return void
	 */
	protected function enqueue_block_assets() {

		Assets::get_instance()->register_style( self::ASSETS_HANDLE, 'css/style-atomic-sharing.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );

		if (
			is_admin() || // Don't enqueue Bento assets in the editor.
			\is_amp_request() // Assets on AMP are handled by the AMP plugin.
		) {
			return;
		}

		$bento_social_share_script_src = 'https://cdn.ampproject.org/v0/bento-social-share-1.0.js';
		$bento_social_share_script     = wp_scripts()->query( self::BENTO_SOCIAL_SHARE_HANDLE );

		if ( ! $bento_social_share_script ) {
			wp_register_script( self::BENTO_RUNTIME_HANDLE, 'https://cdn.ampproject.org/bento.js', [], 'v0', false );
			wp_register_script( self::BENTO_SOCIAL_SHARE_HANDLE, $bento_social_share_script_src, [ self::BENTO_RUNTIME_HANDLE ], 'v0', false );
		}

		wp_enqueue_script( self::BENTO_SOCIAL_SHARE_HANDLE );

		$bento_social_share_style_src = 'https://cdn.ampproject.org/v0/bento-social-share-1.0.css';
		$bento_social_share_style     = wp_styles()->query( self::BENTO_SOCIAL_SHARE_HANDLE );

		if ( ! $bento_social_share_style ) {
			wp_register_style( self::BENTO_SOCIAL_SHARE_HANDLE, $bento_social_share_style_src, [], 'v0', false );
		}

		wp_enqueue_style( self::BENTO_SOCIAL_SHARE_HANDLE );
	}

	/**
	 * Render the bento social share
	 *
	 * @param array $icons Which icons are enabled.
	 *
	 * @return string
	 */
	protected function bento_social_icon( $icons ) {
		$icons_meta  = [
			'twitter'   => 'Share on Twitter',
			'facebook'  => 'Share on Facebook',
			'pinterest' => 'Share on Pinterest',
			'linkedin'  => 'Share on Linkedin',
			'email'     => 'Share via email',
		];
		$bento_icons = '';
		foreach ( $icons as $icon_name => $is_enabled ) {
			ob_start();
			?>
			<li>
				<bento-social-share type='<?php echo esc_attr( $icon_name ); ?>' aria-label='<?php echo esc_attr( $icons_meta[ $icon_name ] ); ?>' class='ab-share-<?php echo esc_attr( $icon_name ); ?>'></bento-social-share>
				<span class="ab-social-text"><?php echo esc_attr( $icons_meta[ $icon_name ] ); ?></span>
			</li>
			<?php
			$bento_icons .= ob_get_clean();
		}
		return $bento_icons;
	}
	/**
	 * Render the sharing links
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string The block HTML.
	 */
	protected function atomic_blocks_bento_render_sharing( $attributes ) {
		$twitter  = ! isset( $attributes['twitter'] ) || $attributes['twitter'];
		$facebook = ! isset( $attributes['facebook'] ) || $attributes['facebook'];

		$icons = [
			'twitter'   => $twitter,
			'facebook'  => $facebook,
			'pinterest' => $attributes['pinterest'],
			'linkedin'  => $attributes['linkedin'],
			'email'     => $attributes['email'],
		];

		return sprintf(
			'<div class="wp-block-atomic-blocks-ab-sharing ab-block-sharing ab-block-sharing-bento %2$s %3$s %4$s %5$s %6$s">
			<ul class="ab-share-list bento-social-share-group">%1$s</ul>
		</div>',
			$this->bento_social_icon( $icons ),
			isset( $attributes['shareButtonStyle'] ) ? $attributes['shareButtonStyle'] : null,
			isset( $attributes['shareButtonShape'] ) ? $attributes['shareButtonShape'] : null,
			isset( $attributes['shareButtonSize'] ) ? $attributes['shareButtonSize'] : null,
			isset( $attributes['shareButtonColor'] ) ? $attributes['shareButtonColor'] : null,
			isset( $attributes['shareAlignment'] ) ? 'ab-align-' . $attributes['shareAlignment'] : null
		);
	}

}
