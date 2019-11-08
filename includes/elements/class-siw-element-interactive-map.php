<?php

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @package   SIW\Elements
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Util
 * @uses      SIW_Properties
 * */
abstract class SIW_Element_Interactive_Map {

	/**
	 * Mapplic versie
	 *
	 * @var string
	 */
	const MAPPLIC_VERSION = '5.0.1';

	/**
	 * URL van Mapplic-bestanden
	 *
	 * @var string
	 */
	protected $mapplic_url = SIW_ASSETS_URL . 'modules/mapplic/';

	/**
	 * ID van kaart
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Bestandsnaam van kaart
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * Inline CSS-regels
	 *
	 * @var array
	 */
	protected $inline_css;

	/**
	 * Gegevens van kaart
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Haalt categorieën op
	 * 
	 * @return array
	 */
	abstract protected function get_categories();

	/**
	 * Geef locaties terug
	 * 
	 * @return array
	 */
	abstract protected function get_locations();

	/**
	 * Geeft alternatieve content voor mobiel terug
	 * 
	 * @return string
	 */
	abstract protected function get_mobile_content();

	/**
	 *  Genereert interactieve kaart
	 */
	public function generate() {
		$this->set_options();
		$this->enqueue_styles();
		$this->enqueue_scripts();

		$attributes = [
			'id'           => uniqid( 'siw-interactive-map-' ),
			'class'        => ['siw-interactive-map', 'mapplic-dark'],
			'data-options' => $this->options,
		];
		$content = SIW_Formatting::generate_tag( 'div', $attributes ) . '</div>' ;

		$content = '<div class="hidden-xs">' . $content . '</div>';
		$content .= '<div class="hidden-sm hidden-md hidden-lg">' . $this->get_mobile_content() . '</div>';
		return $content;
	}

	/**
	 * Zet opties van de kaart
	 */
	protected function set_options() {
		$default_options = [
			'source'        => $this->get_map_data(),
			'landmark'      => null,
			'portrait'      => 668, //TODO: juiste breakpoint
			'alphabetic'    => true,
			'search'        => false,
			'lightbox'      => false,
			'deeplinking'   => false,
			'zoombuttons'   => false,
			'zoomoutclose'  => true,
			'mousewheel'    => false,
			'fullscreen'    => false,
			'developer'     => false, //TODO: setting of WP_DEBUG?
			'fillcolor'     => SIW_Properties::PRIMARY_COLOR,
			'action'        => 'tooltip',
			'maxscale'      => 2,
		];
		$this->options = wp_parse_args( $this->options, $default_options );
	}

	/**
	 * Haalt gegevens voor kaart op
	 * 
	 * @return array
	 */
	protected function get_map_data() {
		$default_data = [
			'mapwidth'  => null,
			'mapheight' => null,
			'bottomLat' => '',
			'leftLng'   => '',
			'topLat'    => '',
			'rightLng'  => '',
		];
		$data = wp_parse_args( $this->data, $default_data );
		
		$data['categories'] = array_map( [ $this, 'parse_category'], $this->get_categories() );
		$data['levels'][] = [
			'id'        => $this->id,
			'title'     => $this->id,
			'map'       => $this->mapplic_url . 'maps/' . $this->file . '.svg', 
			'locations' => array_map( [ $this, 'parse_location'], $this->get_locations() ),
		];
		return $data;
	}

	/**
	 * Parset gegevens van categorie
	 *
	 * @param array $category
	 * @return array
	 */
	protected function parse_category( $category ) {
		$default = [
			'id'    => false,
			'title' => false,
			'color' => SIW_Properties::PRIMARY_COLOR_HOVER,
			'show'  => 'false',
		];
		return wp_parse_args( $category, $default );
	}

	/**
	 * Parset de gegevens van locatie
	 */
	protected function parse_location( $location ) {
		$default = [
			'id'            => false,
			'title'         => false,
			'image'         => null,
			'about'         => false,
			'description'   => false,
			'action'        => 'tooltip',
			'pin'           => 'hidden',
			'fill'          => SIW_Properties::PRIMARY_COLOR,
			'x'             => false,
			'y'             => false,
			'lat'           => false,
			'lng'           => false,
			'pin'           => 'hidden',
			'category'      => false,
		];
		return wp_parse_args( $location, $default );
	}

	/**
	 * Voegt de benodigde scripts toe
	 */
	protected function enqueue_scripts() {
		$deps = [ 'jquery', 'hammer' ];
		if ( true == $this->options[ 'lightbox' ] ) {
			wp_register_script( 'magnific-popup', $this->mapplic_url . 'js/magnific-popup.js', [ 'jquery' ], self::MAPPLIC_VERSION, true );
			$deps[] = 'magnific-popup';
		}
		if ( true == $this->options[ 'mousewheel' ] ) {
			wp_register_script( 'mousewheel', $this->mapplic_url . 'js/jquery.mousewheel.js', [ 'jquery' ], self::MAPPLIC_VERSION, true );
			$deps[] = 'mousewheel';
		}
		wp_register_script( 'hammer', $this->mapplic_url . 'js/hammer.min.js', [], self::MAPPLIC_VERSION, true );
		wp_register_script( 'mapplic', $this->mapplic_url . 'js/mapplic.js', $deps, self::MAPPLIC_VERSION, true );

		$mapplic_localization = [
			'more'     => __( 'Meer', 'siw' ),
			'search'   => __( 'Zoeken', 'siw' ),
		];
		wp_localize_script( 'mapplic', 'mapplic_localization', $mapplic_localization );
		wp_enqueue_script( 'mapplic' );

		wp_register_script( 'siw-interactive-maps', SIW_ASSETS_URL . 'js/siw-interactive-maps.js', [ 'mapplic' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-interactive-maps' );
	}

	/**
	 * Voegt benodigde styles toe
	 */
	protected function enqueue_styles() {
		$deps = [];
		if ( true == $this->options[ 'lightbox' ] ) {
			wp_register_style( 'magnific-popup', $this->mapplic_url . 'css/magnific-popup.css', false, self::MAPPLIC_VERSION );
			$deps[] = 'magnific-popup';
		}
		wp_register_style( 'mapplic', $this->mapplic_url . 'css/mapplic.css', $deps, self::MAPPLIC_VERSION );
		wp_enqueue_style( 'mapplic' );

		if ( isset( $this->inline_css ) ) {
			$css = SIW_CSS::generate_inline_css( $this->inline_css );
			wp_add_inline_style( 'mapplic', $css );
		}
	}
}