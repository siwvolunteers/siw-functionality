<?php
/*
(c)2017-2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 ); //TODO: eventueel alleen als kortingsactie actief is
	add_filter( 'yith_wcan_untrailingslashit', '__return_false' );
	add_filter( 'yith_wcan_is_search', '__return_false' );
	add_filter( 'yith_wcan_hide_out_of_stock_items', '__return_true' );
	add_filter( 'yith_wcan_skip_layered_nav_query', '__return_false', 999 );
} );

/* Pinnacle sales badge verwijderen. TODO: kan weg na switch theme */
add_action( 'init', function() {
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 5 );
}, 999 );


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


/* Verberg AJAX-filter van land, doelgroep of maand op desbetreffende landingspagina */
add_filter( 'sidebars_widgets', function( $sidebars_widgets ) {
	global $wp_query;

	if ( ! isset( $wp_query ) ) {
		return $sidebars_widgets;
	}
	if ( is_tax( 'pa_land' ) || is_tax( 'pa_doelgroep' ) || is_tax( 'pa_maand' ) ) {
	
		global $pinnacle;
		$products_sidebar = $pinnacle['shop_cat_sidebar'];
		$products_widgets = $sidebars_widgets[ $products_sidebar ];
		
		$yith_widgets = get_transient( 'siw_yith_widgets' );
		if ( false == $yith_widgets ) {
			$widgets = get_option( 'widget_yith-woo-ajax-navigation' );
			foreach ( $widgets as $id => $widget ) {
				if ( isset( $widget['attribute'] ) ) {
					$yith_widgets[ 'pa_' . $widget['attribute'] ] = 'yith-woo-ajax-navigation-' . $id;
					set_transient( 'siw_yith_widgets', $yith_widgets, DAY_IN_SECONDS );
				}
			}
		}

		$taxonomy_slug = get_queried_object()->taxonomy;		
		$taxonomy_widget = isset( $yith_widgets[ $taxonomy_slug ] ) ? $yith_widgets[ $taxonomy_slug ] : '';

		if ( ( $index = array_search( $taxonomy_widget, $sidebars_widgets[ $products_sidebar ] ) ) !== false) {
			unset( $sidebars_widgets[ $products_sidebar ][ $index ] );
		}

	}
	return $sidebars_widgets;
});


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
	$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
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
	$workcamps_page_link = siw_get_translated_page_link( siw_get_setting( 'workcamps_page' ) );
	$contact_page_link = siw_get_translated_page_link( siw_get_setting( 'contact_page' ) );

	if ( is_shop() ) {
		$text =	__( 'Hieronder zie je het beschikbare aanbod Groepsprojecten.', 'siw' );
	}
	elseif ( is_product_category() ) {
		$category_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in %s.', 'siw' ), '<b>' . $category_name . '</b>' );
	}
	elseif ( is_tax( 'pa_land' ) ) {
		$country_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in %s.', 'siw' ), '<b>' . $country_name . '</b>' );
	}
	elseif ( is_tax( 'pa_soort-werk' ) ) {
		$work_type_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met werkzaamheden gericht op %s.', 'siw' ), '<b>' . strtolower( $work_type_name ) . '</b>' );
	}
	elseif ( is_tax( 'pa_doelgroep' ) ) {
		$target_audience_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten voor de doelgroep %s.', 'siw' ), '<b>' . strtolower( $target_audience_name ) . '</b>' );
	}
	elseif ( is_tax( 'pa_taal' ) ) {
		$language_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten met de voertaal %s.', 'siw' ), '<b>' . ucfirst( $language_name ) . '</b>' );
	}
	elseif ( is_tax( 'pa_maand' ) ) {
		$month_name = get_queried_object()->name;
		$text =	sprintf( __( 'Hieronder zie je het beschikbare aanbod Groepsprojecten in de maand %s.', 'siw' ), '<b>' . ucfirst( $month_name ) . '</b>' );
	}	

	if ( ! isset( $text ) ) {
		return;
	}

	/* Toon algemene uitleg over groepsprojecten */
	$text .= SPACE .
		__( 'Tijdens onze Groepsprojecten ga je samen met een internationale groep vrijwilligers voor 2 á 3 weken aan de slag.', 'siw' ) . SPACE .
		__( 'De projecten hebben vaste begin- en einddata.', 'siw' ) . SPACE .
		sprintf( __( 'We vertellen je meer over de werkwijze van deze projecten op onze pagina <a href="%s">Groepsprojecten</a>.', 'siw' ), esc_url( $workcamps_page_link ) );

	/* Toon aankondiging voor nieuwe projecten*/
	if ( siw_get_setting( 'workcamp_teaser_text_enabled' ) && date('Y-m-d') <= siw_get_setting( 'workcamp_teaser_text_end_date' ) ) {
		$teaser_text_end_year = date( 'Y', strtotime( siw_get_setting( 'workcamp_teaser_text_end_date' ) ) );
		$text .= BR2 . sprintf( __( 'Vanaf maart wordt het aanbod aangevuld met honderden nieuwe vrijwilligersprojecten voor %s.', 'siw' ), $teaser_text_end_year ). SPACE .
			__( 'Wil je nu al meer weten over de grensverleggende mogelijkheden van SIW?', 'siw' ) . SPACE .
			sprintf( __( '<a href="%s">Bel of mail ons</a> en we denken graag met je mee!', 'siw' ), esc_url( $contact_page_link ) );
	}

	/* Toon extra tekst als de kortingsactie actief is */
	if ( siw_is_sale_active() ) {
		/* Ophalen tarieven en einddatum */
		$tariffs = siw_get_workcamp_tariffs();
		$regular = siw_format_amount( $tariffs[ 'regulier' ] );
		$regular_sale = siw_format_amount( $tariffs[ 'regulier_aanbieding' ] );
		$student = siw_format_amount( $tariffs[ 'student' ] );
		$student_sale = siw_format_amount( $tariffs[ 'student_aanbieding' ] );
		$end_date = siw_get_date_in_text( siw_get_setting( 'workcamp_sale_end_date' ), false );

		$text .= BR2 . sprintf( __( 'Meld je nu aan en betaal geen %s maar %s voor je vrijwilligersproject.', 'siw' ), $regular, '<b>'. $regular_sale .'</b>' ) . SPACE .
			__( 'Ben je student of jonger dan 18 jaar?', 'siw' ) . SPACE .
			sprintf( __( 'Dan betaal je in plaats van %s nog maar %s.', 'siw' ), $student, '<b>'. $student_sale .'</b>' ) . BR  .
			'<b>' . __( 'Let op:', 'siw' ) . '</b>' . SPACE .
			sprintf( __( 'Deze actie duurt nog maar t/m %s, dus wees er snel bij.', 'siw' ), $end_date );
	}

?>
<div class="container">
	<div class="row woo-archive-intro">
		<div class="md-12">
			<?php echo wp_kses_post( $text ); ?>
		</div>
	</div>
</div>

<?php

});
