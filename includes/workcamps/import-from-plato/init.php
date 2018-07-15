<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Classes*/
require_once( __DIR__ . '/class-siw-plato-import.php' );
require_once( __DIR__ . '/class-siw-plato-import-fpl.php' );



/*
 * Project_id toevoegen aan wc_query //TODO:verplaatsen
 */
add_filter( 'woocommerce_product_data_store_cpt_get_products_query', function( $query, $query_vars ) {
	if ( ! empty( $query_vars['project_id'] ) ) {
		$query['meta_query'][] = array(
			'key' => 'project_id',
			'value' => esc_attr( $query_vars['project_id'] ),
		);
	}
	return $query;
}, 10, 2 );
