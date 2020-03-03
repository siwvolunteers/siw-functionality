<?php

namespace SIW\Elements;

use SIW\HTML;

/**
 * Class om een Apex-chart te genereren
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @see       https://github.com/apexcharts/apexcharts.js
 */
abstract class Chart {
	
	/**
	 * Apex Charts versie
	 * 
	 * @param string
	 */
	const APEX_CHARTS_VERSION = '3.16.0';

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

		add_filter( 'rocket_exclude_js', [ $this, 'set_excluded_js' ] );
		
		$this->enqueue_scripts();

		$attributes = [
			'id'           => uniqid( "siw-{$this->type}-chart-"),
			'class'        => 'siw-chart',
			'data-options' => $this->generate_chart_options(),
		];
		return HTML::generate_tag( 'div', $attributes, null, true ) ;
	}

	/**
	 * JS-bestanden uitsluiten van minification/concatenation
	 *
	 * @param array $excluded_files
	 * @return array
	 */
	public function set_excluded_js( array $excluded_files ) {
		$excluded_files[] = '/wp-content/plugins/siw-functionality/assets/modules/apexcharts/apexcharts.js';
		return $excluded_files;
	}

	/**
	 * Voegt scripts toe
	 */
	protected function enqueue_scripts() {
		wp_register_script( 'apex-charts', SIW_ASSETS_URL . 'modules/apexcharts/apexcharts.js', [], self::APEX_CHARTS_VERSION, true );
		wp_register_script( 'siw-charts', SIW_ASSETS_URL . 'js/elements/siw-charts.js', [ 'apex-charts' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-charts' );
	}

	/**
	 * Genereert data voor grafiek
	 */
	abstract protected function generate_chart_options();
}
