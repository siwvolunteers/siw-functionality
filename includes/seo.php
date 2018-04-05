<?php
/*
(c)2017-2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Zet SEO noindex
 * @param int $post_id
 * @param bool $value
 */
function siw_seo_set_noindex( $post_id, $value = false ) {
	$noindex = $value ? 1 : 0;
	update_post_meta( $post_id, '_genesis_noindex', $noindex );
}


/**
 * Zet SEO meta title
 * @param int $post_id
 * @param string $title
 */
function siw_seo_set_title( $post_id, $title ) {
	update_post_meta( $post_id, '_genesis_title', $title );
}


/**
 * Zet SEO meta description
 * @param int $post_id
 * @param string $description
 */
function siw_seo_set_description( $post_id, $description ) {
	update_post_meta( $post_id, '_genesis_description', $description );
}


/* Naam auteur SEO framework niet in HTML tonen */
add_filter( 'sybre_waaijer_<3', '__return_false' );

/* SEO-metabox lagere prioriteit geven */
add_filter( 'the_seo_framework_metabox_priority', function( $priority ) {
	return 'default';
} );


/* SEO support voor Op maat projecten */
add_filter( 'the_seo_framework_supported_post_type', function( $post_type, $post_type_evaluated ) {

	if ( 'portfolio' === $post_type_evaluated )
		return $post_type_evaluated;
	
	return $post_type;
}, 10, 2 );


/* Diverse archieven niet indexeren */
add_filter( 'the_seo_framework_robots_meta_array', function( $robots ) {

	//$qo = get_queried_object();
	if ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
		$robots['noindex'] = 'noindex';	
	}

//TODO:soort_evenement soort_vacature testimonial wpm-testimonial-category

	return $robots;
});


/* Titel voor productarchives aanpassen */
add_filter( 'the_seo_framework_the_archive_title', function( $title, $term ) {
    if ( isset( $term->taxonomy ) && ( 'pa_land' === $term->taxonomy || 'product_cat' === $term->taxonomy ) ) {
        return sprintf( __( 'Groepsprojecten in %s', 'siw' ), $term->name );
	}
    if ( isset( $term->taxonomy ) && 'pa_doelgroep' === $term->taxonomy ) {
        return sprintf( __( 'Groepsprojecten voor %s', 'siw' ), $term->name );
	}
    if ( isset( $term->taxonomy ) && 'pa_taal' === $term->taxonomy ) {
        return sprintf( __( 'Groepsprojecten met voertaal %s', 'siw' ), $term->name );
	}

    return $title;
}, 10, 2 );



/* Aanpassingen sitemap
 * - Quotes niet in sitemap
 * - Kleuren
 * - Maximum aantal posts ophogen
 * - Query args aanpassen ivm performance
 */
add_filter( 'the_seo_framework_sitemap_exclude_cpt', function() {
	$remove = array(
		'testimonial',
	);
	return $remove;
});

add_filter( 'the_seo_framework_sitemap_color_accent', function( $color ) {
	return SIW_FONT_COLOR;
});

add_filter( 'the_seo_framework_sitemap_color_main', function( $color ) {
	return SIW_PRIMARY_COLOR;
});

add_filter( 'the_seo_framework_sitemap_custom_posts_count', function() {
	return 5000;
});

add_filter( 'the_seo_framework_sitemap_cpt_query_args', function( $args ) {
	$args['meta_query'] = array(
		'relation'	=> 'OR',
		array(
			'key'		=> '_genesis_noindex',
			'value'		=> 0,
			'compare'	=> '=',
		),
		array(
			'key'		=> '_genesis_noindex',
			'compare'	=> 'NOT EXISTS',
		),
	);
	return $args;
});


/* Productarchieven toevoegen aan de sitemap */
add_filter( 'the_seo_framework_sitemap_additional_urls', function( $custom_urls ) {

	$taxonomies = array(
		'product_cat',
		'pa_land',
		'pa_doelgroep',
		'pa_soort-werk',
		'pa_taal',
	);

	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_terms( $taxonomy, array( 'hide_empty' => true ) );
		foreach ( $terms as $term ) {
			$custom_urls[] = get_term_link( $term->slug, $term->taxonomy );
		}
	}

	return $custom_urls;
});


/* Irritante bots filteren */
add_filter( 'the_seo_framework_robots_txt_pro', function( $output ) {

	$bots = siw_get_setting( 'blocked_bots');

	if ( empty( $bots ) ) {
		return $output;
	}
	$output .= PHP_EOL;

	foreach ( $bots as $bot ) {
		$output .= "User-agent: " . esc_attr( $bot ) . PHP_EOL;
		$output .= "Disallow: /" . PHP_EOL . PHP_EOL;
	}

	return $output;
});
