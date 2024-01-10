<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\External_Assets\Leaflet;
use SIW\Util\CSS;

/**
 * Leaflet kaart
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Leaflet_Map extends Element {

	private const GEOCODING_URL = 'https://nominatim.openstreetmap.org/search';
	private const TILESERVER_URL_TEMPLATE = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
	private const HASH_ALGORITHM = 'sha256';
	private const HASH_PREFIX = 'siw_geocode_';

	/** Hoogt van kaart in pixels */
	protected int $height = 300;

	/** Markers voor op kaart */
	protected array $markers = [];

	/** Center van kaart */
	protected array $center;

	/** Zoom-niveau */
	protected int $zoom = 6;

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'map_options' => [
				'center'          => $this->center,
				'zoom'            => $this->zoom,
				'scrollWheelZoom' => false,
			],
			'markers'     => $this->markers,
		];
	}

	/** Zet hoogte van de kaart */
	protected function set_height( int $height ): self {
		$this->height = $height;
		return $this;
	}

	/** Zet zoom **/
	public function set_zoom( int $zoom ): self {
		$this->zoom = $zoom;
		return $this;
	}

	/** Zet het midden van de kaart */
	public function set_center( float $lat, float $lng ): self {
		$this->center = [ 'coordinates' => [ $lat, $lng ] ];
		return $this;
	}

	/** Zet het midden van de kaart op basis van een locatie */
	public function set_location_center( string $location ): self {
		$this->center = [
			'location' => $location,
			'hash'     => $this->hash_location( $location ),
		];
		return $this;
	}

	/** Voegt marker toe */
	public function add_marker( float $lat, float $lng, string $title, string $description = '' ): self {
		if ( ! isset( $this->center ) ) {
			$this->set_center( $lat, $lng );
		}
		$this->markers[] = [
			'title'       => $title,
			'description' => $description,
			'coordinates' => [ $lat, $lng ],
		];
		return $this;
	}

	/** Voegt marker op locatie toe */
	public function add_location_marker( string $location, string $title, string $description = '' ): self {
		if ( ! isset( $this->center ) ) {
			$this->set_location_center( $location );
		}
		$this->markers[] = [
			'title'       => $title,
			'description' => $description,
			'location'    => $location,
			'hash'        => $this->hash_location( $location ),
		];
		return $this;
	}

	/** Hash locatie (voor clientside caching van geocoding resultaten) */
	protected function hash_location( string $location ): string {
		return self::HASH_PREFIX . hash( self::HASH_ALGORITHM, $location );
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_register_script( self::get_assets_handle(), SIW_ASSETS_URL . 'js/elements/leaflet-map.js', [ Leaflet::get_assets_handle() ], SIW_PLUGIN_VERSION, true );

		wp_localize_script(
			self::get_assets_handle(),
			'siwLeafletMapData',
			[
				'geocodingUrl' => self::GEOCODING_URL,
				'tileLayer'    => [
					'urlTemplate' => self::TILESERVER_URL_TEMPLATE,
					'options'     => [
						'attribution' => '&copy; <a target="_blank" href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
					],
				],
			]
		);
		wp_enqueue_script( self::get_assets_handle() );
	}

	/** Voegt inline styling toe */
	public function enqueue_styles() {

		wp_register_style(
			self::get_assets_handle(),
			SIW_ASSETS_URL . 'css/elements/leaflet-map.css',
			[ Leaflet::get_assets_handle() ],
			SIW_PLUGIN_VERSION
		);

		wp_enqueue_style( self::get_assets_handle() );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/elements/leaflet-map.css' );
		$css = CSS::get_css_generator();
		$css->add_rule( "#{$this->get_element_id()}", [ 'height' => "{$this->height}px" ] );
		wp_add_inline_style( self::get_assets_handle(), $css->get_output() );
	}
}
