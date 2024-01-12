<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Interfaces\Options\Option as I_Option;

/**
 * Class om opties toe te voegen
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Option extends Base {

	/** Constructor */
	public function __construct( protected I_Option $option ) {}

	#[Add_Filter( 'mb_settings_pages' )]
	/** Voegt admin-pagina toe */
	public function add_settings_page( array $settings_pages ): array {
		$tabs = $this->option->get_tabs();
		$settings_pages[] = [
			'option_name'   => SIW_OPTIONS_KEY,
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

	#[Add_Filter( 'rwmb_meta_boxes' )]
	/*** Voegt metaboxes toe */
	public function add_settings_meta_boxes( array $meta_boxes ): array {

		$tabs = $this->option->get_tabs();
		$fields = $this->option->get_fields();

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
}
