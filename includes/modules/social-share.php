<?php declare(strict_types=1);

namespace SIW\Modules;

use SIW\Core\Template;
use SIW\Data\Social_Network;

/**
 * Voegt share-links toe voor social netwerken
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Social_Share {

	/** Post type van huidige post */
	protected string $post_type;

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles'] );
		add_action( 'generate_after_content', [ $self, 'render' ] );
	}

	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		wp_register_style( 'siw-social-share', SIW_ASSETS_URL . 'css/modules/siw-social-share.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-social-share' );
	}

	/** Toont de share links */
	public function render() {

		if ( ! is_single() || ! $this->is_supported_post_type() ) {
			return;
		}

		$networks = \siw_get_social_networks( 'share' );
		$title = get_the_title();
		$url = get_permalink();

		$social_networks = array_map(
			fn( Social_Network $network ) : array => [
				'share_url' => $network->generate_share_link( $url, $title ),
				'label'     => sprintf( esc_attr__( 'Delen via %s', 'siw' ), $network->get_name() ),
				'color'     => $network->get_color(),
				'name'      => $network->get_name(),
				'url'       => $url,
				'icon'      => [
					'size'             => 2,
					'icon_class'       => $network->get_icon_class(),
					'has_background'   => false,
				]
			],
			$networks
		);

		Template::render_template(
			'modules/social-share',
			[
				'title'           => $this->get_title(),
				'social_networks' => array_values( $social_networks ),
			]
		);
	}

	/** Genereert de titel */
	protected function get_title() {
		return $this->get_post_type_settings()[ $this->post_type ] ?? '';
	}

	/** Geeft aan of dit een ondersteunde post type is */
	protected function is_supported_post_type() : bool {
		$this->post_type = get_post_type();
		return in_array( $this->post_type, array_keys( $this->get_post_type_settings() ) );
	}

	/** Haal instelling van post type op */
	protected function get_post_type_settings() : array {
		return apply_filters( 'siw_social_share_post_types', [] );
	}

}
