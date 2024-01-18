<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Data\Social_Network;
use SIW\Data\Social_Network_Context;
use SIW\Helpers\Template;

class Social_Links extends Element {

	protected Social_Network_Context $context;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {

		$social_networks = \siw_get_social_networks( $this->context );

		foreach ( $social_networks as $network ) {
			$networks[] = [
				'url'   => ( Social_Network_Context::SHARE === $this->context ) ? $this->generate_share_url( $network ) : $network->get_follow_url(),
				'name'  => $network->get_name(),
				'label' => sprintf(
					// translators: %s is de naam van een sociaal netwerk
					( Social_Network_Context::SHARE === $this->context ) ? __( 'Delen via %s', 'siw' ) : __( 'Volg ons op %s', 'siw' ),
					$network->get_name()
				),
				'color' => $network->get_color(),
				'icon'  => [
					'class' => $network->get_icon_class(),
					'size'  => 4,
				],
			];
		}

		return [
			'social_networks' => $networks,
		];
	}

	protected function determine_content_type(): string {
		$content_type = get_post_type();
		if ( str_starts_with( $content_type, 'siw_' ) ) {
			$content_type = substr( $content_type, 4 );
		}
		return $content_type;
	}

	protected function generate_share_url( Social_Network $network ) {
		return Template::create()
		->set_template( $network->get_share_url_template() )
		->set_context(
			[
				'url'   => rawurlencode( get_permalink() ),
				'title' => rawurlencode( html_entity_decode( get_the_title() ) ),
			]
		)
		->parse_template();
	}

	public function set_context( Social_Network_Context $context ): self {
		$this->context = $context;
		return $this;
	}

	public function enqueue_styles() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/elements/social-links.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/elements/social-links.css' );
		wp_enqueue_style( self::get_assets_handle() );
	}
}
