<?php declare(strict_types=1);

namespace SIW\Page_Builder;

use SIW\Interfaces\Page_Builder\Style_Attributes as I_Style_Attributes;
use SIW\Interfaces\Page_Builder\Style_CSS as I_Style_CSS;
use SIW\Interfaces\Page_Builder\Style_Group as I_Style_Group;
use SIW\Interfaces\Page_Builder\Style_Fields as I_Style_Fields;
use SIW\Interfaces\Page_Builder\Settings as I_Settings;

/**
 * PB Extension Builder classe
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Builder {

	/** Voegt style group toe */
	public function add_style_group( I_Style_Group $extension ) {

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
	public function add_style_fields( I_Style_Fields $extension ) {
		if ( $extension->supports_widgets() ) {
			add_filter( 'siteorigin_panels_widget_style_fields', [ $extension, 'add_style_fields' ], 10, 3 );
		}

		if ( $extension->supports_cells() ) {
			add_filter( 'siteorigin_panels_cell_style_fields', [ $extension, 'add_style_fields' ], 10, 3 );
		}

		if ( $extension->supports_rows() ) {
			add_filter( 'siteorigin_panels_row_style_fields', [ $extension, 'add_style_fields' ], 10, 3 );
		}
	}

	/** Voegt style attributes toe */
	public function add_style_attributes( I_Style_Attributes $extension ) {
		if ( $extension->supports_widgets() ) {
			add_filter( 'siteorigin_panels_widget_style_attributes', [ $extension, 'set_style_attributes' ], 10, 2 );
		}

		if ( $extension->supports_cells() ) {
			add_filter( 'siteorigin_panels_cell_style_attributes', [ $extension, 'set_style_attributes' ], 10, 2 );
		}

		if ( $extension->supports_rows() ) {
			add_filter( 'siteorigin_panels_row_style_attributes', [ $extension, 'set_style_attributes' ], 10, 2 );
		}
	}

	/** Voegt CSS toe */
	public function add_style_css( I_Style_CSS $extension ) {

		if ( $extension->supports_widgets() ) {
			add_filter( 'siteorigin_panels_widget_style_css', [ $extension, 'set_style_css' ], 10, 2 );
		}

		if ( $extension->supports_cells() ) {
			add_filter( 'siteorigin_panels_cell_style_css', [ $extension, 'set_style_css' ], 10, 2 );
		}

		if ( $extension->supports_rows() ) {
			add_filter( 'siteorigin_panels_row_style_css', [ $extension, 'set_style_css' ], 10, 2 );
		}
	}


	/** Voeg settings toe */
	public function add_settings( I_Settings $extension ) {
		add_filter( 'siteorigin_panels_settings_fields', [ $extension, 'add_settings' ], 100 );
		add_filter( 'siteorigin_panels_settings_defaults', [ $extension, 'set_settings_defaults' ], 100 );
	}
}
