<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/*
 * Extra functies voor zoeken op sku (projectcode)
 */
add_action( 'pre_get_posts', function( $q ) {
	if ( is_search() ) {
		add_filter( 'posts_join', 'siw_custom_search_join' );
		add_filter( 'posts_where', 'siw_custom_search_where' );
		add_filter( 'posts_distinct', 'siw_custom_search_distinct' );
	}
} );

function siw_custom_search_join( $join = '' ) {
	global $wp_the_query;

	/* Alleen voor woocommerce query */
	if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) ){
		return $join;
	}
	$join .= "INNER JOIN wp_postmeta AS pm_sku ON (wp_posts.ID = pm_sku.post_id AND '_sku' = pm_sku.meta_key )";
	return $join;
}

function siw_custom_search_where( $where = '' ) {
	global $wp_the_query;

	/* alleen voor woocommerce query */
	if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) ) {
		return $where;
	}

	$where = preg_replace("/post_title LIKE ('%[^%]+%')/", "post_title LIKE $1)
		OR (CAST(pm_sku.meta_value AS CHAR) LIKE $1 ", $where );

	return $where;
}

function siw_custom_search_distinct() {
	return "DISTINCT";
}
