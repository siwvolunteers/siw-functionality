<?php

namespace SIW\Modules;

use SIW\HTML;

/**
 * Voegt share-links toe voor social netwerken
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Social_Share {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles'] );
		add_action( 'siw_vacature_footer', [ $self, 'render' ] );
		add_action( 'siw_agenda_footer', [ $self, 'render' ] );
		add_action( 'siw_tm_country_footer', [ $self, 'render' ] );
		add_action( 'siw_evs_project_footer', [ $self, 'render' ] );
		add_action( 'woocommerce_after_single_product', [ $self, 'render' ] );
		add_action( 'kadence_single_post_after', [ $self, 'render' ] );
	}

	/**
	 * Voegt stylesheet toe
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		wp_register_style( 'siw-social-share', SIW_ASSETS_URL . 'css/modules/siw-social-share.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-social-share' );
	}

	/**
	 * Toont de share links
	 */
	public function render() {
		if ( $this->needs_hr() ) {
			echo '<hr>';
		}?>
		<div class="siw-social">
			<div class="title"><?= esc_html( $this->get_title() ) ?> </div>
			<?php
				$networks = \siw_get_social_networks('share');
				$title = get_the_title();
				$url = get_permalink();
				
				foreach ( $networks as $network ) {
					echo HTML::generate_link(
						$network->generate_share_link( $url, $title ),
						'&shy;',
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
						],
						[
							'class' => $network->get_icon_class(),
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
		$post_type = get_post_type();

		switch( $post_type ) {
			case 'product':
				$title = __( 'Deel dit project', 'siw' );
				break;
			case 'vacatures':
				$title = __( 'Deel deze vacature', 'siw' );
				break;
			case 'agenda':
				$title = __( 'Deel dit evenement', 'siw' );
				break;
			case 'wpm-testimonial':
				$title = __( 'Deel dit ervaringsverhaal', 'siw' );
				break;
			case 'siw_tm_country':
				$title = __( 'Deel deze landenpagina', 'siw' );
				break;
			default:
				$title = __( 'Deel deze pagina', 'siw' );
		}
		return $title;
	}

	/**
	 * Bepaalt of er een `<hr>` nodig is
	 *
	 * @return bool
	 */
	protected function needs_hr() {
		$post_type = get_post_type();
		switch( $post_type ) {
			case 'product':
				$needs_hr = true;
				break;
			default:
				$needs_hr = false;
		}
		return $needs_hr;
	}
}
