<?php

/**
 * Widget met grafiek
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Chart
 * 
 * @widget_data
 * Widget Name: SIW: Grafiek
 * Description: Toont grafiek.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class SIW_Widget_Pie_Chart extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_id = 'pie_chart';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_dashicon = 'chart-pie';

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
				'default'   => __( 'Contact', 'siw' ),
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
	protected function get_content( array $instance, array $args, array $template_vars, string $css_name ) { 
		$content = '';
		if ( isset( $instance['intro'] ) ) {
			$content .= wpautop( wp_kses_post( $instance['intro'] ) );
		}
		$chart = new SIW_Element_Pie_Chart();
		$content .= $chart->generate( $instance['series'] );

		if ( true == $instance['show_explanation'] ) {
			$explanation = array_map(
				function( $data ) {
					return '<b>' . $data['label'] . '</b>' . wpautop( $data['explanation'] );
				}, 
				$instance['series']
			);
			$content .= SIW_HTML::generate_list( $explanation );
		}

		return $content;
	}

}