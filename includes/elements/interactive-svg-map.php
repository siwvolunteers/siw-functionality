<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\External_Assets\Jsvectormap;
use SIW\External_Assets\Jsvectormap_World_Map;
use SIW\Util\CSS;

/**
 * Interactive SVG-kaart
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Interactive_SVG_Map extends Element {

	/** TODO: enum voor kaarten*/
	public const MAP_WORLD = 'world';

	/** Hoogte van kaart in pixels */
	protected int $height = 300;

	protected string $map = self::MAP_WORLD;

	protected int $zoom_min = 1;
	protected int $zoom_max = 12;

	protected string $focus_region;

	protected array $regions = [];

	protected array $selected_regions = [];

	protected array $markers = [];

	protected array $selected_markers = [];

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'markers' => $this->markers,
			'regions' => $this->selected_regions,
			'options' => [
				'map'                  => $this->map,
				'draggable'            => true,
				'backgroundColor'      => 'transparent',
				'zoomButtons'          => true,
				'zoomOnScroll'         => true,
				'zoomOnScrollSpeed'    => 3,
				'zoomMax'              => $this->zoom_max,
				'zoomMin'              => $this->zoom_min,
				'zoomAnimate'          => true,
				'showTooltip'          => false,
				'zoomStep'             => 1.5,
				'bindTouchEvents'      => true,
				'selectedMarkers'      => $this->selected_markers,
				'markersSelectable'    => true,
				'markersSelectableOne' => true,
				'selectedRegions'      => $this->selected_regions,
				'regionsSelectable'    => false,
				'regionsSelectableOne' => true,
				'focusOn'              => isset( $this->focus_region ) ?
					[
						'region'  => strtoupper( $this->focus_region ),
						'animate' => true,
					] :
					[],
				'regionStyle'          => [
					'initial'       => [
						'fill' => '#ddd',
					],
					'hover'         => [],
					'selected'      => [
						'fill' => CSS::ACCENT_COLOR,
					],
					'selectedHover' => [],
				],
				'regionLabelStyle'     => [],
				'markerStyle'          => [
					'initial'       => [],
					'hover'         => [],
					'selected'      => [],
					'selectedHover' => [],
				],
				'markerLabelStyle'     => [
					'initial'       => [
						'fontFamily' => 'inherit',
					],
					'selected'      => [],
					'selectedHover' => [],
				],
			],
		];
	}

	/** {@inheritDoc} */
	public function enqueue_scripts() {
		wp_register_script(
			self::get_assets_handle(),
			SIW_ASSETS_URL . 'js/elements/interactive-svg-maps.js',
			[ Jsvectormap_World_Map::get_assets_handle() ],
			SIW_PLUGIN_VERSION,
			true
		);
		wp_enqueue_script( self::get_assets_handle() );
	}

	/** {@inheritDoc} */
	public function enqueue_styles() {
		wp_enqueue_style( Jsvectormap::get_assets_handle() );
		$css = CSS::get_css_generator();
		$css->add_rule( "#{$this->get_element_id()}", [ 'height' => "{$this->height}px" ] );
		wp_add_inline_style( Jsvectormap::get_assets_handle(), $css->get_output() );
	}

	public function set_map( string $map ): self {
		$this->map = $map;
		return $this;
	}

	public function select_region( string $region ): self {
		$this->selected_regions[] = strtoupper( $region );
		return $this;
	}

	public function set_focus_region( string $focus_region ): self {
		$this->focus_region = $focus_region;
		return $this;
	}

	public function add_marker( string $name, array $coordinates ): self {
		$this->markers[] = [
			'name'   => $name,
			'coords' => $coordinates,
		];
		return $this;
	}

	public function select_marker( string $name ): self {
		$markers = array_filter(
			$this->markers,
			fn( array $marker ): bool => $marker['name'] === $name
		);
		if ( ! empty( $markers ) ) {
			$this->selected_markers[] = array_key_first( $markers );
		}

		return $this;
	}

	public function set_zoom_min( int $zoom_min ): self {
		$this->zoom_min = $zoom_min;
		return $this;
	}

	public function set_zoom_max( int $zoom_max ): self {
		$this->zoom_max = $zoom_max;
		return $this;
	}
}

