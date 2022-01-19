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
	 * @param array  $icons Which icons are enabled.
	 *
	 * @param string $share_button_style Button style.
	 *
	 * @return string
	 */
	protected function bento_social_icon( $icons, $share_button_style ) {
		$icons_meta = [
			'twitter'   => 'Share on Twitter',
			'facebook'  => 'Share on Facebook',
			'pinterest' => 'Share on Pinterest',
			'linkedin'  => 'Share on Linkedin',
			'email'     => 'Share via email',
			'reddit'    => 'Share via Reddit',
		];

		global $post;

		if ( has_post_thumbnail() ) {
			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			$thumbnail    = $thumbnail_id ? current( wp_get_attachment_image_src( $thumbnail_id, 'large', true ) ) : '';
		} else {
			$thumbnail = null;
		}
		$share_urls = [
			'twitter'   => 'http://twitter.com/share?text=' . get_the_title() . '&url=' . get_the_permalink(),
			'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink() . '&title=' . get_the_title(),
			'linkedin'  => 'https://www.linkedin.com/shareArticle?mini=true&url=' . get_the_permalink() . '&title=' . get_the_title(),
			'pinterest' => 'https://pinterest.com/pin/create/button/?&url=' . get_the_permalink() . '&description=' . get_the_title() . '&media=' . esc_url( $thumbnail ),
			'email'     => 'mailto:?subject=' . get_the_title() . '&body=' . get_the_title() . '&mdash;' . get_the_permalink(),
			'reddit'    => 'https://www.reddit.com/submit?url=' . get_the_permalink(),
		];

		$bento_icons = '';
		foreach ( $icons as $icon_name => $is_enabled ) {
			if ( isset( $is_enabled ) && $is_enabled ) {
				// Currently bento-social-share component only provides icons. So for text only style rendering anchor like the core Atomic Sharing block.
				if ( isset( $share_button_style ) && 'ab-share-text-only' === $share_button_style ) {
					$bento_icons .= sprintf(
						'<li>
							<a
								href="%1$s"
								class="ab-share-%3$s"
								title="%2$s">
								<span class="ab-social-text">%2$s</span>
							</a>
					</li>',
						$share_urls[ $icon_name ],
						$icons_meta[ $icon_name ],
						$icon_name
					);
				} else {
					$bento_icons .= sprintf(
						'<li class="bento-social-icon-wrapper ab-share-%1$s">
							<bento-social-share type="%1$s"  aria-label="%2$s" class="bento-social-icon" %3$s  %4$s layout="responsive" height="1" width="1"></bento-social-share>
							<span class="ab-social-text">%2$s</span>
						</li>',
						esc_attr( $icon_name ),
						esc_attr( $icons_meta[ $icon_name ] ),
						'facebook' === $icon_name ? "data-param-app_id='none'" : null,
						'reddit' === $icon_name ? "data-share-endpoint=${share_urls[ 'reddit' ]}" : null
					);
				}
			}
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
			'reddit'    => $attributes['reddit'],
		];

		return sprintf(
			'<div class="wp-block-atomic-blocks-ab-sharing ab-block-sharing ab-block-sharing-bento %2$s %3$s %4$s %5$s %6$s">
			<ul class="ab-share-list bento-social-share-group">%1$s</ul>
		</div>',
			$this->bento_social_icon( $icons, $attributes['shareButtonStyle'] ),
			isset( $attributes['shareButtonStyle'] ) ? $attributes['shareButtonStyle'] : 'ab-share-icon-text',
			isset( $attributes['shareButtonShape'] ) ? $attributes['shareButtonShape'] : 'ab-share-shape-circular',
			isset( $attributes['shareButtonSize'] ) ? $attributes['shareButtonSize'] : 'ab-share-size-medium',
			isset( $attributes['shareButtonColor'] ) ? $attributes['shareButtonColor'] : 'ab-share-color-standard',
			isset( $attributes['shareAlignment'] ) ? 'ab-align-' . $attributes['shareAlignment'] : 'ab-align-left'
		);
	}

}
