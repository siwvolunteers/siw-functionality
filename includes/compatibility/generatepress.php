<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Properties;

/**
 * Aanpassingen voor GeneratePress
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @see       https://generatepress.com/
 * @since     3.1.?
 */
class GeneratePress{ 

	/**
	 * Snelheid voor scroll to top
	 * 
	 * @var int
	 */
	const BACK_TO_TOP_SCROLL_SPEED = 500;

	/**
	 * Toegestane lettertypes
	 *
	 * @var array
	 */
	protected $allowed_fonts = [
		'System Stack',
	];

	/**
	 * Init
	 */
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

		//Toon title //TODO: conditioneel maken?
		add_filter( 'generate_show_title', '__return_false' );

		//Verwijder cart fragments van plugin
		add_action( 'init', [ $self, 'remove_cart_fragment_hooks'], PHP_INT_MAX );

		//Pas snelheid voor omhoog scrollen aan
		add_filter( 'generate_back_to_top_scroll_speed', [ $self, 'set_back_to_top_scroll_speed'] );
	}

	/**
	 * Voeg menu order toe een GP Elements
	 */
	public function add_elements_menu_order() {
		add_post_type_support( 'gp_elements', 'page-attributes' );
	}

	/**
	 * Sorteer elements standaard op menu_order
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function set_elements_orderby( array $args ) : array {
		$args['orderby'] = 'menu_order';
		return $args;
	}

	/**
	 * Zet copyright voor footer
	 *
	 * @return string
	 */
	public function set_copyright_message() {
		return sprintf( '&copy; %s %s', current_time( 'Y' ), Properties::NAME );
	}

	/**
	 * Zet toegestane lettertypes
	 *
	 * @param array $fonts
	 *
	 * @return array
	 */
	public function remove_fonts( array $fonts ) : array {
		$fonts = array_merge( ['inherit'], $this->allowed_fonts );
		return $fonts;
	}

	/**
	 * Zet titel van 404-pagina
	 *
	 * @return string
	 */
	public function set_404_title() {
		return esc_html__( 'Pagina niet gevonden', 'siw');
	}

	/**
	 * Zet tekst van 404-pagina
	 *
	 * @return string
	 */
	public function set_404_text() {
		return esc_html__( 'Oeps! Helaas kunnen we de pagina die je zoekt niet vinden. Controleer of de spelling correct is en doe nog een poging via onderstaande zoekfunctie.', 'siw' );
	}

	/**
	 * Verwijder cart fragments hook van het thema
	 */
	public function remove_cart_fragment_hooks() {
		remove_filter( 'woocommerce_add_to_cart_fragments', 'generatepress_wc_cart_link_fragment' );
		remove_filter( 'woocommerce_add_to_cart_fragments', 'generatepress_add_to_cart_panel_fragments' );
	}

	/**
	 * Zet snelheid van omhoog scrollen
	 *
	 * @return int
	 */
	public function set_back_to_top_scroll_speed() : int {
		return self::BACK_TO_TOP_SCROLL_SPEED;
	}
}
