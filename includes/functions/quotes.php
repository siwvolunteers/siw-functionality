<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Geeft array met gegevens van een quote terug
 *
 * @param  string $category
 * @return array
 */
function siw_get_testimonial_quote( $category = '' ) {

	$query_args = array(
		'post_type'				=> 'testimonial',
		'posts_per_page'		=> 1,
		'post_status'			=> 'publish',
		'ignore_sticky_posts'	=> true,
		'orderby'				=> 'rand',
		'fields' 				=> 'ids',
		'testimonial-group'		=> $category,
	);
	$post_ids = get_posts( $query_args );

	if ( empty( $post_ids ) ) {
		return;
	}

	$post_id = $post_ids[0];
	$testimonial_quote['quote'] = get_post_field('post_content', $post_id );
	$testimonial_quote['name'] = get_the_title( $post_id );
	$testimonial_quote['project'] = get_post_meta( $post_id, '_kad_testimonial_location', true );
	return $testimonial_quote;
}


/**
 * Geeft lijst van categorieën voor quotes terug
 *
 * @return array
 */
function siw_get_testimonial_quote_categories() {
	$testimonial_groups = get_terms( 'testimonial-group' );
	$testimonial_quote_categories[''] =  __( 'Alle', 'siw' );
	foreach ( $testimonial_groups as $testimonial_group ) {
		$testimonial_quote_categories[ $testimonial_group->slug ] = $testimonial_group->name;
	}
	return $testimonial_quote_categories;
}
