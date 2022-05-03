<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Elements\Table;
use SIW\Properties;

/**
 * Overzichtspagina met configuratie
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Properties_Page {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'admin_menu', [ $self, 'add_properties_page' ] );
	}

	/** Voegt adminpagina toe */
	public function add_properties_page() {
		add_management_page(
			__( 'SIW Eigenschappen', 'siw' ),
			__( 'SIW Eigenschappen', 'siw' ),
			'edit_posts',
			'siw-properties',
			[ $this, 'render_properties_page' ]
		);
	}

	/** Rendert de adminpagina */
	public function render_properties_page() {

		$properties = array_map(
			fn( array $property ) : array => [ $property['description'], $property['value'], $property['name'] ],
			Properties::get_all()
		);

		?>
		<h2><?php echo esc_attr__( 'Eigenschappen', 'siw' ); ?></h2>
		<?php
		Table::create()
			->set_table_class( 'wp-list-table widefat striped' )
			->set_header(
				[
					__( 'Eigenschap', 'siw' ),
					__( 'Waarde', 'siw' ),
					__( 'Constante', 'siw' ),
				]
			)
			->add_items( $properties )
			->set_footer(
				[
					__( 'Eigenschap', 'siw' ),
					__( 'Waarde', 'siw' ),
					__( 'Constante', 'siw' ),
				]
			)
			->render();
	}
}
