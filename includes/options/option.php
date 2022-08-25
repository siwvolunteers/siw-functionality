<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Interfaces\Options\Option as Option_Interface;

/**
 * Class om opties toe te voegen
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Option {

	/** Constructor */
	public function __construct( protected Option_Interface $option ) {
		add_filter( 'rwmb_meta_boxes', [ $this, 'add_settings_meta_boxes' ] );
		add_filter( 'mb_settings_pages', [ $this, 'add_settings_page' ] );
	}

	/** Voegt admin-pagina toe */
	public function add_settings_page( array $settings_pages ) : array {
		$tabs = $this->get_tabs();
		$settings_pages[] = [
			'option_name'   => 'siw_options',
			'id'            => "siw-{$this->option->get_id()}",
			'menu_title'    => "SIW - {$this->option->get_title()}",
			'capability'    => $this->option->get_capability(),
			'tabs'          => array_column( $tabs, null, 'id' ),
			'submit_button' => __( 'Opslaan', 'siw' ),
			'message'       => __( 'Instellingen opgeslagen', 'siw' ),
			'columns'       => 1,
			'tab_style'     => 'left',
			'parent'        => $this->option->get_parent_page(),
		];

		return $settings_pages;
	}

	/*** Voegt metaboxes toe */
	public function add_settings_meta_boxes( array $meta_boxes ) : array {

		$tabs = $this->get_tabs();
		$fields = $this->get_fields();

		foreach ( $tabs as $tab ) {
			$meta_boxes[] = [
				'id'             => "{$this->option->get_id()}_{$tab['id']}",
				'title'          => $tab['label'],
				'settings_pages' => "siw-{$this->option->get_id()}",
				'tab'            => $tab['id'],
				'fields'         => wp_list_filter( $fields, [ 'tab' => $tab['id'] ] ),
				'toggle_type'    => 'slide',
			];
		}
		return $meta_boxes;
	}

	/** Geeft tabs terug */
	protected function get_tabs(): array {
		return apply_filters( "siw_option_{$this->option->get_id()}_tabs", $this->option->get_tabs() );
	}

	/** Geeft velden terug */
	protected function get_fields(): array {
		return apply_filters( "siw_option_{$this->option->get_id()}_fields", $this->option->get_fields() );
	}

}
