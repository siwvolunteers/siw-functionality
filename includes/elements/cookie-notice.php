<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een cookie notice
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @todo omschrijving naar generieke notice class
 */
class Cookie_Notice extends Element {

	const SCRIPT_HANDLE = 'siw-cookie-notice';
	const STYLE_HANDLE = 'siw-cookie-notice';

	/** HTML id van cookie notice */
	const NOTICE_ID = 'siw-cookie-notification';

	/** HTML id van cookie knop */
	const BUTTON_ID = 'siw-cookie-consent';

	/** Cookienaam */
	const COOKIE_NAME = 'siw_cookie_consent';

	/** Levensduur van cookie in dagen */
	const COOKIE_LIFESPAN = 365;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'cookie-notice';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'notice_id' => self::NOTICE_ID,
			'button_id' => self::BUTTON_ID,
			'i18n'      => [
				'cookie_text' => __( 'We gebruiken cookies om ervoor te zorgen dat onze website optimaal werkt en om het gebruik van onze website te analyseren. Door gebruik te blijven maken van onze website, ga je hiermee akkoord.', 'siw-cookie-notice' ),
				'button_text' => __( 'Ik ga akkoord', 'siw-cookie-notice' ),
			],
		];
	}

	/** {@inheritDoc} */
	protected function enqueue_scripts() {
		wp_register_script( self::SCRIPT_HANDLE, SIW_ASSETS_URL . 'js/elements/siw-cookie-notice.js', [ 'js-cookie' ], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			self::SCRIPT_HANDLE,
			'siw_cookie_notice',
			[
				'cookie' => [
					'name'    => self::COOKIE_NAME,
					'expires' => self::COOKIE_LIFESPAN,
					'value'   => 1,
				],
				'notice_id'   => self::NOTICE_ID,
				'button_id'   => self::BUTTON_ID,
			]
		);
		wp_enqueue_script( self::SCRIPT_HANDLE );
	}

	/** {@inheritDoc} */
	protected function enqueue_styles() {
		wp_register_style( self::STYLE_HANDLE, SIW_ASSETS_URL . 'css/elements/siw-cookie-notice.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::STYLE_HANDLE );
	}
}
