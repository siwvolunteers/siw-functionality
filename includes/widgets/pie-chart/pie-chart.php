<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Animation\Chart_Type;
use SIW\Elements\Chart;

/**
 * Widget Name: SIW: Grafiek
 * Description: Toont grafiek.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Pie_Chart extends Widget {

	#[\Override]
	protected function get_id(): string {
		return 'pie_chart';
	}

	#[\Override]
	protected function get_name(): string {
		return __( 'Taartgrafiek', 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( 'Toont taartgrafiek', 'siw' );
	}

	#[\Override]
	protected function get_template_id(): string {
		return $this->get_id();
	}

	#[\Override]
	protected function get_dashicon(): string {
		return 'chart-pie';
	}

	#[\Override]
	protected function supports_title(): bool {
		return true;
	}

	#[\Override]
	protected function supports_intro(): bool {
		return true;
	}

	#[\Override]
	public function get_widget_fields(): array {
		$widget_fields = [
			'show_explanation' => [
				'type'          => 'checkbox',
				'label'         => __( 'Toon lijst met toelichting', 'siw' ),
				'default'       => false,
				'state_emitter' => [
					'callback' => 'conditional',
					'args'     => [
						'explanation[show]: val',
						'explanation[hide]: ! val',
					],
				],
			],
			'series'           => [
				'type'       => 'repeater',
				'label'      => __( 'Data', 'siw' ),
				'item_name'  => __( 'Datapunt', 'siw' ),
				'item_label' => [
					'selector'     => "[id*='label']",
					'update_event' => 'change',
					'value_method' => 'val',
				],
				'fields'     => [
					'label'       => [
						'type'  => 'text',
						'label' => __( 'Label', 'siw' ),
					],
					'value'       => [
						'type'  => 'number',
						'label' => __( 'Waarde', 'siw' ),
					],
					'explanation' => [
						'type'           => 'tinymce',
						'label'          => __( 'Toelichting', 'siw' ),
						'rows'           => 10,
						'default_editor' => 'html',
						'state_handler'  => [
							'explanation[show]' => [ 'show' ],
							'explanation[hide]' => [ 'hide' ],
						],
					],
				],
			],
		];
		return $widget_fields;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {

		if ( ! isset( $instance['series'] ) || empty( $instance['series'] ) ) {
			return [];
		}

		return [
			'chart'            => Chart::create()
				->set_chart_type( Chart_Type::PIE )
				->set_labels( wp_list_pluck( $instance['series'], 'label' ) )
				->add_dataset( wp_list_pluck( $instance['series'], 'value' ) )
				->generate(),
			'series'           => $instance['series'],
			'show_explanation' => $instance['show_explanation'],
		];
	}
}
