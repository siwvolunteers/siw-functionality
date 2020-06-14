<?php

namespace SIW\Elements;

use SIW\Util\CSS;
use SIW\Data\Country;
use SIW\Data\Continent;
use SIW\HTML;

/**
 * Wereldkaart
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class World_Map {

	/**
	 * Bestandsnaam van wereldkaart
	 *
	 * @var string
	 */
	protected $map_file = SIW_ASSETS_URL . 'images/maps/world.svg';

	/**
	 * Land
	 *
	 * @var Country
	 */
	protected $country;

	/**
	 * Continent
	 *
	 * @var Continent
	 */
	protected $continent;

	/**
	 * Zoom-niveau
	 *
	 * @var int
	 */
	protected $zoom = 1;

	/**
	 * Breedte van SVG
	 *
	 * @var float
	 */
	protected $width = 1200;

	/**
	 * Hoogte van SVG
	 *
	 * @var float
	 */
	protected $height = 760;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->enqueue_script();
	}

	/**
	 * Voegt SVG-script toe
	 */
	protected function enqueue_script() {
		wp_enqueue_script( 'siw-svg' );
	}

	/**
	 * Voegt (inline) style toe
	 */
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

	/**
	 * Genereert kaart
	 *
	 * @param string|Country $country
	 * @param int $zoom
	 * @return string
	 */
	public function generate( $country, int $zoom = 1 ) {
		if ( false === $this->set_country( $country ) ) {
			return false;
		}
		$this->zoom = $zoom;
		$this->enqueue_style();
		
		$div = HTML::div(
			[
				'data-svg-url' => $this->map_file,
				'style'        => 'display:none;',
			]
		);
		$svg = HTML::svg(
			[ 'viewBox' => $this->get_viewbox() ],
			'<use xlink:href="#mapplic-world"></use>'
		);

		return $div . $svg;
	}

	/**
	 * Zet land om in te kleuren
	 *
	 * @param string|Country $country
	 * @return true
	 */
	protected function set_country( $country ) {
		if ( is_string( $country ) ) {
			$country = siw_get_country( $country );
		}
		if ( ! is_a( $country, '\SIW\Data\Country') ) {
			return false;
		}
		$this->country = $country;
		$this->continent = $country->get_continent();
		return true;
	}

	/**
	 * Bepaalt viewbox o.b.v. zoom en locatie land
	 * 
	 * @todo refactor
	 */
	protected function get_viewbox() {
		$x = $this->country->get_world_map_data()->x;
		$y = $this->country->get_world_map_data()->y;
	
		$x = $this->calculate_offset( $x ) * $this->width;
		$y = $this->calculate_offset( $y ) * $this->height;

		$vb_width = $this->width / $this->zoom;
		$vb_height = $this->height / $this->zoom;
		return "{$x} {$y} {$vb_width} {$vb_height}";
	}

	/**
	 * Berekent offset van coordinaat
	 *
	 * @param float $coordinate
	 * @return float
	 */
	protected function calculate_offset( float $coordinate ) {
		$coordinate = min( $coordinate + 1 / ( 2 * $this->zoom ), 1 );
		$coordinate = max( $coordinate - 1 / ( $this->zoom ), 0 );
		return $coordinate;
	}
}
