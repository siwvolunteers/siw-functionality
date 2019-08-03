<?php

use SVG\SVG;

/**
 * Wereldkaart
 * 
 * @package   SIW\Elements
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Country
 * @uses      SVG\SVG
 */
class SIW_Element_World_Map {

	/**
	 * Bestandsnaam van wereldkaart
	 *
	 * @var string
	 */
	protected $map_file = SIW_ASSETS_DIR . "/modules/mapplic/maps/world.svg";

	/**
	 * SVG
	 *
	 * @var SVG\SVG
	 */
	protected $svg;

	/**
	 * SVG-document
	 *
	 * @var SVG\SVGDocumentFragment
	 */
	protected $doc;

	/**
	 * Land
	 *
	 * @var SIW_Country
	 */
	protected $country;

	/**
	 * Continent
	 *
	 * @var SIW_Continent
	 */
	protected $continent;

	/**
	 * Zoom-niveau
	 *
	 * @var int
	 */
	protected $zoom = 1;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->svg = SVG::fromFile( $this->map_file );
		$this->doc = $this->svg->getDocument();
	}

	/**
	 * Genereert kaart
	 *
	 * @param string|SIW_Country $country
	 * @param int $zoom
	 * @return string
	 */
	public function generate( $country, int $zoom = 1 ) {
		if ( false == $this->set_country( $country ) ) {
			return false;
		}
		$this->zoom = $zoom;
		$this->set_styles();
		$this->get_viewport();
		$this->set_viewbox();
		
		return $this->svg;
	}

	/**
	 * Zet land om in te kleuren
	 *
	 * @param string|SIW_Country $country
	 * @return true
	 */
	protected function set_country( $country ) {
		if ( is_string( $country ) ) {
			$country = siw_get_country( $country );
		}
		if ( ! is_a( $country, 'SIW_Country') ) {
			return false;
		}
		$this->country = $country;
		$this->continent = $country->get_continent();
		return true;
	}

	/**
	 * Kleur het land in
	 * 
	 * @todo recursief maken
	 */
	protected function set_styles() {
		$path = $this->doc->getElementById( $this->country->get_world_map_data()->code );
		if ( is_a( $path, 'SVG\Nodes\Shapes\SVGPath') ) {
			$path->setStyle( 'fill', $this->continent->get_color() );
		}
		elseif ( is_a( $path, 'SVG\Nodes\Structures\SVGGroup' ) ) {
			$childCount = $path->countChildren();
			if ( $childCount > 0 ) {
				for ( $i = 0; $i < $childCount; $i++) {
					$subpath = $path->getChild( $i );
					$subpath->setStyle( 'fill', $this->continent->get_color() );
				}
			}
		}
	}

	/**
	 * Haal viewport van SVG op
	 */
	protected function get_viewport() {
		$this->width = floatval( $this->doc->getWidth() );
		$this->height = floatval( $this->doc->getHeight() );
	}

	/**
	 * Zet viewbox o.b.v. zoom en locatie land
	 * 
	 * @todo refactor
	 */
	protected function set_viewbox() {
		$x = $this->country->get_world_map_data()->x;
		$y = $this->country->get_world_map_data()->y;
	
		$x = $this->calculate_offset( $x ) * $this->width;
		$y = $this->calculate_offset( $y ) * $this->height;

		$vb_width = $this->width / $this->zoom;
		$vb_height = $this->height / $this->zoom;
		$this->doc->setAttribute( 'viewBox', "{$x} {$y} {$vb_width} {$vb_height}");
	}

	/**
	 * Berekent offset van coordinaat
	 *
	 * @param float $coordinate
	 * @return float
	 */
	protected function calculate_offset( float $coordinate ) {
		$coordinate = min( $coordinate + 1/ ( 2 * $this->zoom ), 1 );
		$coordinate = max( $coordinate - 1 / ( $this->zoom ), 0 );
		return $coordinate;
	}

}
