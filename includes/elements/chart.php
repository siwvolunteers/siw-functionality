<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\External_Assets\Frappe_Charts;
use SIW\External_Assets\Polyfill;

class Chart extends Element {

	public const CHART_TYPE_LINE = 'line';
	public const CHART_TYPE_BAR = 'bar';
	public const CHART_TYPE_AXIS_MIXED = 'axis-mixed';
	public const CHART_TYPE_SCATTER = 'scatter';
	public const CHART_TYPE_PIE = 'pie';
	public const CHART_TYPE_PERCENTAGE = 'percentage';
	public const CHART_TYPE_HEATMAT = 'heatmap';

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

	public function enqueue_scripts() {
		self::enqueue_class_script( [ Frappe_Charts::get_asset_handle(), Polyfill::get_asset_handle() ] );
	}
}
