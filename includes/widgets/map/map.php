<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements;

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
	protected string $widget_id ='map';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'location-alt';

	/**
	 * {@inheritDoc}
	 */
	protected bool $has_template = false;

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
				'options' => $this->get_maps(),
			],
		];
		return $widget_form;
	}

	/**
	 * Haalt kaarten op
	 *
	 * @return array
	 */
	protected function get_maps() : array {
		return wp_list_pluck( Elements::get_interactive_maps(), 'name', 'id' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_template_parameters( array $instance, array $args, array $template_vars, string $css_name ) : array {
		return [
			'content' => Elements::generate_interactive_map( $instance['map'] )
		];
	}
}