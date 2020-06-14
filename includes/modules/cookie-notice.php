<?php

namespace SIW\Modules;

/**
 * Cookie notice
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Cookie_Notice {
	
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
	 * Voegt scripts toe
	 */
	public function enqueue_scripts() {
		wp_register_script( 'siw-cookie-notice', SIW_ASSETS_URL . 'js/modules/siw-cookie-notice.js', [ 'js-cookie' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-cookie-notice' );
	}

	/**
	 * Voegt styles toe
	 */
	public function enqueue_styles() {
		wp_register_style( 'siw-cookie-notice', SIW_ASSETS_URL . 'css/modules/siw-cookie-notice.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-cookie-notice' );
	}

	/**
	 * Toont de cookie notificatie
	 */
	public function render() { ?>
		<div id="siw-cookie-notification" hidden>
			<div class="grid-container">
				<div class="grid-80 cookie-text">
					<?php
						esc_html_e( 'We gebruiken cookies om ervoor te zorgen dat onze website optimaal werkt en om het gebruik van onze website te analyseren.', 'siw' ); echo SPACE;
						esc_html_e( 'Door gebruik te blijven maken van onze website, ga je hiermee akkoord.', 'siw' ); ?>
				</div>
				<div class="grid-20 cookie-button">
					<button id="siw-cookie-consent" type="button" class="button ghost"><?php esc_html_e( 'Ik ga akkoord', 'siw' ); ?></button>
				</div>
			</div>
		</div>
		<?php
	}
}
