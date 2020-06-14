<?php

namespace SIW\Elements\Charts;

use SIW\Elements\Chart;

/**
 * Class om een piechart te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Pie extends Chart {

	/**
	 * {@inheritDoc}
	 */
	protected $type = 'pie';

	/**
	 * {@inheritDoc}
	 */
	protected $options = [
		'height'          => 400,
		'truncateLegends' => true,
		'maxSlices'       => 7,
	];

	/**
	 * {@inheritDoc}
	 */
	protected function generate_chart_data() {

		$data = [
			'labels'   => wp_list_pluck( $this->data, 'label' ),
			'datasets' => [
				[
					'values' => wp_list_pluck( $this->data, 'value' ),
				]
			],
		];
		return $data;
	}
}
