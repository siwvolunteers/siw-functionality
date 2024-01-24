<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Special_Page;
use SIW\External_Assets\Cookie_Consent as Cookie_Consent_Asset;
use SIW\Traits\Class_Assets;
use SIW\Util\Links;

class Cookie_Consent extends Base {

	use Class_Assets;

	private const COOKIE_NAME = 'siw_cookie_consent';
	private const COOKIE_LIFESPAN = 365;
	private const COOKIE_REVISION = 0;

	public const NECESSARY = 'necessary';
	public const ANALYTICAL = 'analytical';
	public const MARKETING = 'marketing';

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_styles() {
		wp_enqueue_style( Cookie_Consent_Asset::get_asset_handle() );
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_scripts() {
		wp_register_script(
			self::get_asset_handle(),
			self::get_script_asset_url(),
			[ Cookie_Consent_Asset::get_asset_handle() ],
			SIW_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			self::get_asset_handle(),
			'siw_cookie_consent',
			[
				'config' => [
					'current_lang'      => determine_locale(),
					'page_scripts'      => true,
					'mode'              => 'opt-out',
					'cookie_name'       => self::COOKIE_NAME,
					'cookie_expiration' => self::COOKIE_LIFESPAN,
					'cookie_same_site'  => 'Strict',
					'use_rfc_cookie'    => true,
					'revision'          => self::COOKIE_REVISION,
					'gui_options'       => [
						'consent_modal'  => [
							'layout'     => 'bar',               // box/cloud/bar
							'position'   => 'bottom center',     // bottom/middle/top + left/right/center
							'transition' => 'slide',           // zoom/slide
						],
						'settings_modal' => [
							'layout'     => 'box',                 // box/bar
							'position'   => 'left',              // left/right
							'transition' => 'slide',            // zoom/slide
						],
					],
					'languages'         => [
						determine_locale() => [
							'consent_modal'  => [
								'description'   =>
									__( 'Wij gebruiken cookies om je gebruikservaring te optimaliseren, het webverkeer te analyseren en voor persoonlijke advertentiedoeleinden.', 'siw' ),
								'primary_btn'   => [
									'text' => __( 'Alles toestaan', 'siw' ),
									'role' => 'accept_all',
								],
								'secondary_btn' => [
									'text' => __( 'Beheren', 'siw' ),
									'role' => 'settings',
								],
							],
							'settings_modal' => [
								'title'             => __( 'Cookie voorkeuren', 'siw' ),
								'save_settings_btn' => __( 'Instellingen opslaan', 'siw' ),
								'accept_all_btn'    => __( 'Alles toestaan', 'siw' ),
								'reject_all_btn'    => __( 'Alles weigeren', 'siw' ),
								'close_btn_label'   => __( 'Sluiten', 'siw' ),
								'blocks'            => [
									[
										'description' =>
											__( 'Bij het bezoek aan deze website worden op jouw computer, tablet of smartphone cookies geplaatst.', 'siw' ) . SPACE .
											__( 'Cookies zijn kleine, eenvoudige tekstbestandjes.', 'siw' ) . SPACE .
											__( 'Cookies geven ons meer informatie over het surfgedrag op de website, waardoor we de website kunnen verbeteren en jou beter van dienst kunnen zien.', 'siw' ) . SPACE .
											__( 'Daarnaast kunnen met behulp van cookies de informatie en aanbiedingen op de site worden afgestemd op jouw voorkeuren.', 'siw' ) . SPACE .
											// translators: %s is de link naar het privacybeleid
											sprintf( __( 'Lees meer over hoe wij cookies gebruiken en hoe je ze kunt beheren in ons %s.', 'siw' ), Links::generate_link( get_privacy_policy_url(), __( 'privacybeleid', 'siw' ) ) ),
									],
									[
										'title'       => __( 'Noodzakelijke cookies', 'siw' ),
										'description' =>
											__( 'Deze cookies zijn noodzakelijk voor het functioneren van de website.', 'siw' ) . SPACE .
											__( 'Zonder deze cookies zou de website niet naar behoren werken', 'siw' ),
										'toggle'      => [
											'value'    => self::NECESSARY,
											'enabled'  => true,
											'readonly' => true,
										],
									],
									[
										'title'       => __( 'Analytische cookies', 'siw' ),
										'description' =>
										__( 'Analytische cookies verzamelen gegevens over het gebruik van een website zoals het aantal bezoekers, de tijd die bezoekers doorbrengen op een webpagina en foutmeldingen.', 'siw' ) . SPACE .
										__( 'We gebruiken Google Tag Manager (zorgt voor het verzamelen van anonieme gegevens over het gebruik van onze website en het opstellen van bezoekersstatistieken).', 'siw' ) . SPACE .
										// translators: %s is de link naar het privacybeleid van Google
										sprintf( __( 'Meer informatie over hoe Google met gegevens omgaat is te lezen in het %s van Google.', 'siw' ), Links::generate_external_link( 'http://www.google.com/intl/nl/policies/privacy/partners/', __( 'privacybeleid', 'siw' ) ) ),
										'toggle'      => [
											'value'    => self::ANALYTICAL,
											'enabled'  => true,
											'readonly' => true,
										],
									],
									[
										'title'       => __( 'Marketing cookies', 'siw' ),
										'description' =>
											__( 'Marketing cookies zorgen ervoor dat een website gepersonaliseerde reclameboodschappen kan sturen.', 'siw' ) . SPACE .
											__( 'De website van SIW gebruikt een zogenaamde Facebook Conversion Pixel (registreert gedrag na het bekijken van een advertentie in Facebook).', 'siw' ) . SPACE .
											// translators: %s is de link naar het privacybeleid van Facebook
											sprintf( __( 'Meer informatie over hoe Facebook met gegevens omgaat is te lezen in het %s van Facebook.', 'siw' ), Links::generate_external_link( 'https://www.facebook.com/about/privacy/', __( 'privacybeleid', 'siw' ) ) ),
										'toggle'      => [
											'value'    => self::MARKETING,
											'enabled'  => false,
											'readonly' => false,
										],
									],
									[
										'title'       => __( 'Meer informatie', 'siw' ),
										'description' =>
											sprintf(
												// translators: %s is de link naar het de contactpagina
												__( 'Voor meer informatie of vragen over ons privacybeleid kan je %s met ons opnemen.', 'siw' ),
												Links::generate_link( get_permalink( Special_Page::CONTACT->get_page() ), __( 'contact', 'siw' ) )
											),
									],
								],
							],
						],
					],
				],
			]
		);
		wp_enqueue_script( self::get_asset_handle() );
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function register_style() {
		self::enqueue_class_style();
	}

	#[Add_Action( 'generate_before_copyright' )]
	public function add_cookie_settings_button(): void {
		printf(
			'<a href="#" data-cc="c-settings">%s</a>',
			esc_html__( 'Cookie instellingen', 'siw' )
		);
	}

	#[Add_Filter( 'body_class' )]
	public function add_body_class( array $classes ): array {
		$classes[] = 'siw-cookie-consent';
		return $classes;
	}
}
