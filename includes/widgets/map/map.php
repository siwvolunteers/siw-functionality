<?php

namespace SIW\Widgets;

/**
 * Widget met interactieve kaart
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Interactieve kaart
 * Description: Toont interactieve kaart
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Map extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected $widget_id ='map';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'location-alt';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Interactieve kaart', 'siw');
		$this->widget_description = __( 'Toont interactieve kaart', 'siw' );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
			],
			'map' => [
				'type'    => 'select',
				'label'   => __( 'Kaart', 'siw' ),
				'prompt'  => __( 'Kies een kaart', 'siw' ),
				'options' => siw_get_interactive_maps(),
			],
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_content( array $instance, array $args, array $template_vars, string $css_name ) {
		return siw_generate_interactive_map( $instance['map'] );
	}
}