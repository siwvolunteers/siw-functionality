<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Interfaces\Options\Option as Option_Interface;

/**
 * Class om opties toe te voegen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class Option {

	/**
	 * ID
	 */
	protected string $id;

	/**
	 * Titel
	 */
	protected string $title;

	/**
	 * Capability voor menu
	 */
	protected string $capability;

	/**
	 * Parent page voor optiemenu
	 */
	protected string $parent_page;

	/**
	 * Undocumented function
	 *
	 * @param Option_Interface $option
	 */
	public function __construct( Option_Interface $option ) {

		$this->id = $option->get_id();
		$this->title = $option->get_title();
		$this->capability = $option->get_capability();
		$this->parent_page = $option->get_parent_page();

		add_filter( 'rwmb_meta_boxes', [ $this, 'add_settings_meta_boxes'] );
		add_filter( 'mb_settings_pages', [ $this, 'add_settings_page'] );

		add_filter( "siw_option_{$this->id}_tabs", [ $option, 'get_tabs'] );
		add_filter( "siw_option_{$this->id}_fields", [ $option, 'get_fields' ] );
	}

	/**
	 * Voegt admin-pagina toe
	 *
	 * @param array $settings_pages
	 *
	 * @return array
	 */
	public function add_settings_page( array $settings_pages ) : array {
		$tabs = apply_filters( "siw_option_{$this->id}_tabs", [] );
		$settings_pages[] = [
			'option_name'   => 'siw_options',
			'id'            => "siw-{$this->id}",
			'menu_title'    => "SIW - {$this->title}",
			'capability'    => $this->capability,
			'tabs'          => array_column( $tabs , null, 'id' ),
			'submit_button' => __( 'Opslaan', 'siw' ),
			'message'       => __( 'Instellingen opgeslagen', 'siw' ),
			'columns'       => 1,
			'tab_style'     => 'left',
			'parent'        => $this->parent_page,
		];

		return $settings_pages;
	}
	
	/**
	 * Voegt metaboxes toe
	 *
	 * @param array $meta_boxes
	 *
	 * @return array
	 * 
	 * @todo validatie van veld naar metabox verplaatsen
	 */
	public function add_settings_meta_boxes( array $meta_boxes ) : array {
		
		$tabs = apply_filters( "siw_option_{$this->id}_tabs", [] );
		$fields = apply_filters( "siw_option_{$this->id}_fields", [] );

		foreach ( $tabs as $tab ) { 
			$meta_boxes[] = [
				'id'             => "{$this->id}_{$tab['id']}",
				'title'          => $tab['label'],
				'settings_pages' => "siw-{$this->id}",
				'tab'            => $tab['id'],
				'fields'         => wp_list_filter( $fields, [ 'tab' => $tab['id'] ] ),
				'toggle_type'    => 'slide',
			];
		}
		return $meta_boxes;
	}
}