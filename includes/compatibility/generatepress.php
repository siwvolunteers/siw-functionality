<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Data\Continent;
use SIW\i18n;
use SIW\Properties;

/**
 * Aanpassingen voor GeneratePress
 * 
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://generatepress.com/
 */
class GeneratePress{

	/** Snelheid voor scroll to top */
	const BACK_TO_TOP_SCROLL_SPEED = 500;

	/** Toegestane lettertypes */
	protected array $allowed_fonts = [
		'System Stack',
	];

	/** Init */
	public static function init() {
		
		$self = new self();
		add_filter( 'generate_copyright', [ $self, 'set_copyright_message' ] );

		//Elements
		add_action( 'init', [ $self, 'add_elements_menu_order'] );
		add_filter( 'generate_elements_custom_args', [ $self, 'set_elements_orderby'] );

		//Fonts
		add_filter( 'generate_google_fonts_array', '__return_empty_array' );
		add_filter( 'generate_typography_default_fonts', [ $self, 'remove_fonts'] );

		//404
		add_filter( 'generate_404_title', [ $self, 'set_404_title' ] );
		add_filter( 'generate_404_text', [ $self, 'set_404_text' ] );

		//Verwijder cart fragments van plugin
		add_action( 'init', [ $self, 'remove_cart_fragment_hooks'], PHP_INT_MAX );

		//Pas snelheid voor omhoog scrollen aan
		add_filter( 'generate_back_to_top_scroll_speed', fn() : int => self::BACK_TO_TOP_SCROLL_SPEED );

		add_filter( 'generate_footer_widgets', [ $self, 'set_footer_widgets'] );

		//Default instellingen zetten
		add_filter( 'generate_default_color_palettes', [ $self, 'set_default_color_palettes'] );
	}

	/** Voeg menu order toe een GP Elements */
	public function add_elements_menu_order() {
		add_post_type_support( 'gp_elements', 'page-attributes' );
	}

	/** Sorteer elements standaard op menu_order */
	public function set_elements_orderby( array $args ) : array {
		$args['orderby'] = 'menu_order';
		return $args;
	}

	/** Zet copyright voor footer */
	public function set_copyright_message() : string {
		return sprintf( '&copy; %s %s', current_time( 'Y' ), Properties::NAME );
	}

	/** Zet toegestane lettertypes */
	public function remove_fonts( array $fonts ) : array {
		$fonts = array_merge( ['inherit'], $this->allowed_fonts );
		return $fonts;
	}

	/** Zet titel van 404-pagina */
	public function set_404_title() : string {
		return esc_html__( 'Pagina niet gevonden', 'siw');
	}

	/** Zet tekst van 404-pagina */
	public function set_404_text() : string {
		return esc_html__( 'Oeps! Helaas kunnen we de pagina die je zoekt niet vinden. Controleer of de spelling correct is en doe nog een poging via onderstaande zoekfunctie.', 'siw' );
	}

	/** Verwijder cart fragments hook van het thema */
	public function remove_cart_fragment_hooks() {
		remove_filter( 'woocommerce_add_to_cart_fragments', 'generatepress_wc_cart_link_fragment' );
		remove_filter( 'woocommerce_add_to_cart_fragments', 'generatepress_add_to_cart_panel_fragments' );
	}

	/** Zet het aantal footer-widgets op 1 voor andere talen dan Nederlands */
	public function set_footer_widgets( string $widgets ) : string {
		if ( ! i18n::is_default_language() ) {
			$widgets = '1';
		}
		return $widgets;
	}

	/** Zet default kleurenpalet */
	public function set_default_color_palettes() : array {

		$continent_colors = wp_cache_get( 'siw_continent_colors' );
		if ( false == $continent_colors ) {
			$continent_colors = array_values(
				array_map(
				fn( Continent $continent ) : string => $continent->get_color(),
				\siw_get_continents()
			));
			wp_cache_set( 'siw_continent_colors', $continent_colors );
		}

		return [
			Properties::PRIMARY_COLOR,
			Properties::SECONDARY_COLOR,
			Properties::FONT_COLOR,
			...$continent_colors,
			'#fefefe',
		];
	}
}
