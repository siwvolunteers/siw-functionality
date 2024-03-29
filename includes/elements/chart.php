<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Data\Elements\Chart\Chart_Type;
use SIW\External_Assets\Frappe_Charts;
use SIW\External_Assets\Polyfill;

class Chart extends Element {

	protected Chart_Type $chart_type;
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

	public function set_chart_type( Chart_Type $chart_type ): self {
		$this->chart_type = $chart_type;
		return $this;
	}

	#[\Override]
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
					'type'             => $this->chart_type->value,
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

	public function add_dataset( array $values, string $name = null, Chart_Type $chart_type = null ): self {
		$this->datasets[] = array_filter(
			[
				'name'      => $name,
				'chartType' => $chart_type?->value,
				'values'    => $values,
			]
		);

		return $this;
	}

	#[\Override]
	public function enqueue_scripts() {
		self::enqueue_class_script( [ Frappe_Charts::get_asset_handle(), Polyfill::get_asset_handle() ] );
	}
}
