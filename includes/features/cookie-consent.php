<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Features\Cookie_Category;
use SIW\Data\Special_Page;
use SIW\Elements\Link;
use SIW\External_Assets\Cookie_Consent as Cookie_Consent_Asset;
use SIW\Traits\Class_Assets;

class Cookie_Consent extends Base {

	use Class_Assets;

	private const COOKIE_NAME = 'siw_cookie_consent';
	private const COOKIE_LIFESPAN = 365;
	private const COOKIE_REVISION = 1;

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
				'autoShow'               => true,
				'disablePageInteraction' => false,
				'manageScriptTags'       => true,
				'hideFromBots'           => true,
				'mode'                   => 'opt-out',
				'revision'               => self::COOKIE_REVISION,

				'cookie'                 => [
					'name'             => self::COOKIE_NAME,
					'sameSite'         => 'Strict',
					'expiresAfterDays' => self::COOKIE_LIFESPAN,
				],
				'guiOptions'             => [
					'consentModal'     => [
						'layout'             => 'box',
						'position'           => 'bottom right',
						'equalWeightButtons' => false,
					],
					'preferencesModal' => [
						'layout'             => 'box',
						'equalWeightButtons' => false,
					],
				],
				'categories'             => $this->get_cookie_categories(),
				'language'               => [
					'default'      => determine_locale(),
					'translations' => [
						determine_locale() => [
							'consentModal'     => [
								'description'        => __( 'Wij gebruiken cookies om je gebruikservaring te optimaliseren, het webverkeer te analyseren en voor persoonlijke advertentiedoeleinden.', 'siw' ),
								'acceptAllBtn'       => __( 'Alles toestaan', 'siw' ),
								'acceptNecessaryBtn' => __( 'Alles weigeren', 'siw' ),
								'showPreferencesBtn' => __( 'Beheren', 'siw' ),
							],
							'preferencesModal' => [
								'title'              => __( 'Cookie voorkeuren', 'siw' ),
								'acceptAllBtn'       => __( 'Alles toestaan', 'siw' ),
								'acceptNecessaryBtn' => __( 'Alles weigeren', 'siw' ),
								'savePreferencesBtn' => __( 'Instellingen opslaan', 'siw' ),
								'closeIconLabel'     => __( 'Sluiten', 'siw' ),
								'sections'           => [
									[
										'description' =>
											__( 'Bij het bezoek aan deze website worden op jouw computer, tablet of smartphone cookies geplaatst.', 'siw' ) . SPACE .
											__( 'Cookies zijn kleine, eenvoudige tekstbestandjes.', 'siw' ) . SPACE .
											__( 'Cookies geven ons meer informatie over het surfgedrag op de website, waardoor we de website kunnen verbeteren en jou beter van dienst kunnen zien.', 'siw' ) . SPACE .
											__( 'Daarnaast kunnen met behulp van cookies de informatie en aanbiedingen op de site worden afgestemd op jouw voorkeuren.', 'siw' ) . SPACE .
											sprintf(
												// translators: %s is de link naar het privacybeleid
												__( 'Lees meer over hoe wij cookies gebruiken en hoe je ze kunt beheren in ons %s.', 'siw' ),
												Link::create()
													->set_url( get_privacy_policy_url() )
													->set_text( __( 'privacybeleid', 'siw' ) )
													->generate()
											),
									],
									[
										'linkedCategory' => Cookie_Category::NECESSARY->value,
										'title'          => __( 'Noodzakelijke cookies', 'siw' ),
										'description'    =>
											__( 'Deze cookies zijn noodzakelijk voor het functioneren van de website.', 'siw' ) . SPACE .
											__( 'Zonder deze cookies zou de website niet naar behoren werken', 'siw' ),
									],
									[
										'linkedCategory' => Cookie_Category::ANALYTICAL->value,
										'title'          => __( 'Analytische cookies', 'siw' ),
										'description'    =>
											__( 'Analytische cookies verzamelen gegevens over het gebruik van een website zoals het aantal bezoekers, de tijd die bezoekers doorbrengen op een webpagina en foutmeldingen.', 'siw' ) . SPACE .
											__( 'We gebruiken Google Tag Manager (zorgt voor het verzamelen van anonieme gegevens over het gebruik van onze website en het opstellen van bezoekersstatistieken).', 'siw' ) . SPACE .
											sprintf(
												// translators: %s is de link naar het privacybeleid van Google
												__( 'Meer informatie over hoe Google met gegevens omgaat is te lezen in het %s van Google.', 'siw' ),
												Link::create()
													->set_url( 'http://www.google.com/intl/nl/policies/privacy/partners/' )
													->set_text( __( 'privacybeleid', 'siw' ) )
													->set_is_external()
													->generate()
											),
									],
									[
										'linkedCategory' => Cookie_Category::MARKETING->value,
										'title'          => __( 'Marketing cookies', 'siw' ),
										'description'    =>
											__( 'Marketing cookies zorgen ervoor dat een website gepersonaliseerde reclameboodschappen kan sturen.', 'siw' ) . SPACE .
											__( 'SIW adverteert daartoe via Facebook, Instagram en Google Ads.', 'siw' ),
									],
									[
										'title'       => __( 'Meer informatie', 'siw' ),
										'description' => sprintf(
											// translators: %s is de link naar het de contactpagina
											__( 'Voor meer informatie of vragen over ons privacybeleid kan je %s met ons opnemen.', 'siw' ),
											Link::create()
												->set_url( get_permalink( Special_Page::CONTACT->get_page() ) )
												->set_text( __( 'contact', 'siw' ) )
												->generate()
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

	protected function get_cookie_categories(): array {
		$categories = [];
		foreach ( Cookie_Category::cases() as $category ) {
			$categories[ $category->value ] = [
				'enabled'  => $category->enabled(),
				'readOnly' => $category->readonly(),
			];
		}
		return $categories;
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function register_style() {
		self::enqueue_class_style();
	}

	#[Add_Action( 'generate_before_copyright' )]
	public function add_cookie_settings_button(): void {
		Link::create()
			->set_url( '#' )
			->set_text( __( 'Cookie instellingen', 'siw' ) )
			->add_attribute( 'data-cc', 'show-preferencesModal' )
			->render();
	}

	#[Add_Filter( 'body_class' )]
	public function add_body_class( array $classes ): array {
		$classes[] = 'siw-cookie-consent';
		return $classes;
	}
}
