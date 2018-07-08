<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Volgorde en naam van attribute pa_month aanpassen */
siw_add_cron_job( 'siw_reorder_rename_product_attribute_month' );

add_action( 'siw_reorder_rename_product_attribute_month', function() {
	siw_debug_log( 'Start herordenen attribute maand' );

	$terms = get_terms( 'pa_maand', array(
		'hide_empty' => false,
		)
	);
	$ordered_terms = array();
	foreach ( $terms as $term ) {
		$ordered_terms[ $term->term_id ] = $term->slug;
	}
	//oplopend sorteren op slug
	asort( $ordered_terms, SORT_STRING );

	$order = 0;
	foreach ( $ordered_terms as $term_id => $term_slug ) {
		$name = siw_get_month_name_from_slug( $term_slug );

		//naam aanpassen
		wp_update_term( $term_id, 'pa_maand', array(
			'name' => $name,
		));
		$order++;
		//Volgorde bijwerken
		update_term_meta( $term_id, 'order_pa_maand', $order );
	}
	siw_debug_log( 'Eind herordenen attribute maand' );
});







