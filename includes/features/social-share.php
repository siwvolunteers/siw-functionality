<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Data\Post_Type_Support;
use SIW\Data\Social_Network_Context;
use SIW\Elements\Social_Links;
use SIW\Helpers\Template;
use SIW\Traits\Assets_Handle;

/**
 * Voegt share-links toe voor social netwerken
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Social_Share extends Base {

	use Assets_Handle;

	#[Add_Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/features/social-share.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/features/social-share.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}

	#[Add_Action( 'generate_after_content' )]
	/** Toont de share links */
	public function render() {

		if ( ! is_single() || ! $this->is_supported_post_type() ) {
			return;
		}

		Template::create()
			->set_template( 'features/social-share' )
			->set_context(
				[
					'header'       => __( 'Delen', 'siw' ),
					'social_links' => Social_Links::create()
						->set_context( Social_Network_Context::SHARE )
						->generate(),
				]
			)
			->render_template();
	}

	/** Geeft aan of dit een ondersteunde post type is */
	protected function is_supported_post_type(): bool {
		return post_type_supports( get_post_type(), Post_Type_Support::SOCIAL_SHARE->value );
	}
}
