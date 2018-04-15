<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Verwijderen ongebruikte terms */
siw_add_cron_job( 'siw_cleanup_terms' );

add_action( 'siw_cleanup_terms', function() {
	siw_debug_log( 'Start verwijderen ongebruikte terms');
	$taxonomies[] = 'pa_maand';
	$taxonomies[] = 'pa_aantal-vrijwilligers';
	$taxonomies[] = 'pa_leeftijd';
	$taxonomies[] = 'pa_lokale-bijdrage';
	$taxonomies[] = 'pa_projectcode';
	$taxonomies[] = 'pa_projectnaam';
	$taxonomies[] = 'pa_startdatum';
	$taxonomies[] = 'pa_einddatum';

	$deleted_terms = 0;

	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_terms( $taxonomy, array(
			'hide_empty' => false,
			)
		);
		if ( is_wp_error( $terms ) ) {
			continue;
		}
		foreach ( $terms as $term ) {
			if ( 0 == $term->count ) {
				wp_delete_term( $term->term_id, $taxonomy );
				$deleted_terms++;
			}
		}
	}
	siw_debug_log( 'Terms verwijderd: ' . $deleted_terms );
	siw_debug_log( 'Eind verwijderen ongebruikte terms');
});


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


/*
 * Bijwerken YITH Ajax Navigation widgets
 */
siw_add_cron_job( 'siw_update_yith_widgets' );

add_action( 'siw_update_yith_widgets', function() {
	siw_debug_log( 'Start updaten YITH widgets' );
	$widgets = get_option( 'widget_yith-woo-ajax-navigation' );
	$attributes = array(
		'maand',
		'land',
		'soort-werk',
		'doelgroep',
	);

	foreach ( $widgets as $index=>$widget ) {

		if ( isset( $widget['attribute'] ) && in_array( $widget['attribute'], $attributes ) ) {
			$terms = get_terms( array(
				'taxonomy' => 'pa_' . $widget['attribute'],
				'hide_empty' => false,
			));
			$labels = array();
			foreach ( $terms as $term ) {
				$labels[ $term->term_id ] = $term->name;
			}

			$widgets[ $index ]['labels'] =  $labels;
		}
	}
	update_option( 'widget_yith-woo-ajax-navigation', $widgets );
	siw_debug_log( 'Eind updaten YITH widgets' );
});


/* Verweesde variaties verwijderen*/
siw_add_cron_job( 'siw_delete_orphaned_variations' );

add_action( 'siw_delete_orphaned_variations', function() {
	siw_debug_log( 'Start verwijderen verweesde variaties' );
	$args = array(
		'posts_per_page'		=> -1,
		'post_type'				=> 'product',
		'fields'				=> 'ids',
		'post_status'			=> 'any',
	);
	$products = get_posts( $args );

	//zoek alle product_variations zonder parent.
	$args = array(
		'posts_per_page'		=> -1,
		'post_type'				=> 'product_variation',
		'post_parent__not_in'	=> $products,
		'fields' 				=> 'ids',
	);
	$variations = get_posts( $args );

	if ( empty( $variations ) ) {
		siw_debug_log( 'Eind verwijderen verweesde variaties: geen variaties te verwijderen' );
		return;
	}
	siw_debug_log( 'Aantal te verwijderen variaties: ' . count( $variations ) );


	//wp all import tabel bijwerken
	global $wpdb;
	if ( ! isset( $wpdb->pmxi_posts ) ) {
		$wpdb->pmxi_posts = $wpdb->prefix . 'pmxi_posts';
	}

	$variation_ids = implode( ',', $variations );
	$wpdb->query(
		$wpdb->prepare("
			DELETE FROM $wpdb->pmxi_posts
			WHERE post_id IN (%s)",
			$variation_ids
		)
	);
	//siw_start_background_process( 'delete_orphaned_variations', $variations );
});


/* Verwijderen groepsprojecten met een startdatum die meer dan 6 maanden in het verleden ligt */
siw_add_cron_job( 'siw_delete_projects' );

add_action( 'siw_delete_projects', function() {
	siw_debug_log( 'Start verwijderen projecten' );

	$limit = date( 'Y-m-d', time() - ( 6 * MONTH_IN_SECONDS ) );

	$meta_query = array(
		'relation'	=> 'OR',
		array(
			'key'		=> 'startdatum',
			'value'		=> $limit,
			'compare'	=> '<',
		),
		array(
			'key'		=> 'startdatum',
			'compare'	=> 'NOT EXISTS',
		),
	);
	$args = array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'product',
		'meta_query'		=> $meta_query,
		'fields' 			=> 'ids'
	);
	$products = get_posts( $args ); //TODO: wc_get_products gebruiken

	// Afbreken als er geen te verwijderen projecten zijn
	if ( empty( $products ) ) {
		siw_debug_log( 'Eind verwijderen projecten: geen projecten te verwijderen' );
		return;
	}
	siw_debug_log( 'Aantal te verwijderen projecten: ' . count( $products ) );

	//variaties van geselecteerde projecten opzoeken
	$args = array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'product_variation',
		'post_parent__in'	=> $products,
		'fields' 			=> 'ids',
	);
	$variations = get_posts( $args );

	//variaties en producten samenvoegen tot 1 array voor DELETE-query
	$posts = array_merge( $variations, $products );
	$post_ids = implode( ',', $posts );

	//wp all import tabel bijwerken
	global $wpdb;
	if ( ! isset( $wpdb->pmxi_posts ) ) {
		$wpdb->pmxi_posts = $wpdb->prefix . 'pmxi_posts';
	}

	$wpdb->query(
		$wpdb->prepare("
			DELETE FROM $wpdb->pmxi_posts
			WHERE post_id IN (%s)",
			$post_ids
		)
	);

	siw_start_background_process( 'delete_workcamps', $products );
});


/* Verwijderen aanmeldingen van meer dan 1 jaar oud */
siw_add_cron_job( 'siw_delete_applications' );

add_action( 'siw_delete_applications', function() {

	siw_debug_log( 'Start verwijderen aanmeldingen' );

	// Zoek te verwijderen aanmeldingen
	$args = array(
		'limit'			=> -1,
		'return'		=> 'ids',
		'type'			=> 'shop_order',
		'date_created'	=> '<' . ( time() - YEAR_IN_SECONDS ),
	);
	$applications = wc_get_orders( $args );

	// Afbreken als er geen te verwijderen aanmeldingen zijn
	if ( empty( $applications ) ) {
		siw_debug_log( 'Eind verwijderen aanmeldingen: geen aanmeldingen te verwijderen' );
		return;
	}
	siw_debug_log( 'Aantal te verwijderen aanmeldingen: ' . count( $applications ) );

	siw_start_background_process( 'delete_applications', $applications );
});
