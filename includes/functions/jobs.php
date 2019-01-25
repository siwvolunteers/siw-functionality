<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Zet noindex voor evenementen waarvan de deadline verstreken is */
SIW_Scheduler::add_job( 'siw_set_noindex_for_expired_jobs' );

add_action( 'siw_set_noindex_for_expired_jobs', function() {
	$args = [
		'post_type'      => 'vacatures',
		'fields'         => 'ids',
		'posts_per_page' => -1,
	];
	$job_ids = get_posts( $args );
	foreach ( $job_ids as $job_id ) {
		$noindex = 0;
		$deadline_ts = get_post_meta( $job_id, 'siw_vacature_deadline', true );
		if ( $deadline_ts < time() ) {//TODO:vergelijken datum i.p.v. ts
			$noindex = 1;
			//TODO:uitgelicht op off zetten
		}
		SIW_Util::set_seo_noindex( $job_id, $noindex );
	}
} );


/**
 * Haal gegevens van vacature op
 *
 * @param int $post_id
 * @return array
 */
function siw_get_job_data( $post_id ) {
	$deadline_ts            = get_post_meta( $post_id, 'siw_vacature_deadline', true );
	$contactpersoon_functie = get_post_meta( $post_id, 'siw_vacature_contactpersoon_functie', true );
	$solliciteren_functie   = get_post_meta( $post_id, 'siw_vacature_solliciteren_functie', true );

	$job_data = [
		'permalink'                => get_permalink( $post_id ),
		'title'                    => get_the_title( $post_id ),
		'deadline_datum'           => date( 'Y-m-d', $deadline_ts ),
		'deadline'                 => SIW_Formatting::format_date( date( 'Y-m-d', $deadline_ts ), false ),
		'inleiding'                => get_post_meta( $post_id, 'siw_vacature_inleiding', true ),
		'highlight_quote'          => get_post_meta( $post_id, 'siw_vacature_highlight_quote', true ),
		'betaald'                  => get_post_meta( $post_id, 'siw_vacature_betaald', true ),
		'uur_per_week'             => get_post_meta( $post_id, 'siw_vacature_uur_per_week', true ),
		'wie_ben_jij'              => get_post_meta( $post_id, 'siw_vacature_wie_ben_jij', true ),
		'wie_ben_jij_lijst'        => get_post_meta( $post_id, 'siw_vacature_wie_ben_jij_lijst', true ),
		'wat_ga_je_doen'           => get_post_meta( $post_id, 'siw_vacature_wat_ga_je_doen', true ),
		'wat_ga_je_doen_lijst'     => get_post_meta( $post_id, 'siw_vacature_wat_ga_je_doen_lijst', true ),
		'wat_bieden_wij_jou'       => get_post_meta( $post_id, 'siw_vacature_wat_bieden_wij_jou', true ),
		'wat_bieden_wij_jou_lijst' => get_post_meta( $post_id, 'siw_vacature_wat_bieden_wij_jou_lijst', true ),
		'contactpersoon_naam'      => get_post_meta( $post_id, 'siw_vacature_contactpersoon_naam', true ),
		'contactpersoon_email'     => antispambot( get_post_meta( $post_id, 'siw_vacature_contactpersoon_email', true ) ),
		'contactpersoon_telefoon'  => get_post_meta( $post_id, 'siw_vacature_contactpersoon_telefoon', true ),// Wordt nog niet gebruikt
		'solliciteren_naam'        => get_post_meta( $post_id, 'siw_vacature_solliciteren_naam', true ),
		'solliciteren_email'       => antispambot( get_post_meta( $post_id, 'siw_vacature_solliciteren_email', true ) ),
		'toelichting_solliciteren' => get_post_meta( $post_id, 'siw_vacature_toelichting_solliciteren', true ),
		'meervoud'                 => get_post_meta( $post_id, 'siw_vacature_meervoud', true ),
		'date_last_updated'        => get_the_modified_date( 'Y-m-d', $post_id ),
	];

	if ( $contactpersoon_functie ) {
		$job_data['contactpersoon_naam']	= $job_data['contactpersoon_naam'] . ' (' . $contactpersoon_functie . ')';
	}

	if (  $solliciteren_functie ) {
		$job_data['solliciteren_naam'] =  $job_data['solliciteren_naam'] . ' (' .  $solliciteren_functie . ')';
	}
	$job_data['json_ld'] = siw_generate_job_json_ld( $job_data );

	return $job_data;
}
add_filter( 'siw_job_data', function( $job_data, $post_id ) {
	return siw_get_job_data( $post_id );
}, 10, 2 );




/**
 * Geeft eerste uitgelichte vacature terug
 * @return array
 */
function siw_get_featured_job() {
	$meta_query = [
		'relation' => 'AND',
		[
			'key'     => 'siw_vacature_deadline',
			'value'   => time(),
			'compare' => '>=',
		],
		[
			'key'     => 'siw_vacature_uitgelicht',
			'value'   => 'on',
			'compare' => '=',
		],
	];
	$query_args = [
		'post_type'           => 'vacatures',
		'posts_per_page'      => 1,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'meta_key'            => 'siw_vacature_deadline',
		'orderby'             => 'meta_value_num',
		'order'               => 'ASC',
		'meta_query'          => $meta_query,
		'fields'              => 'ids'
	];
	$featured_job_id = get_posts( $query_args );
	if ( empty ( $featured_job_id ) ) {
		return false;
	}
	$featured_job = siw_get_job_data( $featured_job_id[0] );

	return $featured_job;
}


/**
 * Genereer structured data voor evenement
 *
 * @param array $job
 * @return string
 */
function siw_generate_job_json_ld( $job ) {

	$description = wpautop( $job['inleiding'] ) .
		'<h5><strong>' . __( 'Wat ga je doen?', 'siw' ) . '</strong></h5>' . wpautop( $job['wat_ga_je_doen'] . SIW_Formatting::generate_list( $job['wat_ga_je_doen_lijst'] ) ) .
		'<h5><strong>' . __( 'Wie ben jij?', 'siw' ) . '</strong></h5>' . wpautop( $job['wie_ben_jij'] . SIW_Formatting::generate_list( $job['wie_ben_jij_lijst'] ) ) .
		'<h5><strong>' . __( 'Wat bieden wij jou?', 'siw' ) . '</strong></h5>' . wpautop( $job['wat_bieden_wij_jou'] . SIW_Formatting::generate_list( $job['wat_bieden_wij_jou_lijst'] ) ) .
		'<h5><strong>' . __( 'Wie zijn wij?', 'siw' ) . '</strong></h5>' . wpautop( siw_get_setting('company_profile') );

	$logo = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );

	$data = [
		'@context'          => 'http://schema.org',
		'@type'             => 'JobPosting',
		'description'       => wp_kses_post( $description ),
		'title'             => esc_attr( $job['title'] ),
		'datePosted'        => esc_attr( $job['date_last_updated'] ),
		'validThrough'      => esc_attr( $job['deadline_datum'] ),
		'employmentType'    => ['VOLUNTEER', 'PARTTIME'],
		'hiringOrganization'=> [
			'@type' => 'Organization', 
			'name'  => SIW_Properties::get('name'),
			'sameAs'=> SIW_SITE_URL,
			'logo'  => esc_url( $logo ),
		],
		'jobLocation'   => [
			'@type'     => 'Place',
			'address'   => [
				'@type'             => 'PostalAddress',
				'streetAddress'     => SIW_Properties::get('address'),
				'addressLocality'   => SIW_Properties::get('city'),
				'postalCode'        => SIW_Properties::get('postal_code'),
				'addressRegion'     => SIW_Properties::get('city'),
				'addressCountry'    => 'NL',
			],
		],
	];

	return SIW_Formatting::generate_json_ld( $data );
}
