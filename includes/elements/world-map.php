<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Util\CSS;
use SIW\Data\Country;
use SIW\Data\Continent;

/**
 * Wereldkaart
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class World_Map extends Element {

	const STYLE_HANDLE = 'siw-world-map';

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

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'world-map';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'file'    => $this->map_file,
			'viewbox' => "0 0 {$this->width} {$this->height}",
		];
	}

	/** Zet land */
	public function set_country( Country $country ) : self {
		$this->country = $country;
		$this->continent = $country->get_continent();
		return $this;
	}

	/** Zet zoom-niveau */
	public function set_zoom( int $zoom ) : self {
		$this->zoom = $zoom;
		return $this;
	}

	/** Voegt SVG-script toe */
	protected function enqueue_scripts() {
		wp_enqueue_script( 'siw-svg' );
	}

	/** Voegt (inline) style toe */
	public function enqueue_styles() {
		$code = $this->country->get_iso_code();
		$inline_css = CSS::generate_inline_css(
			[
				"#{$this->get_element_id()} svg" => [
					'width' => '100%',
					'height' => 'auto',
				],
				"#{$this->get_element_id()} #{$code} path, #{$this->get_element_id()} path#{$code}" => [
					'fill' => $this->continent->get_color(),
				],
			]
		);
		wp_register_style( self::STYLE_HANDLE, false );
		wp_enqueue_style( self::STYLE_HANDLE );
		wp_add_inline_style( self::STYLE_HANDLE, $inline_css );
	}
}
