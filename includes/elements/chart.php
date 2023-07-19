<?php declare(strict_types=1);

namespace SIW\Elements;

use SIW\External_Assets\Frappe_Charts;
use SIW\External_Assets\Polyfill;

/**
 * Class om een chart te genereren
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
abstract class Chart extends Element {

	const SCRIPT_HANDLE = 'siw-charts';

	/** Type grafiek */
	protected string $type;

	/** Data voor grafiek */
	protected array $data = [];

	/** Opties voor grafiek */
	protected array $options = [];

	/** {@inheritDoc} */
	protected static function get_type(): string {
		return 'chart';
	}

	/** {@inheritDoc} */
	protected function get_template_variables(): array {
		return [
			'options' => $this->generate_chart_options(),
		];
	}

	/** Zet data voor grafiek */
	public function set_data( array $data ): static {
		$this->data = $data;
		return $this;
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_register_script( self::SCRIPT_HANDLE, SIW_ASSETS_URL . 'js/elements/charts.js', [ Frappe_Charts::get_assets_handle(), Polyfill::get_assets_handle() ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( self::SCRIPT_HANDLE );
	}

	/** Genereert opties voor grafiek */
	protected function generate_chart_options(): array {

		$options = wp_parse_args_recursive(
			$this->options,
			[
				'data' => $this->generate_chart_data(),
				'type' => $this->type,
			]
		);
		return $options;
	}

	/** Genereert data voor grafiek */
	abstract protected function generate_chart_data(): array;
}
