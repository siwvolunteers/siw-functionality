<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Voeg prijzen voor tarieven (student/regulier) toe.
 * Verwerk reeds beoordeelde projecten
 */
add_action( 'pmxi_saved_post', function( $product_id ) {
	$tariff_array = array(
		'regulier'	=> number_format( SIW_WORKCAMP_FEE_REGULAR, 2 ),
		'student'	=> number_format( SIW_WORKCAMP_FEE_STUDENT, 2 )
	);

	$args = array(
		'post_type'		=> 'product_variation',
		'post_parent'	=> $product_id,
		'fields' 		=> 'ids'
	);
	$variations = get_posts( $args );
	foreach ( $variations as $variation_id ) {
		$tariff = get_post_meta( $variation_id, 'attribute_pa_tarief', true );
		$price = isset( $tariff_array[ $tariff ] ) ? $tariff_array[ $tariff ] : $tariff_array['regulier'];
		update_post_meta( $variation_id, '_regular_price', $price );
		update_post_meta( $variation_id, '_price', $price );
		update_post_meta( $variation_id, '_virtual', 'yes' );
	}

	/*Verwerk al beoordeelde projecten*/
	$approval_result = get_post_meta( $variation_id, 'approval_result', true );
	if ( 'publish' != get_post_status( $product_id ) && ! empty( $approval_result ) ) {
		wp_publish_post( $product_id );
		if ( 'rejected' == $approval_result ) {
			siw_hide_workcamp( $post_id );
		}
	}


}, 10, 1 );


/*
 * Zet vervolgacties klaar (alleen in het geval van de volledige import)
 * - Verbergen project
 * - Mail naar regiospecialisten over te beoordelen projecten
 * - Optie 'Forceer volledige update' weer uitzetten
 */
add_action( 'pmxi_after_xml_import', function( $import_id ) {
	$full_import_id = siw_get_setting( 'plato_full_import_id' );

	if ( $import_id == $full_import_id ) {
		wp_schedule_single_event( time() + ( 15 * MINUTE_IN_SECONDS ), 'siw_hide_workcamps' );
		wp_schedule_single_event( time() + ( 45 * MINUTE_IN_SECONDS ), 'siw_send_projects_for_approval_email' );
		//TODO: email voor nieuwe projecten
		if ( siw_get_setting( 'plato_force_full_update' ) ) {
			siw_set_setting( 'plato_force_full_update', 0);
		}
	}

}, 10, 1);
