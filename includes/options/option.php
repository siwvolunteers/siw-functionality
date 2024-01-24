<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Attributes\Add_Filter;
use SIW\Base;

abstract class Option extends Base {

	final protected static function get_id(): string {
		$class_name_components = explode( '\\', static::class );
		return strtolower( end( $class_name_components ) );
	}

	abstract protected function get_title(): string;

	abstract protected function get_capability(): string;

	abstract protected function get_tabs(): array;

	abstract protected function get_parent_page();

	abstract protected function get_fields(): array;

	#[Add_Filter( 'mb_settings_pages' )]
	/** Voegt admin-pagina toe */
	final public function add_settings_page( array $settings_pages ): array {
		$tabs = $this->get_tabs();
		$settings_pages[] = [
			'option_name'   => SIW_OPTIONS_KEY,
			'id'            => "siw-{$this->get_id()}",
			'menu_title'    => "SIW - {$this->get_title()}",
			'capability'    => $this->get_capability(),
			'tabs'          => array_column( $tabs, null, 'id' ),
			'submit_button' => __( 'Opslaan', 'siw' ),
			'message'       => __( 'Instellingen opgeslagen', 'siw' ),
			'columns'       => 1,
			'tab_style'     => 'left',
			'parent'        => $this->get_parent_page(),
		];

		return $settings_pages;
	}

	#[Add_Filter( 'rwmb_meta_boxes' )]
	/*** Voegt metaboxes toe */
	final public function add_settings_meta_boxes( array $meta_boxes ): array {

		$tabs = $this->get_tabs();
		$fields = $this->get_fields();

		foreach ( $tabs as $tab ) {
			$meta_boxes[] = [
				'id'             => "{$this->get_id()}_{$tab['id']}",
				'title'          => $tab['label'],
				'settings_pages' => "siw-{$this->get_id()}",
				'tab'            => $tab['id'],
				'fields'         => wp_list_filter( $fields, [ 'tab' => $tab['id'] ] ),
				'toggle_type'    => 'slide',
			];
		}
		return $meta_boxes;
	}
}
