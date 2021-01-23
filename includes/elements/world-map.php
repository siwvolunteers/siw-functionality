<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;
use SIW\Util\CSS;
use SIW\Data\Country;
use SIW\Data\Continent;

/**
 * Wereldkaart
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class World_Map {

	/** Bestandsnaam van wereldkaart */
	protected $map_file = SIW_ASSETS_URL . 'images/maps/world.svg';

	/** Land */
	protected Country $country;

	/** Continent */
	protected Continent $continent;

	/** Zoom-niveau */
	protected int $zoom = 1;

	/** Breedte van SVG */
	protected float $width = 1200;

	/** Hoogte van SVG */
	protected float $height = 760;

	/** Constructor */
	public function __construct() {
		$this->enqueue_script();
	}

	/** Voegt SVG-script toe */
	protected function enqueue_script() {
		wp_enqueue_script( 'siw-svg' );
	}

	/** Voegt (inline) style toe */
	protected function enqueue_style() {
		$code = $this->country->get_world_map_data()->code;
		$inline_css = CSS::generate_inline_css(
			[
				'svg' => [
					'width' => '100%',
					'height' => 'auto',
				],
				"#{$code} path, path#{$code}" => [
					'fill' => $this->continent->get_color(),
				],
			]
		);
		wp_register_style( 'siw-world-map', false );
		wp_enqueue_style( 'siw-world-map' );
		wp_add_inline_style( 'siw-world-map', $inline_css );
	}

	/** Genereert kaart */
	public function generate( Country $country, int $zoom = 1 ) : string {
		$this->country = $country;
		$this->continent = $country->get_continent();
		$this->zoom = $zoom;

		$this->enqueue_style();
		
		return Template::parse_template(
			'elements/world-map',
			[
				'file'    => $this->map_file,
				'viewbox' => $this->get_viewbox(),
			]
		);
	}

	/** Bepaalt viewbox o.b.v. zoom en locatie land */
	protected function get_viewbox() : string {
		$x = $this->country->get_world_map_data()->x;
		$y = $this->country->get_world_map_data()->y;
	
		$x = $this->calculate_offset( $x ) * $this->width;
		$y = $this->calculate_offset( $y ) * $this->height;

		$vb_width = $this->width / $this->zoom;
		$vb_height = $this->height / $this->zoom;
		return "{$x} {$y} {$vb_width} {$vb_height}";
	}

	/** Berekent offset van coordinaat */
	protected function calculate_offset( float $coordinate ) : float {
		$coordinate = min( $coordinate + 1 / ( 2 * $this->zoom ), 1 );
		$coordinate = max( $coordinate - 1 / ( $this->zoom ), 0 );
		return $coordinate;
	}
}
