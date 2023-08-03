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
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'location-alt';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function get_widget_fields(): array {
		$widget_fields = [
			'map' => [
				'type'    => 'select',
				'label'   => __( 'Kaart', 'siw' ),
				'prompt'  => __( 'Kies een kaart', 'siw' ),
				'options' => $this->get_maps(),
			],
		];
		return $widget_fields;
	}

	/** Haalt kaarten op */
	protected function get_maps(): array {
		return wp_list_pluck( siw_get_interactive_maps(), 'name', 'id' );
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {

		$maps = wp_list_pluck( siw_get_interactive_maps(), 'class', 'id' );
		if ( ! isset( $maps[ $instance['map'] ] ) ) {
			return [];
		}
		$interactive_map = new $maps[ $instance['map'] ]();

		return [
			'content' => Interactive_Map::create()->set_interactive_map( $interactive_map )->generate(),
		];
	}
}
