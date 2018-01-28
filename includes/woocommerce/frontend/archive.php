<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * Aanpassen diverse woocommerce-hooks voor archive
 * - Prijs verbergen
 * - Add to cart verbergen
 * - AJAX-filtering ook op zoekresultaten-pagina
 * - Trailing slash toevoegen bij AJAX-filtering
*/
add_action( 'plugins_loaded', function() {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	add_filter( 'yith_wcan_untrailingslashit', '__return_false' );
	add_filter( 'yith_wcan_is_search', '__return_false' );
	add_filter( 'yith_wcan_hide_out_of_stock_items', '__return_true' );
	add_filter( 'yith_wcan_skip_layered_nav_query', '__return_false', 999 );
} );


/* AJAX-filtering: Maanden niet filteren op alfabet maar op slug*/
add_filter( 'yith_wcan_get_terms_list', function ( $terms, $taxonomy, $instance ) {
	if ( 'pa_maand' != $taxonomy ) {
		return $terms;
	}
	foreach ( $terms as $index=>$term ) {
		$ordered_term_indices[ $index ] = $term->slug;
	}
	asort( $ordered_term_indices, SORT_STRING );
	$order = array_keys( $ordered_term_indices );

	uksort( $terms, function( $key1, $key2 ) use ( $order ) {
		return ( array_search( $key1, $order ) > array_search( $key2, $order ) );
	} );

	return $terms;
}, 10, 3 );


/*
 * Velden toevoegen aan product in productoverzichten
 * - Datums
 * - Projectcode
*/
add_action( 'woocommerce_after_shop_loop_item_title', function() {
	global $product;
	$start_date = $product->get_attribute( 'startdatum' );
	$end_date = $product->get_attribute( 'einddatum' );
	$duration = siw_get_date_range_in_text( $start_date, $end_date, false );
	$project_code = $product->get_sku();
	//TODO: inline styling verplaatsen naar css
	echo '<p>' . esc_html( $duration ) . '</p><hr style="margin:5px;">';
	echo '<p style="margin-bottom:5px;"><small>' . esc_html( $project_code ) . '</small></p>';
}, 1 );


/*
 * Voeg extra sorteeropties toe:
 * - Random
 * - Land
 * - Start datum
*/
add_filter( 'woocommerce_get_catalog_ordering_args', function( $args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	switch ( $orderby_value ) {
		case 'random':
			$sort_args['orderby']	= 'rand';
			$sort_args['order']		= '';
			$sort_args['meta_key']	= '';
			break;
		case 'startdate':
			$sort_args['orderby']	= 'meta_value';
			$sort_args['order']		= 'asc';
			$sort_args['meta_key']	= 'startdatum';
			break;
		case 'country':
			$sort_args['orderby']	= 'meta_value';
			$sort_args['order']		= 'asc';
			$sort_args['meta_key']	= 'land';
			break;
	}
	return $sort_args;
} );

add_filters( array( 'woocommerce_default_catalog_orderby_options', 'woocommerce_catalog_orderby' ), function() {
	$orderby['startdate']	= __( 'Startdatum', 'siw' );
	$orderby['country']		= __( 'Land', 'siw' );
	$orderby['random']		= __( 'Willekeurig', 'siw' );

	return $orderby;
} );


/*
 * Voeg extra sorteeropties toe aan WooCommerce shortcodes:
 * - Random
*/
add_filter( 'woocommerce_shortcode_products_query', function( $args, $atts ) {
	if ( 'random' == $atts['orderby'] ) {
		$args['orderby']  = 'rand';
		$args['order']    = '';
		$args['meta_key'] = '';
	}
	return $args;
	return $atts;
}, 10, 2 );


/** Introtekst voor overzichtspagina toevoegen*/
add_action( 'after_page_header', function() {
	$workcamps_page = siw_get_setting( 'workcamps_page' );
	$workcamps_page_link = siw_get_translated_page_link( $workcamps_page );

	if ( is_shop() ) {
		$text =	__( 'Hieronder zie je het beschikbare aanbod groepsprojecten.', 'siw' );

	}

	if ( is_product_category() ) {
		$category_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod groepsprojecten in %s.', 'siw' ), '<b>' . $category_name . '</b>' );

	};

	if ( is_tax( 'pa_land' ) ) {
		$country_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod groepsprojecten in %s.', 'siw' ), '<b>' . $country_name . '</b>' );
	}

	if ( is_tax( 'pa_soort-werk' ) ) {
		$work_type_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod groepsprojecten met werkzaamheden gericht op %s.', 'siw' ), '<b>' . strtolower( $work_type_name ) . '</b>' );
	}

	if ( is_tax( 'pa_doelgroep' ) ) {
		$target_audience_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod groepsprojecten voor de doelgroep %s.', 'siw' ), '<b>' . strtolower( $target_audience_name ) . '</b>' );
	}

	if ( isset( $text ) ) {
		$text .= SPACE .
			__( 'Tijdens onze groepsprojecten ga je samen met een internationale groep vrijwilligers voor 2 á 3 weken aan de slag.', 'siw' ) . SPACE .
			__( 'De projecten hebben vaste begin- en einddata.', 'siw' ) . SPACE .
		 	sprintf( __( 'We vertellen je meer over de werkwijze van deze projecten op onze pagina <a href="%s">groepsprojecten</a>.', 'siw' ), esc_url( $workcamps_page_link ) );


	?>
	<div class="container">
		<div class="row woo-archive-intro">
			<div class="md-12">
				<?php echo wp_kses_post( $text ); ?>
			</div>
		</div>
	</div>

<?php
	}
});
