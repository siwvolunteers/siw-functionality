<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Data\Social_Network_Context;
use SIW\Elements\Social_Links;
use SIW\Helpers\Template;

/**
 * Voegt share-links toe voor social netwerken
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Social_Share extends Base {

	const ASSETS_HANDLE = 'siw-social-share';

	public const POST_TYPE_FEATURE = 'siw-social-share';

	/** Post type van huidige post */
	protected string $post_type;

	#[Add_Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/features/social-share.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/features/social-share.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
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
					'header'       => $this->get_title(),
					'social_links' => Social_Links::create()
						->set_context( Social_Network_Context::SHARE )
						->generate(),
				]
			)
			->render_template();
	}

	/** Genereert de titel */
	protected function get_title() {
		$supports = get_all_post_type_supports( $this->post_type );
		return $supports[ self::POST_TYPE_FEATURE ][0]['cta'] ?? __( 'Deel deze pagina', 'siw' );
	}

	/** Geeft aan of dit een ondersteunde post type is */
	protected function is_supported_post_type(): bool {
		$this->post_type = get_post_type();
		return post_type_supports( $this->post_type, self::POST_TYPE_FEATURE );
	}
}
