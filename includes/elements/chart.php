<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\HTML;

/**
 * Class om een chart te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @see       https://github.com/apexcharts/apexcharts.js
 */
abstract class Chart {
	
	/**
	 * Frappe Charts versie
	 * 
	 * @param string
	 */
	const FRAPPE_CHARTS_VERSION = '1.5.2';

	/**
	 * Type grafiek
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Data voor grafiek
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Opties voor grafiek
	 *
	 * @var array
	 */
	protected $options = [];

	/**
	 * Genereert grafiek
	 *
	 * @param array $data
	 * @param string $title
	 * @return string
	 */
	public function generate( array $data, array $options = [] ) {
		$this->data = $data;
		$this->options = wp_parse_args_recursive( $options, $this->options );

		$this->enqueue_scripts();
		$this->enqueue_styles();

		$attributes = [
			'id'           => uniqid( "siw-{$this->type}-chart-"),
			'class'        => 'siw-chart',
			'data-options' => $this->generate_chart_options(),
		];
		return HTML::div( $attributes ) ;
	}

	/**
	 * Voegt scripts toe
	 */
	protected function enqueue_scripts() {
		wp_register_script( 'frappe-charts', SIW_ASSETS_URL . 'modules/frappe-charts/frappe-charts.js', ['polyfill'], self::FRAPPE_CHARTS_VERSION, true );
		wp_register_script( 'siw-charts', SIW_ASSETS_URL . 'js/elements/siw-charts.js', ['frappe-charts'], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-charts' );
	}

	/**
	 * Voegt styles toe
	 */
	protected function enqueue_styles() {
		wp_register_style( 'frappe-charts', SIW_ASSETS_URL . 'modules/frappe-charts/frappe-charts.css', [], self::FRAPPE_CHARTS_VERSION );
		wp_enqueue_style( 'frappe-charts' );
	}

	/**
	 * Genereert opties voor grafiek
	 *
	 * @return array
	 */
	protected function generate_chart_options() : array {

		$options = wp_parse_args_recursive(
			$this->options,
			[
				'data'  => $this->generate_chart_data(),
				'type'  => $this->type,
			]
		);
		return $options;
	}

	/**
	 * Genereert data voor grafiek
	 */
	abstract protected function generate_chart_data() : array;
}
