<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Assets\Google_Maps as Google_Maps_Asset;
use SIW\Util\CSS;

/**
 * Google Maps kaart
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Google_Maps extends Element {

	const ASSETS_HANDLE = 'siw-google-maps';

	/** Hoogt van kaart in pixels */
	protected int $height = 300;

	/** Markers voor op kaart */
	protected array $markers = [];

	/** Center van kaart */
	protected string|array $center; // phpcs:ignore Squiz.Commenting.VariableComment.Missing

	/** Zoom-niveau */
	protected int $zoom = 6;

	/** Is zoom control actief */
	protected bool $zoom_control = true;

	/** Is map ty pe control actief */
	protected bool $map_type_control = false;

	/** Is scale control actief */
	protected bool $scale_control = false;

	/** Is street view control actief */
	protected bool $street_view_control = false;

	/** Is rotate control actief */
	protected bool $rotate_control = false;

	/** Is fullscreen control actief */
	protected bool $fullscreen_control = false;

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'google-maps';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'options' => [
				'center'            => $this->center,
				'zoom'              => $this->zoom,
				'zoomControl'       => $this->zoom_control,
				'mapTypeControl'    => $this->map_type_control,
				'scaleControl'      => $this->scale_control,
				'streetViewControl' => $this->street_view_control,
				'rotateControl'     => $this->rotate_control,
				'fullscreenControl' => $this->fullscreen_control,
			],
			'markers' => $this->markers,
		];
	}

	/** Zet hoogte van de kaart */
	protected function set_height( int $height ): self {
		$this->height = $height;
		return $this;
	}

	/** Zet zoom-niveau */
	public function set_zoom( int $zoom ): self {
		$this->zoom = $zoom;
		return $this;
	}

	/** Zet zoom-control */
	public function set_zoom_control( bool $zoom_control ): self {
		$this->zoom_control = $zoom_control;
		return $this;
	}

	/** Zet map type control */
	public function set_map_type_control( bool $map_type_control ): self {
		$this->map_type_control = $map_type_control;
		return $this;
	}

	/** Zet scale control */
	public function set_scale_control( bool $scale_control ): self {
		$this->scale_control = $scale_control;
		return $this;
	}

	/** Zet street view control */
	public function set_street_view_control( bool $street_view_control ): self {
		$this->street_view_control = $street_view_control;
		return $this;
	}

	/** Zet rotate control */
	public function set_rotate_control( bool $rotate_control ): self {
		$this->rotate_control = $rotate_control;
		return $this;
	}

	/** Zet fullscreen control */
	public function set_fullscreen_control( bool $fullscreen_control ): self {
		$this->fullscreen_control = $fullscreen_control;
		return $this;
	}

	/** Zet het midden van de kaart */
	public function set_center( float $lat, float $lng ): self {
		$this->center = [
			'lat' => $lat,
			'lng' => $lng,
		];
		return $this;
	}

	/** Zet het midden van de kaart op basis van een locatie */
	public function set_location_center( string $location ): self {
		$this->center = $location;
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
			'position'    => [
				'lat' => $lat,
				'lng' => $lng,
			],
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
			'position'    => $location,
		];
		return $this;
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_enqueue_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/elements/google-maps.js', [ Google_Maps_Asset::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
	}

	/** Voegt inline styling toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( self::ASSETS_HANDLE );

		$css = CSS::get_css_generator();
		$css->add_rule( "#{$this->get_element_id()}", [ 'height' => "{$this->height}px" ] );
		wp_add_inline_style( self::ASSETS_HANDLE, $css->get_output() );
	}
}
