<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Assets\JS_Cookie;
use SIW\i18n;
use SIW\Properties;
use SIW\Update;
use SIW\Util\CSS;

/**
 * Aanpassingen voor GeneratePress
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://generatepress.com/
 */
class GeneratePress {

	/** Snelheid voor scroll to top */
	const BACK_TO_TOP_SCROLL_SPEED = 500;

	/** Init */
	public static function init() {

		$self = new self();
		add_filter( 'generate_copyright', [ $self, 'set_copyright_message' ] );

		//Elements
		add_action( 'init', [ $self, 'add_elements_menu_order'] );
		add_filter( 'generate_elements_custom_args', [ $self, 'set_elements_orderby'] );

		//404
		add_filter( 'generate_404_title', [ $self, 'set_404_title' ] );
		add_filter( 'generate_404_text', [ $self, 'set_404_text' ] );

		//Pas snelheid voor omhoog scrollen aan
		add_filter( 'generate_back_to_top_scroll_speed', fn() : int => self::BACK_TO_TOP_SCROLL_SPEED );

		add_filter( 'generate_footer_widgets', [ $self, 'set_footer_widgets'] );

		//Default instellingen zetten
		add_filter( 'generate_default_color_palettes', [ $self, 'set_default_color_palettes'] );
		add_action( 'customize_save_after', [ $self, 'set_global_colors'], 1 );
		add_action( Update::PLUGIN_UPDATED_HOOK, [ $self, 'set_global_colors'], 1 );
		add_action( Update::PLUGIN_UPDATED_HOOK, 'generate_update_dynamic_css_cache' );
	}

	/** Voeg menu order toe een GP Elements */
	public function add_elements_menu_order() {
		add_post_type_support( 'gp_elements', 'page-attributes' );
	}

	/** Sorteer elements standaard op menu_order */
	public function set_elements_orderby( array $args ): array {
		$args['orderby'] = 'menu_order';
		return $args;
	}

	/** Zet copyright voor footer */
	public function set_copyright_message(): string {
		return sprintf( '&copy; %s %s', current_time( 'Y' ), Properties::NAME );
	}

	/** Zet titel van 404-pagina */
	public function set_404_title(): string {
		return esc_html__( 'Pagina niet gevonden', 'siw');
	}

	/** Zet tekst van 404-pagina */
	public function set_404_text(): string {
		return esc_html__( 'Oeps! Helaas kunnen we de pagina die je zoekt niet vinden. Controleer of de spelling correct is en doe nog een poging via onderstaande zoekfunctie.', 'siw' );
	}

	/** Zet het aantal footer-widgets op 1 voor andere talen dan Nederlands */
	public function set_footer_widgets( string $widgets ): string {
		if ( ! i18n::is_default_language() ) {
			$widgets = '1';
		}
		return $widgets;
	}

	/** Zet default kleurenpalet */
	public function set_default_color_palettes(): array {
		return [
			CSS::CONTRAST_COLOR,
			CSS::CONTRAST_COLOR_LIGHT,
			CSS::BASE_COLOR,
			CSS::ACCENT_COLOR,
		];
	}

	/** Zet global colors */
	public function set_global_colors() {
		$generate_settings = get_option( 'generate_settings', [] );
		$generate_settings['global_colors'] = [
			[
				'name'  => 'Accent',
				'slug'  => 'siw-accent',
				'color' => CSS::ACCENT_COLOR,
			],
			[
				'name'  => 'Contrast',
				'slug'  => 'siw-contrast',
				'color' => CSS::CONTRAST_COLOR
			],
			[
				'name'  => 'Contrast 2',
				'slug'  => 'siw-contrast-light',
				'color' => CSS::CONTRAST_COLOR_LIGHT
			],
			[
				'name'  => 'Base',
				'slug'  => 'siw-base',
				'color' => CSS::BASE_COLOR,
			],
		];
		update_option( 'generate_settings', $generate_settings );
	}
}
