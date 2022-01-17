<?php declare(strict_types=1);

use SIW\Structured_Data\Employment_Type;
use SIW\Structured_Data\Job_Posting;
use SIW\Structured_Data\NL_Non_Profit_Type;
use SIW\Structured_Data\Organization;
use SIW\Structured_Data\Place;
use SIW\Structured_Data\Postal_Address;
use SIW\Properties;

/**
 * Functies m.b.t. evenementen
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */


/** Geeft actieve vacatures terug */
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

/** Genereer structured data voor vacature */
function siw_generate_job_posting_json_ld( int $job_id ) : string {

	$job_posting = Job_Posting::create()
		->set_title( get_the_title( $job_id ) )
		->set_description( siw_meta( 'introduction', [], $job_id ) )
		->set_date_posted( new \DateTime( get_the_modified_date( 'Y-m-d', $job_id ) ) )
		->set_valid_through( new \DateTime( siw_meta( 'deadline', [], $job_id ) ) )
		->set_employment_type( Employment_Type::PART_TIME() );
		
	switch ( siw_meta( 'job_type', [], $job_id ) ) {
		case 'paid':
			break;
		case 'internship':
			$job_posting->add_employment_type( Employment_Type::INTERN() );
			break;
		case 'volunteer':
		default:
			$job_posting->add_employment_type( Employment_Type::VOLUNTEER() );
	}

	$job_posting->set_hiring_organization(
			Organization::create()
				->set_name( Properties::NAME )
				->set_same_as( SIW_SITE_URL )
				->set_logo( get_site_icon_url() )
				->set_non_profit_status( NL_Non_Profit_Type::NonprofitANBI() )
		)
		->set_qualifications( siw_meta( 'description.qualifications', [], $job_id ) )
		->set_responsibilities( siw_meta( 'description.work', [], $job_id ) )
		->set_employer_overview( siw_get_option('job_postings_organization_profile')  )
		->set_job_benefits( siw_meta( 'description.perks', [], $job_id ) )
		->set_job_location(
			Place::create()
				->set_address(
					Postal_Address::create()
						->set_street_address( Properties::ADDRESS )
						->set_address_locality( Properties::CITY )
						->set_postal_code( Properties::POSTCODE )
						->set_address_region( 'NL' )
						->set_address_country( 'NL' )
				)
		);
	return $job_posting->to_script();
}
