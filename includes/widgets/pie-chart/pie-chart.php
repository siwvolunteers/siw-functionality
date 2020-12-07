<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements;
use SIW\Elements\Charts\Pie as Element_Pie_Chart;

/**
 * Widget met grafiek
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Grafiek
 * Description: Toont grafiek.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Pie_Chart extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id = 'pie_chart';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'chart-pie';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Taartgrafiek', 'siw' );
		$this->widget_description = __( 'Toont taartgrafiek', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'      => 'text',
				'label'     => __( 'Titel', 'siw'),
			],
			'intro' => [
				'type'           => 'tinymce',
				'label'          => __( 'Intro', 'siw' ),
				'rows'           => 10,
				'default_editor' => 'html',
			],
			'show_explanation' => [
				'type'          => 'checkbox',
				'label'         => __( 'Toon lijst met toelichting', 'siw' ),
				'default'       => false,
				'state_emitter' => [
					'callback'    => 'conditional',
					'args'        => [
						'explanation[show]: val',
						'explanation[hide]: ! val'
					],
				],
			],
			'series' => [
				'type'          => 'repeater',
				'label'         => __( 'Data' , 'siw' ),
				'item_name'     => __( 'Datapunt', 'siw' ),
				'item_label'    => [
					'selector'     => "[id*='label']",
					'update_event' => 'change',
					'value_method' => 'val'
				],
				'fields' => [
					'label' => [
						'type'  => 'text',
						'label' => __( 'Label', 'siw' )
					],
					'value' => [
						'type'    => 'number',
						'label'   => __( 'Waarde', 'siw' ),
					],
					'explanation' => [
						'type'           => 'tinymce',
						'label'          => __( 'Toelichting', 'siw' ),
						'rows'           => 10,
						'default_editor' => 'html',
						'state_handler' => [
							'explanation[show]' => [ 'show' ],
							'explanation[hide]' => [ 'hide' ],
						],
					]
				]
			],
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( array $instance, array $args, array $template_vars, string $css_name ) : string { 
		$content = '';
		if ( isset( $instance['intro'] ) ) {
			$content .= wpautop( wp_kses_post( $instance['intro'] ) );
		}
		$chart = new Element_Pie_Chart();
		$content .= $chart->generate( $instance['series'] ); //TODO: check of dit series wel gevuld is

		if ( $instance['show_explanation'] ) {
			$explanation = array_map(
				function( $data ) {
					return '<b>' . $data['label'] . '</b>' . wpautop( $data['explanation'] );
				}, 
				$instance['series']
			);
			$content .= Elements::generate_list( $explanation );
		}

		return $content;
	}
}
