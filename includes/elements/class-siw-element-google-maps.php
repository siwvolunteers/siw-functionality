<?php

/**
 * Google Maps kaart
 * 
 * @package   SIW\Elements
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_Element_Google_Maps {

	/**
	 * Google Maps API-key
	 *
	 * @var string
	 */
	protected $api_key;

	/**
	 * Hoogt van kaart in pixels
	 *
	 * @var int
	 */
	protected $height = 300;

	/**
	 * Markers voor op kaart
	 *
	 * @var array
	 */
	protected $markers = [];

	/**
	 * Opties voor kaart
	 *
	 * @var array
	 */
	protected $options = [
		//'center'            => [ 'lat' => 0, 'lng' => 0 ],
		'zoom'              => 6,
		'zoomControl'       => true,
		'mapTypeControl'    => false,
		'scaleControl'      => false,
		'streetViewControl' => false,
		'rotateControl'     => false,
		'fullscreenControl' => false
	];

	/**
	 * Init
	 *
	 * @param string $id
	 */
	public function __construct() {
		$this->api_key = siw_get_option( 'google_maps_api_key' );
		if ( empty( $this->api_key ) ) {
			return;
		}
		$this->enqueue_scripts();
		$this->enqueue_styles();
	}

	/**
	 * Zet hoogte van de kaart
	 *
	 * @param int $height
	 */
	protected function set_height( int $height ) {
		$this->height = $height;
	}

	/**
	 * Zet opties voor kaart
	 *
	 * @param array $options
	 */
	public function set_options( array $options ) {
		$this->options = wp_parse_args( $options, $this->options );
	}

	/**
	 * Zet het midden van de kaart
	 *
	 * @param float $lat
	 * @param float $lng
	 */
	public function set_center( float $lat, float $lng ) {
		$this->options['center'] = [ 'lat' => $lat, 'lng' => $lng ];
	}

	/**
	 * Zet het midden van de kaart op basis van een locatie
	 *
	 * @param string $location
	 */
	public function set_location_center( string $location ) {
		$this->options['center'] = $location;
	}

	/**
	 * Voegt marker toe
	 *
	 * @param float $lat
	 * @param float $lng
	 * @param string $title
	 * @param string $description
	 */
	public function add_marker( float $lat, float $lng, string $title, string $description = '' ) {
		if ( ! isset( $this->options['center'] ) ) {
			$this->options['center'] = [ 'lat' => $lat, 'lng' => $lng ];
		}
		$this->markers[] = [
			'title'       => $title,
			'description' => $description,
			'position'    => [ 'lat' => $lat, 'lng' => $lng ],
		];
	}

	/**
	 * Voegt marker op locatie toe
	 *
	 * @param string $location
	 * @param string $title
	 * @param string $description
	 */
	public function add_location_marker( string $location, string $title, string $description = '' ) {
		if ( ! isset( $this->options['center'] ) ) {
			$this->options['center'] = $location;
		}
		$this->markers[] = [
			'title'       => $title,
			'description' => $description,
			'position'    => $location,
		];
	}

	/**
	 * Rendert de kaart
	 */
	public function render() {
		echo $this->generate();
	}

	/**
	 * Genereert kaart
	 * 
	 * @return string
	 */
	public function generate() {
		$attributes = [
			'id'           => uniqid('siw-google-map-'),
			'class'        => 'siw-google-map',
			'data-options' => $this->options,
			'data-markers' => $this->markers,
		];
		return SIW_Formatting::generate_tag( 'div', $attributes ) . '</div>';
	}

	/**
	 * Voegt scripts toe
	 */
	public function enqueue_scripts() {
		$google_maps_url = add_query_arg( [
			'key'      => $this->api_key,
			'callback' => 'siwInitAllGoogleMaps',
		], SIW_Properties::GOOGLE_MAPS_API_URL );
		wp_enqueue_script( 'google-maps', $google_maps_url, [], null, true );
		wp_enqueue_script( 'siw-google-maps', SIW_ASSETS_URL . 'js/siw-google-maps.js', [ 'google-maps'], SIW_PLUGIN_VERSION, true );
	}

	/**
	 * Voegt inline styling toe
	 */
	public function enqueue_styles() {
		wp_register_style( 'siw-google-maps', false );
		wp_enqueue_style( 'siw-google-maps' );

		$inline_style = SIW_Util::generate_css( [
				'.siw-google-map' => [ 'height' => "{$this->height}px" ],
		]);
		wp_add_inline_style( 'siw-google-maps', $inline_style );
	}
}