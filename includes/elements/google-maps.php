<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;
use SIW\Util\CSS;

/**
 * Google Maps kaart
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @see       https://developers.google.com/maps/documentation/javascript/tutorial
 */
class Google_Maps {

	/**
	 * URL voor Google Maps API
	 *
	 * @var string
	 */
	const API_URL = 'https://maps.googleapis.com/maps/api/js';

	/**
	 * Google Maps API-key
	 */
	protected string $api_key;

	/**
	 * Hoogt van kaart in pixels
	 */
	protected int $height = 300;

	/**
	 * Markers voor op kaart
	 */
	protected array $markers = [];

	/**
	 * Opties voor kaart
	 */
	protected array $options = [
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
	 */
	public function __construct() {
		$this->api_key = siw_get_option( 'google_maps.api_key' );
		$this->enqueue_scripts();
		$this->enqueue_styles();
		add_filter( 'siw_preconnect_urls', [ $this, 'add_urls'] );
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
	public function generate() : string {
		return Template::parse_template(
			'elements/google-maps',
			[
				'id'      => uniqid( 'siw-google-map-' ),
				'options' => $this->options,
				'markers' => $this->markers,
			]
		);
	}

	/**
	 * Voegt scripts toe
	 */
	public function enqueue_scripts() {
		$google_maps_url = add_query_arg( [
			'key'      => $this->api_key,
		], self::API_URL );
		wp_enqueue_script( 'google-maps', $google_maps_url, [], null, true );
		wp_enqueue_script( 'siw-google-maps', SIW_ASSETS_URL . 'js/elements/siw-google-maps.js', [ 'google-maps'], SIW_PLUGIN_VERSION, true );
	}

	/**
	 * Voegt inline styling toe
	 */
	public function enqueue_styles() {
		wp_register_style( 'siw-google-maps', false );
		wp_enqueue_style( 'siw-google-maps' );

		$inline_style = CSS::generate_inline_css( [
			'.siw-google-map' => [ 'height' => "{$this->height}px" ],
		]);
		wp_add_inline_style( 'siw-google-maps', $inline_style );
	}

	/**
	 * Voegt url's toe t.b.v. DNS-prefetch en preconnect
	 *
	 * @param array $urls
	 * @return array
	 * 
	 * @todo werkt pas als de constructor eerder aangeroepen wordt.
	 */
	public function add_urls( array $urls ) : array {
		$urls[] = 'maps.googleapis.com';
		$urls[] = 'maps.google.com';
		$urls[] = 'maps.gstatic.com';
		$urls[] = 'csi.gstatic.com';
		return $urls;
	}
}
