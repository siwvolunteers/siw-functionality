<?php declare(strict_types=1);

use SIW\Formatting;
use SIW\Properties;

/**
 * Functies m.b.t. evenementen
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Geeft uitgelichte vacatures terug
 *
 * @param int $number
 *
 * @return array
 */
function siw_get_featured_job_postings( int $number = 1 ) : array {
	$args = [
		'number'   => $number,
		'featured' => true,
		
	];
	return siw_get_active_job_postings( $args );
}

/**
 * Geeft actieve vacatures terug
 *
 * @param array $args
 *
 * @return array
 */
function siw_get_active_job_postings( array $args = [] ) : array {

	$args = wp_parse_args(
		$args,
		[
			'number'         => -1,
			'featured'       => null,
			'deadline_after' => date( 'Y-m-d' ),
			'return'         => 'ids'
		]
	);

	//Meta query opbouwen
	$meta_query = [
		'relation' => 'AND',
	];

	$meta_query[] = [
		[
			'key'     => 'deadline',
			'value'   => $args['deadline_after'],
			'compare' => '>'
		],
	];

	//Zoeken op uitgelichte vacatures
	if ( null !== $args['featured'] ) {
		$meta_query[] = [
			[
				'key'     => 'featured',
				'value'   => $args['featured'],
				'compare' => '=',
			]
		];
	}

	$post_query = [
		'post_type'           => 'siw_job_posting',
		'posts_per_page'      => $args['number'],
		'post_status'         => 'publish',
		'meta_key'            => 'deadline',
		'orderby'             => 'meta_value',
		'order'               => 'ASC',
		'meta_query'          => $meta_query,
		'fields'              => $args['return']
	];

	return get_posts( $post_query );
}

/**
 * Genereer structured data voor vacature
 *
 * @param array $job_id
 * @return string
 * 
 * @todo verplaatsen naar Util/JsonLD
 */
function siw_generate_job_posting_json_ld( int $job_id ) : string {

	$description = siw_meta( 'description', [], $job_id );
	$job_description = wpautop( siw_meta( 'introduction', [], $job_id ) ) .
		'<h5><strong>' . __( 'Wat ga je doen?', 'siw' ) . '</strong></h5>' . wpautop( $description['work'] ) .
		'<h5><strong>' . __( 'Wie ben jij?', 'siw' ) . '</strong></h5>' . wpautop( $description['qualifications'] ) .
		'<h5><strong>' . __( 'Wat bieden wij jou?', 'siw' ) . '</strong></h5>' . wpautop( $description['perks'] ) .
		'<h5><strong>' . __( 'Wie zijn wij?', 'siw' ) . '</strong></h5>' . wpautop( siw_get_option('job_postings_organization_profile') );

	$data = [
		'@context'          => 'https://schema.org/',
		'@type'             => 'JobPosting',
		'title'             => esc_attr( get_the_title( $job_id )),
		'description'       => wp_kses_post( $job_description ),
		'datePosted'        => esc_attr( get_the_modified_date( 'Y-m-d', $job_id ) ),
		'validThrough'      => esc_attr( siw_meta( 'deadline', [], $job_id ) ),
		'employmentType'    => siw_meta( 'paid', [], $job_id ) ? ['PARTTIME'] : ['PARTTIME', 'VOLUNTEER'],
		'hiringOrganization'=> [
			'@type' => 'Organization', 
			'name'  => Properties::NAME,
			'sameAs'=> SIW_SITE_URL,
		],
		'jobLocation' => [
			'@type'     => 'Place',
			'address'   => [
				'@type'             => 'PostalAddress',
				'streetAddress'     => Properties::ADDRESS,
				'addressLocality'   => Properties::CITY,
				'postalCode'        => Properties::POSTCODE,
				'addressRegion'     => Properties::CITY,
				'addressCountry'    => 'NL',
			],
		],
	];

	return Formatting::generate_json_ld( $data );
}
