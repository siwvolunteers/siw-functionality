<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Style_Group as Style_Group_Interface;
use SIW\Interfaces\Page_Builder\Style_Fields as Style_Fields_Interface;

use SIW\Interfaces\Page_Builder\Settings as Settings_Interface;

/**
 * PB Extension Builder classe
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Builder {

	/** Voegt style group toe */
	public function add_style_group( Style_Group_Interface $extension ) {

		if ( $extension->supports_widgets() ) {
			add_filter( 'siteorigin_panels_widget_style_groups', [ $extension, 'add_style_group' ], 10, 3 );
		}

		if ( $extension->supports_cells() ) {
			add_filter( 'siteorigin_panels_cell_style_groups', [ $extension, 'add_style_group' ], 10, 3 );
		}

		if ( $extension->supports_rows() ) {
			add_filter( 'siteorigin_panels_row_style_groups', [ $extension, 'add_style_group' ], 10, 3 );
		}
	}

	/** Voegt style fields toe */
	public function add_style_fields( Style_Fields_Interface $extension ) {
		if ( $extension->supports_widgets() ) {
			add_filter( 'siteorigin_panels_widget_style_fields', [ $extension, 'add_style_fields' ], 10, 3 );
			add_filter( 'siteorigin_panels_widget_style_attributes', [ $extension, 'set_style_attributes' ], 10, 2 );
		}

		if ( $extension->supports_cells() ) {
			add_filter( 'siteorigin_panels_cell_style_fields', [ $extension, 'add_style_fields' ], 10, 3 );
			add_filter( 'siteorigin_panels_cell_style_attributes', [ $extension, 'set_style_attributes' ], 10, 2 );
		}

		if ( $extension->supports_rows() ) {
			add_filter( 'siteorigin_panels_row_style_fields', [ $extension, 'add_style_fields' ], 10, 3 );
			add_filter( 'siteorigin_panels_row_style_attributes', [ $extension, 'set_style_attributes' ], 10, 2 );
		}
	}

	/** Voeg settings toe */
	public function add_settings( Settings_Interface $extension ) {
		add_filter( 'siteorigin_panels_settings_fields', [ $extension, 'add_settings' ], 100 );
		add_filter( 'siteorigin_panels_settings_defaults', [ $extension, 'set_settings_defaults' ], 100 );
	}
}
