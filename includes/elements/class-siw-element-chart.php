<?php

/**
 * Class om een Apex-chart te genereren
 * 
 * @package   SIW\Elements
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */
abstract class SIW_Element_Chart {
	
	/**
	 * Apex Charts versie
	 * 
	 * @param string
	 */
	const APEX_CHARTS_VERSION = '3.8.5';

	/**
	 * Breakpoint voor responsive behaviour
	 */
	const MOBILE_BREAKPOINT = '1024';

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
	 * Undocumented variable
	 *
	 * @var array
	 */
	protected $series = [];

	/**
	 * Genereert grafiek
	 *
	 * @param array $data
	 * @param array $options
	 * @return string
	 */
	public function generate( array $data, array $options = [] ) {
		$this->data = $data;
		$this->options = $options;
		
		$this->enqueue_scripts();

		$attributes = [
			'id'           => uniqid( "siw-{$this->type}-chart-"),
			'class'        => 'siw-chart',
			'data-options' => $this->generate_chart_options(),
		];
		return SIW_Formatting::generate_tag( 'div', $attributes ) . '</div>' ;
	}

	/**
	 * Voegt scripts toe
	 */
	protected function enqueue_scripts() {
		wp_register_script( 'apex-charts', SIW_ASSETS_URL . 'modules/apexcharts/apexcharts.min.js', [], self::APEX_CHARTS_VERSION, true );
		wp_register_script( 'siw-charts', SIW_ASSETS_URL . 'js/siw-charts.js', [ 'apex-charts' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-charts' );
	}

	/**
	 * Genereert data voor grafiek
	 */
	abstract protected function generate_chart_options();
}