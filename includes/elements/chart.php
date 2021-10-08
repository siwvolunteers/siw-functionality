<?php declare(strict_types=1);

namespace SIW\Elements;

/**
 * Class om een chart te genereren
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @see       https://github.com/apexcharts/apexcharts.js
 */
abstract class Chart extends Element {
	
	/** Frappe Charts versie */
	const FRAPPE_CHARTS_VERSION = '1.6.2';

	/** Type grafiek */
	protected string $type;

	/** Data voor grafiek */
	protected array $data = [];

	/** Opties voor grafiek */
	protected array $options = [];

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'chart';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'id'      => uniqid( "siw-{$this->type}-chart-"),
			'options' => $this->generate_chart_options(),
		];
	}

	/** Zet data voor grafiek */
	public function set_data( array $data ) : self {
		$this->data = $data;
		return $this;
	}

	/** Voegt scripts toe */
	protected function enqueue_scripts() {
		wp_register_script( 'frappe-charts', SIW_ASSETS_URL . 'vendor/frappe-charts/frappe-charts.min.umd.js', ['polyfill'], self::FRAPPE_CHARTS_VERSION, true );
		wp_register_script( 'siw-charts', SIW_ASSETS_URL . 'js/elements/siw-charts.js', ['frappe-charts'], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-charts' );
	}

	/** Genereert opties voor grafiek */
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

	/** Genereert data voor grafiek */
	abstract protected function generate_chart_data() : array;
}
