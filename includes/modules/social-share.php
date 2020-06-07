<?php

namespace SIW\Modules;

use SIW\Util\Links;

/**
 * Voegt share-links toe voor social netwerken
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Social_Share {

	/**
	 * Post type van huidige post
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles'] );
		add_action( 'generate_after_content', [ $self, 'render' ] );
	}

	/**
	 * Voegt stylesheet toe
	 */
	public function enqueue_styles() {
		wp_register_style( 'siw-social-share', SIW_ASSETS_URL . 'css/modules/siw-social-share.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-social-share' );
	}

	/**
	 * Toont de share links
	 */
	public function render() {

		if ( ! is_single() || ! $this->is_supported_post_type() ) {
			return;
		}
		?>
		<hr>
		<div class="siw-social">
			<div class="title"><?php echo esc_html( $this->get_title() ) ?> </div>
			<?php
				$networks = \siw_get_social_networks('share');
				$title = get_the_title();
				$url = get_permalink();
				
				foreach ( $networks as $network ) {
					echo Links::generate_icon_link(
						$network->generate_share_link( $url, $title ),
						[
							'class' => $network->get_icon_class(),
						],
						[
							'class'               => $network->get_slug(),
							'aria-label'          => sprintf( esc_attr__( 'Delen via %s', 'siw' ), $network->get_name() ),
							'data-balloon-pos'    => 'down',
							'style'               => '--hover-color: ' . $network->get_color(),
							'target'              => '_blank',
							'data-ga-track'       => 1,
							'data-ga-type'        => 'social',
							'data-ga-category'    => $network->get_name(),
							'data-ga-action'      => 'Delen',
							'data-ga-label'       => $url,
						]
					);
				}
			?>
		</div><?php
	}

	/**
	 * Genereert de titel
	 *
	 * @return string
	 */
	protected function get_title() {
		return $this->get_post_type_settings()[ $this->post_type ] ?? '';
	}

	/**
	 * Geeft aan of dit een ondersteunde post type is
	 *
	 * @return bool
	 */
	protected function is_supported_post_type() : bool {
		$this->post_type = get_post_type();
		return in_array( $this->post_type, array_keys( $this->get_post_type_settings() ) );
	}

	/**
	 * Haal instelling van post type op
	 *
	 * @return array
	 */
	protected function get_post_type_settings() : array {
		$settings = [
			'product' => __( 'Deel dit project', 'siw' ), //TODO: verplaatsen naar Compat/WooCommerce
		];
		return apply_filters( 'siw_social_share_post_types', $settings );
	}

}
