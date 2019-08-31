<?php

/**
 * Class om een Apex-piechart te genereren
 * 
 * @package   SIW\Elements
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */
class SIW_Element_Pie_Chart extends SIW_Element_Chart {

	/**
	 * Breedte van grafiek
	 * 
	 * @var int
	 */
	const CHART_WIDTH = 480;

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
				'width' => self::CHART_WIDTH,
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