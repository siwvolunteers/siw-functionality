<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Elements\Interactive_Map;

/**
 * Widget met interactieve kaart
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data
 * Widget Name: SIW: Interactieve kaart
 * Description: Toont interactieve kaart
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Map extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'map';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Interactieve kaart', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont interactieve kaart', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return 'default';
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'location-alt';
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw' ),
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

	/** Haalt kaarten op */
	protected function get_maps() : array {
		return wp_list_pluck( siw_get_interactive_maps(), 'name', 'id' );
	}

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {

		$maps = wp_list_pluck( siw_get_interactive_maps(), 'class', 'id' );
		if ( ! isset( $maps[ $instance['map'] ] ) ) {
			return [];
		}
		$interactive_map = new Interactive_Map( new $maps[ $instance['map'] ] );

		return [
			'content' => $interactive_map->generate(),
		];
	}
}
