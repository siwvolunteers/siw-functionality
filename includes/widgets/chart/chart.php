<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met grafiek
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Chart
 * 
 * Widget Name: SIW: Grafiek
 * Description: Toont grafiek.
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class SIW_Chart_Widget extends SIW_Widget {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_id = 'chart';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_dashicon = 'chart-pie';

	/**
	 * {@inheritDoc}
	 */
	function __construct() {
		$this->widget_name = __( 'Grafiek', 'siw');
		$this->widget_description = __( 'Toont grafiek', 'siw' );
		$this->widget_fields = [
			'title' => [
				'type'      => 'text',
				'label'     => __( 'Titel', 'siw'),
				'default'   => __( 'Contact', 'siw' ),
			],
			'type' => [
				'type'    => 'radio',
				'label'   => __( 'Type grafiek', 'siw' ),
				'options' => [
					'pie'  => __( 'Taart', 'siw' ),
					'line' => __( 'Lijn', 'siw')
				]
			],
		];

		parent::__construct();
	}



	function __construct2() {

		$chart_types = [
			'pie'
		];
		
		parent::__construct(
			'siw-chart-widget',
			__( 'SIW: Grafiek', 'siw'),
			[
				'description'	=> __( 'Toont een grafiek.', 'siw' ),
				'panels_groups'	=> [ 'siw' ],
			],
			[],
			[
				'title' => [
					'type' => 'text',
					'label' => __( 'Titel', 'siw' ),
				],
				'type' => [
					'type'    => 'radio',
					'label'   => __( 'Type grafiek', 'siw' ),
					'options' => $chart_types
				],
				'panes' => [
					'type'          => 'repeater',
					'label'         => __( 'Data' , 'siw' ),
					'item_name'     => __( 'Waarde', 'siw' ),
					'item_label'    => [
						'selector'     => "[id*='title']",
						'update_event' => 'change',
						'value_method' => 'val'
					],
					'fields' => [
						'title' => [
							'type'	=> 'text',
							'label'	=> __( 'Titel', 'siw' )
						],
						'content' => [
							'type'				=> 'tinymce',
							'label'				=> __( 'Inhoud', 'siw' ),
							'rows'				=> 10,
							'default_editor'	=> 'html',
						],
					]
				],
			],
			plugin_dir_path(__FILE__)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( $instance, $args, $template_vars, $css_name ) { 
		$content = SIW\generate_chart( $instance['panes'] );
		return $content;
	}

}