<?php declare(strict_types=1);

namespace SIW\Elements\Charts;

use SIW\Elements\Chart;

/**
 * Class om een piechart te genereren
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class Pie extends Chart {

	/** {@inheritDoc} */
	protected string $type = 'pie';

	/** {@inheritDoc} */
	protected array $options = [
		'height'          => 400,
		'truncateLegends' => true,
		'maxSlices'       => 7,
		'tooltipOptions'  => [], // TODO: Verwijderen als deze opgelost is: https://github.com/frappe/charts/issues/314
	];

	/** {@inheritDoc} */
	protected function generate_chart_data() : array {

		$data = [
			'labels'   => wp_list_pluck( $this->data, 'label' ),
			'datasets' => [
				[
					'values' => wp_list_pluck( $this->data, 'value' ),
				],
			],
		];
		return $data;
	}
}
