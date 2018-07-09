<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
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
add_action( 'pmxi_saved_post', function( $product_id, $xml_node, $is_update ) {
	/*Verwerk al beoordeelde projecten*/
	$product = wc_get_product( $product_id );
	$approval_result = $product->get_meta( 'approval_result' );
	if ( 'publish' != get_post_status( $product_id ) && ! empty( $approval_result ) ) {
		wp_publish_post( $product_id );
		if ( 'rejected' == $approval_result ) {
			siw_hide_workcamp( $post_id );
		}
	}
	//TODO: Kan dit niet weg ivm met pmxi_product_variation_saved ?
	siw_update_workcamp_tariff( $product_id );

	/*
	 * Corrigeren attributes
	 */
	//Startdatum
	$start_date = siw_get_workcamp_formatted_date( (string) $xml_node->start_date );
	wp_set_object_terms( $product_id, $start_date, 'pa_startdatum' );
	//Einddatum
	$end_date = siw_get_workcamp_formatted_date( (string) $xml_node->end_date );
	wp_set_object_terms( $product_id, $end_date, 'pa_einddatum');
	//Projectnaam
	$project_name = (string) $xml_node->name;
	wp_set_object_terms( $product_id, $project_name, 'pa_projectnaam' );
	//Projectcode
	$project_code = (string) $xml_node->code;
	wp_set_object_terms( $product_id, $project_code, 'pa_projectcode' );
	//Leeftijd
	$age = siw_get_workcamp_age_range( (string) $xml_node->min_age, (string) $xml_node->max_age );
	wp_set_object_terms( $product_id, $age, 'pa_leeftijd' );
	//Lokale bijdrage
	$local_fee = siw_get_workcamp_local_fee( (string) $xml_node->participation_fee, (string) $xml_node->participation_fee_currency );
	if ( ! empty ( $local_fee ) ) {
		wp_set_object_terms( $product_id, $local_fee, 'pa_lokale-bijdrage' );	
	}
	//Aantal vrijwilligers
	$volunteers = siw_get_workcamp_number_of_volunteers( (string) $xml_node->numvol, (string) $xml_node->numvol_m, (string) $xml_node->numvol_f );
	wp_set_object_terms( $product_id, $volunteers, 'pa_aantal-vrijwilligers' );	

}, 10, 3 );


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
