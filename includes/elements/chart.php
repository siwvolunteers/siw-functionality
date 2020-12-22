<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\Core\Template;

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
	const FRAPPE_CHARTS_VERSION = '1.5.6';

	/**
	 * Type grafiek
	 */
	protected string $type;

	/**
	 * Data voor grafiek
	 */
	protected array $data = [];

	/**
	 * Opties voor grafiek
	 */
	protected array $options = [];

	/**
	 * Genereert grafiek
	 *
	 * @param array $data
	 * @param string $title
	 * @return string
	 */
	public function generate( array $data, array $options = [] ) : string {
		$this->data = $data;
		$this->options = wp_parse_args_recursive( $options, $this->options );

		$this->enqueue_scripts();
		$this->enqueue_styles();

		return Template::parse_template(
			'elements/chart',
			[
				'id'      => uniqid( "siw-{$this->type}-chart-"),
				'options' => $this->generate_chart_options(),
			]
		);
	}

	/**
	 * Voegt scripts toe
	 */
	protected function enqueue_scripts() {
		wp_register_script( 'frappe-charts', SIW_ASSETS_URL . 'vendor/frappe-charts/frappe-charts.min.iife.js', ['polyfill'], self::FRAPPE_CHARTS_VERSION, true );
		wp_register_script( 'siw-charts', SIW_ASSETS_URL . 'js/elements/siw-charts.js', ['frappe-charts'], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-charts' );
	}

	/**
	 * Voegt styles toe
	 */
	protected function enqueue_styles() {
		wp_register_style( 'frappe-charts', SIW_ASSETS_URL . 'vendor/frappe-charts/frappe-charts.min.css', [], self::FRAPPE_CHARTS_VERSION );
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
