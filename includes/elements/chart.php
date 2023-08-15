<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\External_Assets\Frappe_Charts;
use SIW\External_Assets\Polyfill;

/**
 * Class om een chart te genereren
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Chart extends Element {

	const SCRIPT_HANDLE = 'siw-charts';

	const CHART_TYPE_LINE = 'line';
	const CHART_TYPE_BAR = 'bar';
	const CHART_TYPE_AXIS_MIXED = 'axis-mixed';
	const CHART_TYPE_SCATTER = 'scatter';
	const CHART_TYPE_PIE = 'pie';
	const CHART_TYPE_PERCENTAGE = 'percentage';
	const CHART_TYPE_HEATMAT = 'heatmap';

	/** Type grafiek */
	protected string $chart_type;

	protected ?string $title = null;

	protected array $colors = [];

	protected bool $animate = true;
	protected int $height = 400;
	protected bool $truncate_legends = true;
	protected int $max_slices = 7;
	protected array $tooltip_options = [];


	/** Data voor grafiek */
	protected array $labels = [];
	protected array $datasets = [];
	protected array $y_markers = [];
	protected array $y_regions = [];

	/** Opties voor grafiek */
	protected array $options = [];

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'chart';
	}

	public function set_chart_type( string $chart_type ): self {
		$this->chart_type = $chart_type;
		return $this;
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'options' => array_filter(
				[
					'data'             => [
						'labels'   => $this->labels,
						'datasets' => $this->datasets,
						'yMarkers' => $this->y_markers,
						'yRegions' => $this->y_regions,
					],
					'title'            => $this->title,
					'type'             => $this->chart_type,
					'animate'          => $this->animate,
					'height'           => $this->height,
					'colors'           => $this->colors,
					'truncateLegends'  => $this->truncate_legends,
					'maxSlices'        => $this->max_slices,
					'tooltipOptions'   => $this->tooltip_options,
					'axisOptions'      => [
						'xAxisMode' => 'span',
						'yAxisMode' => 'span',
						'xIsSeries' => false,
					],
					'barOptions'       => [
						'height'     => 20,
						'spaceRatio' => 0.5,
						'stacked'    => false,

					],
					'lineOptions'      => [
						'regionFill' => false,
						'hideDots'   => false,
						'hideLine'   => false,
						'heatline'   => false,
						'spline'     => false,
						'dotSize'    => 4,
					],
					'isNavigable'      => false,
					'valuesOverPoints' => false,
				],
			),
		];
	}

	public function set_labels( array $labels ): self {
		$this->labels = $labels;
		return $this;
	}

	public function add_dataset( array $values, string $name = null, string $chart_type = null ): self {
		$this->datasets[] = array_filter(
			[
				'name'      => $name,
				'chartType' => $chart_type,
				'values'    => $values,
			]
		);

		return $this;
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_register_script(
			self::SCRIPT_HANDLE,
			SIW_ASSETS_URL . 'js/elements/charts.js',
			[ Frappe_Charts::get_assets_handle(), Polyfill::get_assets_handle() ],
			SIW_PLUGIN_VERSION,
			true
		);
		wp_enqueue_script( self::SCRIPT_HANDLE );
	}

}
