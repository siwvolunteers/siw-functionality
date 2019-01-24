<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cookie notificatie
 * 
 * @package SIW\Modules
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Cookie_Notification {
	
	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles'] );
		add_action( 'wp_footer', [ $self, 'render'] );
	}

	/**
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_register_script( 'siw-cookie', SIW_ASSETS_URL . 'js/siw-cookie.js', [ 'jquery', 'js-cookie' ] , SIW_PLUGIN_VERSION );
		wp_enqueue_script( 'siw-cookie' );
	}

	/**
	 * @return void
	 */
	public function enqueue_styles() {
		wp_register_style( 'siw-cookie', SIW_ASSETS_URL . 'css/siw-cookie.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-cookie' );
	}

	/**
	 * Genereert de cookie notificatie
	 *
	 * @return void
	 */
	public function render() { ?>
		<div id="siw-cookie-notification">
			<div class="container">
				<div class="row">
					<div class="col-md-10 cookie-text"><?php
						esc_html_e( 'We gebruiken cookies om ervoor te zorgen dat onze website optimaal werkt en om het gebruik van onze website te analyseren.', 'siw' ); echo SPACE;
						esc_html_e( 'Door gebruik te blijven maken van onze website, ga je hiermee akkoord.', 'siw' ); ?>
					</div>
					<div class="col-md-2 cookie-button">
						<button id="siw-cookie-consent" class="button"><?php esc_html_e( 'Ik ga akkoord!', 'siw' ); ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
