<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Properties;

/**
 * Overzichtspagina met configuratie
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Properties_Page {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'admin_menu', [ $self, 'add_properties_page'] ) ;
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
		?>
		<h2><?= esc_attr__( 'Eigenschappen', 'siw' ); ?></h2>

	<table class="wp-list-table widefat">
		<thead>
			<tr>
				<th class="row-title"><?= esc_attr__( 'Eigenschap', 'siw' ); ?></th>
				<th class="row-title"><?= esc_attr__( 'Waarde', 'siw' ); ?></th>
				<th class="row-title"><?= esc_attr__( 'Constante', 'siw' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th class="row-title"><?= esc_attr__( 'Eigenschap', 'siw' ); ?></th>
				<th class="row-title"><?= esc_attr__( 'Waarde', 'siw' ); ?></th>
				<th class="row-title"><?= esc_attr__( 'Constante', 'siw' ); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$configuration = Properties::get_all();
			$alternate = false;
			foreach ( $configuration as $item ) {
				$class = ( $alternate ) ? 'class = "alternate"' : ''; 
				printf('<tr %s><td>%s</td><td>%s</td><td>%s</td></tr>', $class, esc_html( $item['description'] ), esc_html( $item['value'] ), esc_html( $item['name'] ) );
				$alternate = ! $alternate;
			}
			?>
		</tbody>
	</table>
	<?php
	}
}
