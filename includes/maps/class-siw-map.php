<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class om een Mapplic kaart te genereren
 * 
 * @package   SIW\Maps
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Util
 * @uses      SIW_Properties
 * */
class SIW_Map {

	/**
	 * Mapplic versie
	 */
	const MAPPLIC_VERSION = '5.0';

	/**
	 * Basis-url van de Mapplic bestanden
	 */
	protected $mapplic_url = SIW_ASSETS_URL . 'modules/mapplic/';

	/**
	 * ID van de kaart
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Bestandsnaam van kaart
	 *
	 * @var string
	 */
	protected $filename;

	/**
	 * Instellingen voor kaart
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Defaultinstellingen van de kaart
	 *
	 * @var array
	 */
	protected $default_options;

	/**
	 * Data van de kaart
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Categorieën
	 * 
	 * @var array
	 */
	protected $categories;

	/**
	 * Locaties
	 * 
	 * @var array
	 */
	 protected $locations;

	/**
	 * Standaard categoriegegevens
	 * 
	 * @var array
	 */
	private $default_category_data;

	/**
	 * Standaard locatiegegevens
	 * 
	 * @var array
	 */
	protected $default_location_data;

	/**
	 * Inline css-regels
	 * 
	 * @var array
	 */
	protected $inline_css;

	/**
	 * Aparte content voor mobiel
	 *
	 * @var string
	 */
	protected $mobile_content = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_default_options();
		$this->set_default_category_data();
		$this->set_default_location_data();
	}

	/**
	 * Rendert de kaart
	 *
	 * @return string
	 */
	public function render() {
		$this->enqueue_styles();
		$this->enqueue_scripts();
		$content = sprintf( '<div id="mapplic-%s" class="mapplic-dark"></div>', $this->id );
		if ( null != $this->mobile_content ) {
			$content = '<div class="hidden-xs">' . $content . '</div>';
			$content .= '<div class="hidden-sm hidden-md hidden-lg">' . $this->mobile_content . '</div>';
		}
		return $content;
	}

	/**
	 * Voegt de benodigde scripts toe
	 */
	protected function enqueue_scripts() {
		$deps = [ 'jquery', 'hammer' ];
		if ( true == $this->options[ 'lightbox' ] ) {
			wp_register_script( 'magnific-popup', $this->mapplic_url . 'js/magnific-popup.js', false, self::MAPPLIC_VERSION );
			$deps[] = 'magnific-popup';
		}
		if ( true == $this->options[ 'mousewheel' ] ) {
			wp_register_script( 'mousewheel', $this->mapplic_url . 'js/jquery.mousewheel.js', false, self::MAPPLIC_VERSION ); //TODO: kan dit niet in de footer?
			$deps[] = 'mousewheel';
		}
		wp_register_script( 'hammer', $this->mapplic_url . 'js/hammer.min.js', false, self::MAPPLIC_VERSION);
		wp_register_script( 'mapplic-script', $this->mapplic_url . 'js/mapplic.js', $deps, self::MAPPLIC_VERSION );
	
		$mapplic_localization = [
			'more'     => __( 'Meer', 'siw' ),
			'search'   => __( 'Zoeken', 'siw' ),
		];
		/* Vertalingen en kaartgegevens toevoegen */
		wp_localize_script( 'mapplic-script', 'mapplic_localization', $mapplic_localization );
		wp_enqueue_script( 'mapplic-script' );
		
		$inline_script = sprintf( "( function( $ ){ $(document).ready(function() { $('#mapplic-%s').mapplic(%s); }); } )( jQuery )", $this->id, json_encode( $this->options, JSON_PRETTY_PRINT ) );
		wp_add_inline_script( 'mapplic-script', $inline_script );
	}

	/**
	 * Voegt benodigde styles toe
	 */
	protected function enqueue_styles() {
		$deps = false;
		if ( true == $this->options[ 'lightbox' ] ) {
			wp_register_style( 'magnific-popup', $this->mapplic_url . 'css/magnific-popup.css', false, self::MAPPLIC_VERSION );
			$deps = [ 'magnific-popup' ];
		}
		wp_register_style( 'mapplic-style', $this->mapplic_url . 'css/mapplic.css', $deps, self::MAPPLIC_VERSION );
		wp_enqueue_style( 'mapplic-style' );

		if ( isset( $this->inline_css ) ) {
			$css = SIW_Util::generate_css( $this->inline_css );
			wp_add_inline_style( 'mapplic-style', $css );
		}
	}

	/**
	 * Zet de standaardopties
	 */
	protected function set_default_options() {
		$default_options = [
			'source'        => false,
			'height'        => 420,
			'landmark'      => null,
			'mapfill'       => false,
			'markers'       => true,
			'minimap'       => false,
			'sidebar'       => true,
			'alphabetic'    => true,
			'search'        => false,
			'thumbholder'   => false,
			'lightbox'      => false,
			'deeplinking'   => false,
			'clearbutton'   => true,
			'zoombuttons'   => false,
			'zoomoutclose'  => true,
			'hovertip'      => [ 'desc' => false ],
			'tooltip'       => [ 'thumb' => false, 'desc' => true, 'link' => true ],
			'smartip'       => true,
			'mousewheel'    => false,
			'fullscreen'    => false,
			'developer'     => false,
			'fillcolor'     => SIW_Properties::PRIMARY_COLOR,
			'action'        => 'tooltip',
			'maxscale'      => 2,
			'zoom'          => true,
			'skin'          => 'mapplic-dark',
		];
		$this->default_options = $default_options;
	}

	/**
	 * Zet de standaard categoriegegevens
	 */
	protected function set_default_category_data() {
		$default_category_data = [
			'id'    => false,
			'title' => false,
			'color' => SIW_Properties::PRIMARY_COLOR_HOVER,
			'show'  => 'false',
		];
		$this->default_category_data = $default_category_data;
	}

	/**
	 * Zet de standaard locatiegegevens
	 */
	protected function set_default_location_data() {
		$default_location_data = [
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
		$this->default_location_data = $default_location_data;
	}

	/**
	 * Zet het ID van de kaart
	 * 
	 * @param string $id
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

	/**
	 * Zet de bestandsnaam van de kaart
	 * 
	 * @param string $filename
	 */
	public function set_filename( $filename ) {
		$this->filename = $filename;
	}

	/**
	 * Zet de instellingen van de kaart
	 */
	public function set_options( $options ) {
		$this->options = wp_parse_args( $options, $this->default_options );
		$this->options['source'] = $this->data;
	}

	/**
	 * Zet de categorieën
	 * 
	 * @param array $categories
	 */
	public function set_categories( $categories ) {
		foreach ( $categories as $category_data ) {
			$category_data = wp_parse_args( $category_data, $this->default_category_data );
			$this->categories[] = $category_data;
		}
	}

	/**
	 * Zet de locaties
	 * 
	 * @param array $locations
	 */
	public function set_locations( $locations ) {
		/* Locaties */
		foreach ( $locations as $location_data ) {
			$location_data = wp_parse_args( $location_data, $this->default_location_data );
			$this->locations[] = $location_data;
		}
	}

	/**
	 * Zet inline CSS
	 * 
	 * @param array $css
	 */
	public function set_inline_css( $css ) {
		$this->inline_css = $css;
	}

	/**
	 * Zet alternatieve content voor mobiel
	 *
	 * @param string $content
	 */
	public function set_mobile_content( $content ) {
		$this->mobile_content = $content;
	}

	/**
	 * Zet de eigenschappen van de kaart
	 *
	 * @param array $data
	 */
	public function set_data( $data ) {
		$default_data = [
			'mapwidth'  => null,
			'mapheight' => null,
			'bottomLat' => '',
			'leftLng'   => '',
			'topLat'    => '',
			'rightLng'  => '',
		];
		$data = wp_parse_args( $data, $default_data );
		
		$data['categories'] = $this->categories;
		$data['levels'][] = [
			'id'        => $this->id,
			'title'     => $this->id,
			'map'       => $this->mapplic_url . 'maps/' . $this->filename . '.svg', 
			'locations' => $this->locations,
		];
		$this->data = $data;
	}
}
