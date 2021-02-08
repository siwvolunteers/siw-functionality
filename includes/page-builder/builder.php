<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Row_Style_Group as Row_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Row_Style_Fields as Row_Style_Fields_Interface;
use SIW\Interfaces\Page_Builder\Cell_Style_Group as Cell_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Cell_Style_Fields as Cell_Style_Fields_Interface;
use SIW\Interfaces\Page_Builder\Widget_Style_Group as Widget_Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Widget_Style_Fields as Widget_Style_Fields_Interface;
use SIW\Interfaces\Page_Builder\Settings as Settings_Interface;

/**
 * PB Extension Builder classe
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
class Builder {

	/** Voegt row style group toe */
	public function add_row_style_group( Row_Style_Group_Interface $extension ) {
		add_filter( 'siteorigin_panels_row_style_groups', [ $extension, 'add_style_group'] );
	}

	/** Voegt row style fields toe */
	public function add_row_style_fields( Row_Style_Fields_Interface $extension ) {
		add_filter( 'siteorigin_panels_row_style_fields', [ $extension, 'add_style_fields'] );
		add_filter( 'siteorigin_panels_row_style_attributes', [ $extension, 'set_style_attributes'], 10, 2 );
	}

	/** Voegt cell style group toe */
	public function add_cell_style_group( Cell_Style_Group_Interface $extension ) {
		add_filter( 'siteorigin_panels_cell_style_groups', [ $extension, 'add_style_group'] );
	}

	/** Voegt cell style fields toe */
	public function add_cell_style_fields( Cell_Style_Fields_Interface $extension ) {
		add_filter( 'siteorigin_panels_cell_style_fields', [ $extension, 'add_style_fields'] );
		add_filter( 'siteorigin_panels_cell_style_attributes', [ $extension, 'set_style_attributes'], 10, 2 );
	}

	/** Voegt widget style group toe */
	public function add_widget_style_group( Widget_Style_Group_Interface $extension ) {
		add_filter( 'siteorigin_panels_widget_style_groups', [ $extension, 'add_style_group'] );
	}

	/** Voegt widget style fields toe */
	public function add_widget_style_fields( Widget_Style_Fields_Interface $extension ) {
		add_filter( 'siteorigin_panels_widget_style_fields', [ $extension, 'add_style_fields'] );
		add_filter( 'siteorigin_panels_widget_style_attributes', [ $extension, 'set_style_attributes'], 10, 2 );
	}

	/** Voeg settings toe */
	public function add_settings( Settings_Interface $extension ) {
		add_filter( 'siteorigin_panels_settings_fields', [ $extension, 'add_settings' ], 100 );
		add_filter( 'siteorigin_panels_settings_defaults', [ $extension, 'set_settings_defaults' ], 100 );
	}
}