<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Assets\JS_Cookie;
use SIW\I18n;
use SIW\Util\Links;

/**
 * Class om een cookie notice
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @todo omschrijving naar generieke notice class
 */
class Cookie_Notice extends Element {

	/** Handle voor assets */
	const ASSETS_HANDLE = 'siw-cookie-notice';

	/** Cookienaam */
	const COOKIE_NAME = 'siw_cookie_choices';

	const EVENT_NAME = 'siw_cookie_choices';

	/** Levensduur van cookie in dagen */
	const COOKIE_LIFESPAN = 365;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'cookie-notice';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {

		return [
			'i18n' => [
				'cookie_text'      =>
					__( 'Wij gebruiken cookies om je gebruikservaring te optimaliseren, het webverkeer te analyseren en voor persoonlijke advertentiedoeleinden.', 'siw' ) . SPACE .
					// translators: %s is de link naar het privacybeleid
					sprintf( __( 'Lees meer over hoe wij cookies gebruiken en hoe je ze kunt beheren in ons %s.', 'siw' ), Links::generate_link( get_privacy_policy_url(), __( 'privacybeleid', 'siw' ) ) ),
				'accept_selection' => __( 'Selectie toestaan', 'siw' ),
				'accept_all'       => __( 'Alles toestaan', 'siw' ),
				'analytical'       => __( 'Analytisch', 'siw' ),
				'marketing'        => __( 'Marketing', 'siw' ),
			],
		];
	}

	/** {@inheritDoc} */
	public function enqueue_scripts() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/elements/cookie-notice.js', [ JS_Cookie::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			self::ASSETS_HANDLE,
			'siw_cookie_notice',
			[
				'cookie'     => [
					'name'    => self::COOKIE_NAME,
					'expires' => self::COOKIE_LIFESPAN,
				],
				'notice_id'  => $this->get_element_id(),
				'event_name' => self::EVENT_NAME,
			]
		);
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	/** {@inheritDoc} */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/elements/cookie-notice.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/elements/cookie-notice.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}
}
