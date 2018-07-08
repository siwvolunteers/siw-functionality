<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_action( 'admin_menu', function() {
	add_management_page( __( 'SIW: Configuratie', 'siw' ) , __( 'SIW: Configuratie', 'siw' ), 'manage_options', 'siw-configuration', 'siw_configuration_page' );
});


/**
 * Toon overzichtspagina met constantes
 *
 * @return void
 */
function siw_configuration_page() {
	?>
		<h2><?php esc_attr_e( 'Configuratie', 'siw' ); ?></h2>

	<table class="wp-list-table widefat">
	<thead>
		<tr>
			<th class="row-title"><?php esc_attr_e( 'Instelling', 'siw' ); ?></th>
			<th class="row-title"><?php esc_attr_e( 'Waarde', 'siw' ); ?></th>
			<th class="row-title"><?php esc_attr_e( 'Constante', 'siw' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="row-title"><?php esc_attr_e( 'Instelling', 'siw' ); ?></th>
			<th class="row-title"><?php esc_attr_e( 'Waarde', 'siw' ); ?></th>
			<th class="row-title"><?php esc_attr_e( 'Constante', 'siw' ); ?></th>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$constants = siw_get_constants();
	$alternate = false;
	foreach ( $constants as $constant => $name ) {
		$class = ( $alternate ) ? 'class = "alternate"' : ''; 
		echo sprintf('<tr %s><td>%s</td><td>%s</td><td>%s</td></tr>', $class, esc_html( $name ), esc_html( constant( $constant ) ), esc_html( $constant )  );
		$alternate = ! $alternate;
	}
	?>
	</tbody>
	</table>
	<?php
}