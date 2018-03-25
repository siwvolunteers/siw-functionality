<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Voeg prijzen voor tarieven (student/regulier) toe.
 */
add_action( 'pmxi_product_variation_saved', function( $variation_id ) {
	$tariff_array = siw_get_workcamp_tariffs();

	$variation = wc_get_product( $variation_id );
	$tariff = $variation->get_attributes()['pa_tarief'];
	$price = isset( $tariff_array[ $tariff ] ) ? $tariff_array[ $tariff ] : $tariff_array['regulier'];
	$variation->set_price( $price );
	$variation->set_regular_price( $price );
	$variation->set_virtual( 'yes' );
	$variation->save();
});


/*
 * Verwerk reeds beoordeelde projecten
 */
add_action( 'pmxi_saved_post', function( $product_id ) {
	/*Verwerk al beoordeelde projecten*/
	$product = wc_get_product( $product_id );
	$approval_result = $product->get_meta( 'approval_result' );
	if ( 'publish' != get_post_status( $product_id ) && ! empty( $approval_result ) ) {
		wp_publish_post( $product_id );
		if ( 'rejected' == $approval_result ) {
			siw_hide_workcamp( $post_id );
		}
	}
	//TODO: Kan weg na implementatie backgroup process
	$tariff_array = siw_get_workcamp_tariffs();
	$variations = $product->get_children();

	foreach ( $variations as $variation_id ) {
		$variation = wc_get_product( $variation_id );
		$tariff = $variation->get_attributes()['pa_tarief'];
		$price = isset( $tariff_array[ $tariff ] ) ? $tariff_array[ $tariff ] : $tariff_array['regulier'];
		$variation->set_price( $price );
		$variation->set_regular_price( $price );
		$variation->save();
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
		wp_schedule_single_event( time(), 'siw_hide_workcamps' );
		wp_schedule_single_event( time() + ( 15 * MINUTE_IN_SECONDS ), 'siw_send_projects_for_approval_email' );
		//TODO: email voor nieuwe projecten
		if ( siw_get_setting( 'plato_force_full_update' ) ) {
			siw_set_setting( 'plato_force_full_update', 0);
		}
	}

}, 10, 1);
