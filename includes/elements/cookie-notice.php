<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een cookie notice
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @todo omschrijving naar generieke notice class
 */
class Cookie_Notice extends Element {

	// Asset handles
	const SCRIPT_HANDLE = 'siw-cookie-notice';
	const STYLE_HANDLE = 'siw-cookie-notice';

	/** Cookienaam */
	const COOKIE_NAME = 'siw_cookie_choices';

	/** Levensduur van cookie in dagen */
	const COOKIE_LIFESPAN = 365;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'cookie-notice';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'i18n'      => [
				'cookie_text' => __( 'We gebruiken cookies om ervoor te zorgen dat onze website optimaal werkt en om het gebruik van onze website te analyseren. Door gebruik te blijven maken van onze website, ga je hiermee akkoord.', 'siw-cookie-notice' ),
				'i_agree'     => __( 'Ik ga akkoord', 'siw-cookie-notice' ),
				'analytical'  => __( 'Analytisch', 'siw' ),
				'marketing'   => __( 'Marketing', 'siw' ),
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
				],
				'notice_id'   => $this->get_element_id(),
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
