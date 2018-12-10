<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Voegt share-links toe voor social netwerken
 *
 * @package     SIW\Social-Share
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 *
 * @uses        siw_get_social_networks();
 */
class SIW_Social_Share {

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles'] );
		add_action( 'kadence_single_portfolio_after', [ $self, 'render' ] );
		add_action( 'siw_vacature_footer', [ $self, 'render' ] );
		add_action( 'siw_agenda_footer', [ $self, 'render' ] );
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
		wp_register_style( 'siw-social-share', SIW_ASSETS_URL . 'css/siw-social-share.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-social-share' );
	}

	/**
	 * Toont de share links
	 *
	 * @return void
	 * 
	 * @todo loop over social networks
	 * @todo generate_link gebruiken
	 */
	public function render() {
		$share_links = $this->get_share_links();

		if ( $this->needs_hr() ) {
			echo '<hr>';
		}?>
		<div class="siw-social">
			<div class="title"><?= esc_html( $this->get_title() ) ?> </div>
			<a class="facebook" data-toggle="tooltip" data-placement="bottom" data-original-title="Facebook" href="<?= esc_url( $share_links['facebook'] );?>" target="_blank"><i class="kt-icon-facebook2"></i></a>
			<a class="twitter" data-toggle="tooltip" data-placement="bottom" data-original-title="Twitter" href="<?= esc_url( $share_links['twitter'] );?>" target="_blank"><i class="kt-icon-twitter2"></i></a>
			<a class="linkedin" data-toggle="tooltip" data-placement="bottom" data-original-title="LinkedIn" href="<?= esc_url( $share_links['linkedin'] );?>" target="_blank"><i class="kt-icon-linkedin2"></i></a>
		</div><?php


	}

	/**
	 * Genereert de titel
	 *
	 * @return string
	 */
	protected function get_title() {
		$post_type = get_post_type();
		if ( 'portfolio' == $post_type || 'product' == $post_type || 'evs_project' == $post_type ) {
			$title = __( 'Deel dit project', 'siw' );
		}
		elseif ( 'vacatures' == $post_type ) {
			$title = __( 'Deel deze vacature', 'siw' );
		}
		elseif ( 'agenda' == $post_type ) {
			$title = __( 'Deel dit evenement', 'siw' );
		}
		elseif ( 'wpm-testimonial' == $post_type ) {
			$title = __( 'Deel dit ervaringsverhaal', 'siw' );
		}
		else {
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
		if ( 'portfolio' == $post_type || 'product' == $post_type || 'evs_project' == $post_type ) {
			return true;
		}
		return false;
	}

	/**
	 * Genereert de link voor alle netwerken
	 *
	 * @return array
	 */
	protected function get_share_links() {
		$title = get_the_title();
		$url = get_permalink();
		$networks = siw_get_social_networks('share');
		$links = [];
		foreach ( $networks as $network ) {
			$links[ $network->get_slug() ] = $network->generate_share_link( $url, $title );
		}
		return $links;
	}
}