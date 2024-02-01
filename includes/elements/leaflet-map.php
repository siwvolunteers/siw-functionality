<?php declare(strict_types=1);

namespace SIW\Elements;

use luizbills\CSS_Generator\Generator;
use SIW\External_Assets\Leaflet;

class Leaflet_Map extends Element {

	private const GEOCODING_URL = 'https://nominatim.openstreetmap.org/search';
	private const TILESERVER_URL_TEMPLATE = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png';
	private const HASH_ALGORITHM = 'sha256';
	private const HASH_PREFIX = 'siw_geocode_';

	protected int $height = 300;
	protected array $markers = [];
	protected array $center;
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

	protected function set_height( int $height ): self {
		$this->height = $height;
		return $this;
	}

	public function set_zoom( int $zoom ): self {
		$this->zoom = $zoom;
		return $this;
	}

	public function set_center( float $lat, float $lng ): self {
		$this->center = [ 'coordinates' => [ $lat, $lng ] ];
		return $this;
	}

	public function set_location_center( string $location ): self {
		$this->center = [
			'location' => $location,
			'hash'     => $this->hash_location( $location ),
		];
		return $this;
	}

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

	// Hash locatie (voor clientside caching van geocoding resultaten)
	protected function hash_location( string $location ): string {
		return self::HASH_PREFIX . hash( self::HASH_ALGORITHM, $location );
	}

	public function enqueue_scripts() {
		wp_register_script(
			self::get_asset_handle(),
			self::get_script_asset_url(),
			[ Leaflet::get_asset_handle() ],
			SIW_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			self::get_asset_handle(),
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
		wp_enqueue_script( self::get_asset_handle() );
	}

	public function enqueue_styles() {
		self::enqueue_class_style( [ Leaflet::get_asset_handle() ] );
		$css_generator = new Generator();
		$css_generator->add_rule( "#{$this->get_element_id()}", [ 'height' => "{$this->height}px" ] );
		wp_add_inline_style( self::get_asset_handle(), $css_generator->get_output() );
	}
}
