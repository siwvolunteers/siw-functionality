<?php

namespace SIW\Elements;

/**
 * Class om een Apex-piechart te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Pie_Chart extends Chart {

	/**
	 * {@inheritDoc}
	 */
	protected $type = 'pie';

	/**
	 * {@inheritDoc}
	 */
	protected function generate_chart_options() {

		foreach ( $this->data as $item ) {
			$series[] = $item['value'];
			$labels[] = $item['label'];
		}

		$data = [
			'chart' => [
				'type'  => $this->type,
			],
			'labels' => $labels,
			'series' => $series,
			'legend' => [
				'fontFamily' => 'system-ui',
				'fontSize'   => '14px',
				'position'   => 'right',
			],
			'tooltip' => [
				'enabled' => true,
			],
			'responsive' => [
				[
					'breakpoint' => self::MOBILE_BREAKPOINT,
					'options' => [
						'chart' => [
							'width' => '100%',
						],
						'legend' => [
							'position' => 'bottom',
						],
					],
				]
			],
		];
		return  $data;
	}
}
