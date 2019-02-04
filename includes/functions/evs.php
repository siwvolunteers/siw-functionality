<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Geeft de datum van de volgende EVS-deadline terug
 * @param bool $date_in_text
 * @return string
 */
function siw_get_next_evs_deadline( $date_in_text = false ) {
	for ( $x = 1 ; $x <= SIW_NUMBER_OF_EVS_DEADLINES; $x++ ) {
		$evs_deadlines[]= siw_get_setting( "evs_deadline_{$x}" );
	}
	asort( $evs_deadlines );
	$weeks = siw_get_setting( 'evs_min_weeks_before_deadline' );
	$limit = date( 'Y-m-d', time() + ( $weeks * WEEK_IN_SECONDS ) );

	foreach ( $evs_deadlines as $evs_deadline => $evs_deadline_date ) {
		if ( $evs_deadline_date > $limit ) {
			$next_evs_deadline = $evs_deadline_date;
			break;
		}
	}
	if ( ! isset ( $next_evs_deadline ) ) {
		return;
	}

	if ( $date_in_text ) {
		$next_evs_deadline = SIW_Formatting::format_date( $next_evs_deadline );
	}

	return $next_evs_deadline;
}


/**
 * Geeft de maand en jaar van het volgende EVS-vertrekmoment terug
 *
 * Telt 14 weken op bij de volgende EVS-deadline
 * @return string
 */
function siw_get_next_evs_departure_month() {

	$weeks = SIW_EVS_WEEKS_BEFORE_DEPARTURE;
	$next_evs_deadline = siw_get_next_evs_deadline();

	if ( empty( $next_evs_deadline ) ) {
		return;
	}

	$next_evs_departure = strtotime( $next_evs_deadline) + ( $weeks * WEEK_IN_SECONDS ) ;
	$next_evs_departure_month = date_i18n( 'F Y',  $next_evs_departure );

	return $next_evs_departure_month;
}


/**
 * [siw_get_evs_project_data description]
 * @param  int $post_id
 * @return array
 */
function siw_get_evs_project_data( $post_id ) {

	$evs_countries = siw_get_evs_countries();
	$evs_work_types = siw_get_evs_project_work_types();

	$evs_project_data = [
		'permalink'				=> get_permalink( $post_id ),
		'title'					=> get_the_title( $post_id ),
		//'excerpt' 				=> get_the_excerpt( $post_id ),
		'post_thumbnail_url'	=> get_the_post_thumbnail_url( $post_id ),
		'highlight_quote'		=> get_post_meta( $post_id, 'siw_evs_project_highlight_quote', true ),
		'deadline'				=> SIW_Formatting::format_date( get_post_meta( $post_id, 'siw_evs_project_deadline', true ) ),
		'wat_ga_je_doen'		=> get_post_meta( $post_id, 'siw_evs_project_wat_ga_je_doen', true ),
		'organisatie'			=> get_post_meta( $post_id, 'siw_evs_project_organisatie', true ),
		'plaats'				=> get_post_meta( $post_id, 'siw_evs_project_plaats', true ),
		'startdatum'			=> date( 'Y-m-d', get_post_meta( $post_id, 'siw_evs_project_startdatum', true ) ),
		'einddatum'				=> date( 'Y-m-d', get_post_meta( $post_id, 'siw_evs_project_einddatum', true ) ),
	];
	$evs_project_data['projectduur'] = SIW_Formatting::format_month_range( $evs_project_data['startdatum'], $evs_project_data['einddatum'] );
	$evs_project_data['land'] = $evs_countries[ get_post_meta( $post_id, 'siw_evs_project_land', true ) ];
	$evs_project_data['soort_werk']	= $evs_work_types[ get_post_meta( $post_id, 'siw_evs_project_soort_werk', true ) ];

	return $evs_project_data;
}



//TODO: functie om oude evs-projecten te verwijderen


/**
 * [siw_get_active_evs_projects description]
 * @param  int $number
 * @return array
 */
function siw_get_active_evs_projects( $number ) {
	$min_date = strtotime( date( 'Y-m-d' ) );
	$meta_query_args = [
		'relation'	=>	'AND',
		[
			'key'		=>	'siw_evs_project_deadline',
			'value'		=>	$min_date,
			'compare'	=>	'>='
		],
	];

	$query_args = [
		'post_type'				=>	'evs_project',
		'posts_per_page'		=>	$number,
		'post_status'			=>	'publish',
		'ignore_sticky_posts'	=>	true,
		'meta_key'				=>	'siw_evs_project_deadline',
		'orderby'				=>	'meta_value_num',
		'order'					=>	'ASC',
		'meta_query'			=>	$meta_query_args,
		'fields' 				=> 'ids'
	];

	$evs_projects_ids = get_posts( $query_args );

	$active_evs_projects = [];
	foreach ( $evs_projects_ids as $evs_projects_id ) {
		$active_evs_projects[] = siw_get_evs_project_data( $evs_projects_id );
	}
	return $active_evs_projects;
}

/**
 * Geeft array met EVS-landen terug
 * @deprecated
 * @return array
 */
function siw_get_evs_countries() {

	$countries = siw_get_countries( 'esc_projects' );

	$evs_countries = [];
	foreach ( $countries as $country ) {
		$evs_countries[ $country->get_slug() ] = $country->get_name();
	}

	return $evs_countries;
}


add_shortcode( 'siw_evs_projecten', function() {
	$evs_projects = siw_get_active_evs_projects( 4 );

	/* Start template*/
	ob_start();
	?>
	<div class="carousel_outerrim kad-animation" data-animation="fade-in" data-delay="0">
		<div class="rowtight">
			<div class="evs-carousel slick-slider kht-slickslider">
			<?php foreach ( $evs_projects as $evs_project ) :?>
				<div class="kt-slick-slide">
					<a href="<?php echo esc_url( $evs_project['permalink'] ); ?>" title="<?php echo esc_attr( $evs_project['title']); ?>">
						<div class="row">
							<img src="<?php echo esc_url( $evs_project['post_thumbnail_url'] );?>">
						</div>
						<div class="row">
							<h5><?php echo esc_html( $evs_project['title'] ); ?></h5>
							<?php echo esc_html( $evs_project['land'] ); ?><br/>
							<?php echo ucfirst( esc_html( $evs_project['projectduur'] ) ); ?>
						</div>
					</a>
				</div>
			<?php endforeach ?>
			</div>
		</div>
	</div>
	<?php
	$output = ob_get_clean();

	wp_add_inline_script( 'pinnacle_plugins',"
	(function( $ ) {
		$( document ).ready(function() {
			$('.evs-carousel').slick({
				slidesToShow: 3,
				slidesToScroll: 1,
				dots: true,
				responsive: [
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 1,
						dots: true
					}
				}
				]
			});
		});
	})( jQuery );
" );

	return $output;

});
