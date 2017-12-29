<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Geeft een array met gewhiteliste IP-adressen terug
 *
 * @return array
 */
function siw_get_ip_whitelist() {
	for ( $x = 1 ; $x <= SIW_IP_WHITELIST_SIZE; $x++ ) {
		$ip_whitelist[] = siw_get_setting( "whitelist_ip_{$x}" );
	}
	return $ip_whitelist;
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
		$next_evs_deadline = siw_get_date_in_text( $next_evs_deadline );
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

	$next_evs_departure = date_parse( date( 'Y-m-d', strtotime( $next_evs_deadline) + ( $weeks * WEEK_IN_SECONDS ) ) );
	$next_evs_departure_month = siw_get_month_in_text( 	$next_evs_departure['month'] ) . ' ' . $next_evs_departure['year'];

	return $next_evs_departure_month;
}


/**
 * Geeft de datum van de volgende infodag terug
 *
 * @param bool $date_in_text
 * @return string
 */
function siw_get_next_info_day( $date_in_text = false ) {
	$future_info_days = siw_get_future_info_days( $date_in_text );

	if ( empty( $future_info_days ) ) {
		return;
	}
	$next_info_day = $future_info_days[0];

	return $next_info_day;
}


/**
 * Geeft de array met tokomstige infodagen terug
 *
 * @param bool $dates_in_text
 * @param int $results
 *
 * @return array
 */
function siw_get_future_info_days( $dates_in_text = false, $results = SIW_NUMBER_OF_INFO_DAYS ) {

	for ( $x = 1 ; $x <= SIW_NUMBER_OF_INFO_DAYS; $x++ ) {
		$info_days[]= siw_get_setting("info_day_{$x}");
	}
	asort( $info_days );
	$hide_form_days_before_info_day = siw_get_setting( 'hide_application_form_days_before_info_day' );
	$limit = date( 'Y-m-d', time() + ( $hide_form_days_before_info_day * DAY_IN_SECONDS ) );

	$future_info_days = array();
	foreach ( $info_days as $info_day ) {
		if ( $info_day > $limit ) {
			$future_info_days[] = $dates_in_text ? siw_get_date_in_text( $info_day, false ) : $info_day;
		}
	}

	$results = min( $results, SIW_NUMBER_OF_INFO_DAYS );
	$future_info_days = array_slice( $future_info_days, 0, $results );
	return $future_info_days;
}


/**
 * Geeft de maand in tekst terug
 *
 * @param int $month maandnummer (1-12)
 * @return string
 */
function siw_get_month_in_text( $month ) {
	$month_to_text = array (
		'1'		=> 'januari',
		'2'		=> 'februari',
		'3'		=> 'maart',
		'4'		=> 'april',
		'5'		=> 'mei',
		'6'		=> 'juni',
		'7'		=> 'juli',
		'8'		=> 'augustus',
		'9'		=> 'september',
		'10'	=> 'oktober',
		'11'	=> 'november',
		'12'	=> 'december',
	);
	$month_in_text = ( isset ( $month_to_text[ $month ] ) ? $month_to_text[ $month ] : '' );

	return $month_in_text;
}


/**
 * Geeft de datum in tekst terug
 *
 * @param string $date Y-m-d
 * @param bool $year Jaar toevoegen aan tekst
 *
 * @return string
 */
function siw_get_date_in_text( $date, $year = true ) {
	$date_array = date_parse( $date );
	$day = $date_array['day'];
	$month = siw_get_month_in_text( $date_array['month'] );
	$date_in_text = $day . ' ' . $month;
	if ( $year ) {
		$year = $date_array['year'];
		$date_in_text .=  ' ' . $year;
	}

	return $date_in_text;
}


/**
 * Geeft de datum in tekst terug
 *
 * @param string $date_start Y-m-d
 * @param string $date_end Y-m-d
 * @param bool $year jaar toevoegen aan tekst
 *
 * @return string
 */
function siw_get_date_range_in_text( $date_start, $date_end, $year = true ) {
	//als beide datums gelijk zijn gebruik dan siw_get_date_in_text
	if ( $date_start == $date_end ) {
		$date_range_in_text = siw_get_date_in_text( $date_start, $year );
	}
	else {
		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );

		$date_range_in_text = $date_start_array['day'];
		if ( $date_start_array['month'] != $date_end_array['month']) {
			$date_range_in_text .= ' ' . siw_get_month_in_text( $date_start_array['month'] );
		}
		if ( ($date_start_array['year'] != $date_end_array['year'] ) and $year ) {
			$date_range_in_text .= ' ' . $date_start_array['year'];
		}
		$date_range_in_text .= ' t/m ';
		$date_range_in_text .= $date_end_array['day'];
		$date_range_in_text .= ' ' . siw_get_month_in_text( $date_end_array['month'] );
		if ( $year ) {
			$date_range_in_text .= ' ' . $date_end_array['year'];
		}

	}
	return $date_range_in_text;
}


/**
 * Berekent leeftijd in jaren o.b.v. huidige datm
 * @param  string $date dd-mm-jjjj
 * @return int leeftijd in jaren
 */
function siw_get_age_from_date( $date ) {

	$from = new DateTime( $date );
	$to   = new DateTime('today');
	$age = $from->diff($to)->y;

	return $age;
}


/**
 * Zet pa_maand-slug om naar string
 *
 * @param string $slug
 *
 * @return string
 */
function siw_get_month_name_from_slug( $slug ) {
	$year = substr( $slug, 0, 4);
	$month = substr( $slug, 4, 2);
	$month = ltrim( $month, '0' );
	$current_year = date( 'Y' );

	$month_name = ucfirst( siw_get_month_in_text( $month ) );
	if ( $year != $current_year ) {
		$month_name .= ' ' . $year;
	}
	return $month_name;
}


/**
 * Geeft array met tarieven groepsprojecten terug
 *
 * @return array
 */
function siw_get_workcamp_tariffs() {
	$workcamp_tariffs = array(
		'regulier'	=> number_format( SIW_WORKCAMP_FEE_REGULAR, 2 ),
		'student'	=> number_format( SIW_WORKCAMP_FEE_STUDENT, 2 )
	);
	return $workcamp_tariffs;
}


/**
 * Geeft array met Mailpoet-lijsten terug
 *
 * @return array id => naam
 */
function siw_get_mailpoet_lists() {
	if ( ! class_exists( 'WYSIJA' ) ) {
		return;
	}
	$model_list = WYSIJA::get( 'list','model' );
	$lists = $model_list->get( array( 'name','list_id' ), array( 'is_enabled' => 1 ) );
	foreach ( $lists as $list ) {
		$mailpoet_lists[ $list['list_id'] ] = $list['name'];
	}
	return $mailpoet_lists;
}


/**
 * Geeft array met pagina's terug
 *
 * @return array id => naam
 */
function siw_get_pages() {
	$results = get_pages();
	$pages = array();
	foreach ( $results as $result ) {
		$ancestors = get_ancestors( $result->ID, 'page' );
		$prefix = str_repeat ( '-', sizeof( $ancestors ) );
		$pages[ $result->ID ] = $prefix . esc_html( $result->post_title );
	}
	return $pages;
}


/**
 * Geeft array met WPAI imports terug
 *
 * @return array
 */
function siw_get_wpai_imports() {
	global $wpdb;
	if ( ! isset( $wpdb->pmxi_imports ) ) {
		$wpdb->pmxi_imports = $wpdb->prefix . 'pmxi_imports';
	}
	$query = "SELECT $wpdb->pmxi_imports.id, $wpdb->pmxi_imports.friendly_name, $wpdb->pmxi_imports.name FROM $wpdb->pmxi_imports ORDER BY $wpdb->pmxi_imports.friendly_name ASC";
	$results = $wpdb->get_results( $query, ARRAY_A);
	foreach ( $results as $result ) {
		$imports[$result['id']] = esc_html( $result['friendly_name'] . ' (' . $result['name'] . ')' );
	}
	return $imports;
}

/**
 * Geeft array met Mapplic-kaarten terug
 *
 * @return array
 */
function siw_get_mapplic_maps() {
	$query_args = array(
		'post_type'				=> 'mapplic_map',
		'posts_per_page'		=> -1,
		'post_status'			=> 'publish',
		'ignore_sticky_posts'	=> true,
		'orderby'				=> 'title',
		'order'					=> 'ASC',
		'fields' 				=> 'ids',
	);
	$post_ids = get_posts( $query_args );

	if ( empty( $post_ids ) ) {
		return;
	}
	foreach ( $post_ids as $post_id ) {
		$mapplic_maps[ $post_id ] = get_the_title( $post_id );
	}
	return $mapplic_maps;
}


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


/**
 * Geeft array met gegevens van toekomstige evenementen terug
 *
 * @param  int $number
 * @param  string $min_date
 * @param  string $max_date
 *
 * @return array
 */
function siw_get_upcoming_events( $number, $min_date = '', $max_date = '' ) {

	if ( '' == $min_date ) {
		$min_date = strtotime( date( 'Y-m-d' ) );
	}

	$meta_query_args = array(
		'relation'	=>	'AND',
		array(
			'key'		=>	'siw_agenda_eind',
			'value'		=>	$min_date,
			'compare'	=>	'>='
		),

	);
	if ( '' != $max_date ) {
		$meta_query_args[] = array(
			'key'		=> 'siw_agenda_start',
			'value'		=> $max_date,
			'compare'	=>	'<='
		);
	}

	$query_args = array(
		'post_type'				=>	'agenda',
		'posts_per_page'		=>	$number,
		'post_status'			=>	'publish',
		'ignore_sticky_posts'	=>	true,
		'meta_key'				=>	'siw_agenda_start',
		'orderby'				=>	'meta_value_num',
		'order'					=>	'ASC',
		'meta_query'			=>	$meta_query_args,
		'fields' 				=> 'ids'
	);

	$events_ids = get_posts( $query_args );

	$upcoming_events = array();
	foreach ( $events_ids as $event_id ) {
		$upcoming_events[] = siw_get_event_data( $event_id );
	}

	return $upcoming_events;
}


/**
 * Haal gegevens van agenda-evenement op
 *
 * @param int $post_id
 *
 * @return array
 */
function siw_get_event_data( $post_id ) {
	$event_data['permalink']				= get_permalink( $post_id );
	$event_data['title']					= get_the_title( $post_id );
	$event_data['excerpt'] 					= get_the_excerpt( $post_id );
	$event_data['post_thumbnail_url'] 		= get_the_post_thumbnail_url( $post_id );
	$start_ts 								= get_post_meta( $post_id, 'siw_agenda_start', true );
	$end_ts 								= get_post_meta( $post_id, 'siw_agenda_eind', true );
	$event_data['start_date'] 				= date( 'Y-m-d', $start_ts );
	$event_data['end_date'] 				= date( 'Y-m-d', $end_ts );
	$event_data['start_time']				= date( 'H:i', $start_ts );
	$event_data['end_time']					= date( 'H:i', $end_ts );
	$event_data['date_range']				= siw_get_date_range_in_text( $event_data['start_date'],  $event_data['end_date'] , false );
	$event_data['duration']					= $event_data['date_range']	  . ', ' .  $event_data['start_time']	 . '&nbsp;-&nbsp;' . $event_data['end_time'];
	$event_data['program'] 					= get_post_meta( $post_id, 'siw_agenda_programma', true );
	$event_data['description']				= get_post_meta( $post_id, 'siw_agenda_beschrijving', true );
	$event_data['highlight_quote']			= get_post_meta( $post_id, 'siw_agenda_highlight_quote', true );
	$event_data['location']					= get_post_meta( $post_id, 'siw_agenda_locatie', true );
	$event_data['address']					= get_post_meta( $post_id, 'siw_agenda_adres', true );
	$event_data['postal_code']				= get_post_meta( $post_id, 'siw_agenda_postcode', true );
	$event_data['city']						= get_post_meta( $post_id, 'siw_agenda_plaats', true );

	$event_data['application'] 				= get_post_meta( $post_id, 'siw_agenda_aanmelden', true );
	$event_data['application_explanation']	= get_post_meta( $post_id, 'siw_agenda_aanmelden_toelichting', true );
	$event_data['application_link_url']		= get_post_meta( $post_id, 'siw_agenda_aanmelden_link_url', true );
	$event_data['application_link_text'] 	= get_post_meta( $post_id, 'siw_agenda_aanmelden_link_tekst', true );
	$event_data['text_after_hide_form']		= get_post_meta( $post_id, 'siw_agenda_tekst_na_verbergen_formulier', true );

	return $event_data;
}


/**
 * Haal gegevens van vacature op
 *
 * @param int $post_id
 * @return array
 */
function siw_get_job_data( $post_id ) {
	$deadline_ts							= get_post_meta( $post_id, 'siw_vacature_deadline', true );
	$job_data['permalink']					= get_permalink( $post_id );
	$job_data['title']						= get_the_title( $post_id );
	$job_data['deadline_datum']				= date( 'Y-m-d', $deadline_ts );
	$job_data['deadline']					= siw_get_date_in_text( date("Y-m-d", $deadline_ts ), false);
	$job_data['inleiding']					= get_post_meta( $post_id, 'siw_vacature_inleiding', true );
	$job_data['highlight_quote']			= get_post_meta( $post_id, 'siw_vacature_highlight_quote', true );
	$job_data['uur_per_week']				= get_post_meta( $post_id, 'siw_vacature_uur_per_week', true );
	$job_data['wie_ben_jij']				= get_post_meta( $post_id, 'siw_vacature_wie_ben_jij', true );
	$job_data['wat_ga_je_doen']				= get_post_meta( $post_id, 'siw_vacature_wat_ga_je_doen', true );
	$job_data['wat_bieden_wij_jou']			= get_post_meta( $post_id, 'siw_vacature_wat_bieden_wij_jou', true );
	$job_data['contactpersoon_naam']		= get_post_meta( $post_id, 'siw_vacature_contactpersoon_naam', true );
	$job_data['contactpersoon_functie']		= get_post_meta( $post_id, 'siw_vacature_contactpersoon_functie', true );
	if ( $job_data['contactpersoon_functie'] ) {
		$job_data['contactpersoon_naam']	= $job_data['contactpersoon_naam'] . ' (' . $job_data['contactpersoon_functie'] . ')';
	}
	$job_data['contactpersoon_email']		= antispambot( get_post_meta( $post_id, 'siw_vacature_contactpersoon_email', true ) );
	$job_data['contactpersoon_telefoon']	= get_post_meta( $post_id, 'siw_vacature_contactpersoon_telefoon', true );// Wordt nog niet gebruikt
	$job_data['solliciteren_naam']			= get_post_meta( $post_id, 'siw_vacature_solliciteren_naam', true );
	$job_data['solliciteren_functie']		= get_post_meta( $post_id, 'siw_vacature_solliciteren_functie', true );
	if (  $job_data['solliciteren_functie'] ) {
		$job_data['solliciteren_naam'] 		=  $job_data['solliciteren_naam'] . ' (' .  $job_data['solliciteren_functie'] . ')';
	}
	$job_data['solliciteren_email']			= antispambot( get_post_meta( $post_id, 'siw_vacature_solliciteren_email', true ) );
	$job_data['toelichting_solliciteren']	= get_post_meta( $post_id, 'siw_vacature_toelichting_solliciteren', true );
	$job_data['meervoud']					= get_post_meta( $post_id, 'siw_vacature_meervoud', true );

	return $job_data;
}

/**
 * Haal gegevens van aanmelding op
 * @param  object $order
 * @return array
 */
function siw_get_order_data( $order ) {

	/* Hulplijstjes */
	$genders = siw_get_volunteer_genders();
	$nationalities = siw_get_volunteer_nationalities();
	$languages = siw_get_volunteer_languages();
	$language_skill = siw_get_volunteer_language_skill_levels();

	/* Naam, gegeboortedatum, geslacht en nationaliteit */
	$order_data['first_name']		= $order->get_billing_first_name();
	$order_data['last_name']		= $order->get_billing_last_name();
	$order_data['full_name']		= sprintf( '%s %s', $order_data['first_name'], $order_data['last_name'] );
	$order_data['date_of_birth']	= $order->get_meta( '_billing_dob' );
	$order_data['gender_code']		= $order->get_meta( '_billing_gender' );
	$order_data['gender']			= $genders[ $order_data['gender_code'] ];
	$order_data['nationality_code']	= $order->get_meta( '_billing_nationality' );
	$order_data['nationality']		= $nationalities[ $order_data['nationality_code'] ];

	$order_data['street'] 			= $order->get_billing_address_1();
	$order_data['housenumber'] 		= $order->get_meta( '_billing_housenumber' );
	$order_data['postcode'] 		= $order->get_billing_postcode();
	$order_data['city'] 			= $order->get_billing_city();
	$order_data['country']			= $order->get_billing_country();
	/* Adres formatteren voor e-mail */
	$order_data['address']			= sprintf('%s %s<br/>%s %s<br/>%s', $order_data['street'] , $order_data['housenumber'], $order_data['postcode'], $order_data['city'], $order_data['country'] );
	$order_data['email']			= $order->get_billing_email();
	$order_data['phone']			= $order->get_billing_phone();

	/* Gegevens noodcontact */
	$order_data['emergency_contact_name']	= $order->get_meta( 'emergencyContactName' );
	$order_data['emergency_contact_phone']	= $order->get_meta( 'emergencyContactPhone' );

	/* Talenkennis */
	$order_data['language_1_code']			= $order->get_meta( 'language1' );
	$order_data['language_1']				= $languages[ $order_data['language_1_code'] ];
	$order_data['language_1_skill_code']	= $order->get_meta( 'language1Skill' );
	$order_data['language_1_skill']			= $language_skill[ $order_data['language_1_skill_code'] ];

	$order_data['language_2_code']			= $order->get_meta( 'language2' );
	$order_data['language_2']				= ! empty( $order_data['language_2_code'] ) ? $languages[ $order_data['language_2_code'] ] : '';
	$order_data['language_2_skill_code']	= $order->get_meta( 'language2Skill' );
	$order_data['language_2_skill']			= isset( $language_skill[ $order_data['language_2_skill_code'] ] ) ? $language_skill[ $order_data['language_2_skill_code'] ] : '';

	$order_data['language_3_code']			= $order->get_meta( 'language3' );
	$order_data['language_3']				= ! empty( $order_data['language_3_code'] ) ? $languages[ $order_data['language_3_code'] ] : '';
	$order_data['language_3_skill_code']	= $order->get_meta( 'language3Skill' );
	$order_data['language_3_skill']			= isset( $language_skill[ $order_data['language_3_skill_code'] ] ) ? $language_skill[ $order_data['language_3_skill_code'] ] : '';

	/* Gegevens voor partner */
	$order_data['motivation']			= $order->get_meta( 'motivation' );
	$order_data['health_issues']		= $order->get_meta( 'healthIssues' );
	$order_data['volunteer_experience']	= $order->get_meta( 'volunteerExperience' );
	$order_data['together_with']		= $order->get_meta( 'togetherWith' );

	return $order_data;
}



/**
 * Reken bedrag in vreemde valuta om naar Euro
 * @param  string $currency
 * @param  float $amount
 * @param  int $decimals
 * @return float $amount_in_euro
 */
function siw_get_amount_in_euro( $currency, $amount, $decimals = 0 ) {

	$exchange_rate = siw_get_exchange_rate( $currency );
	if ( false == $exchange_rate ) {
		return false;
	}

	$amount_in_euro = (float) $amount * (float) $exchange_rate;
	$amount_in_euro = number_format( $amount_in_euro, $decimals, ',', '.' );
	return $amount_in_euro;
}


/**
 * Haal wisselkoers naar Euro op voor een specifieke valuta
 * @param  string $currency
 * @return float $exchange_rate
 */
function siw_get_exchange_rate( $currency ) {
	$month = date ( 'Ym' );
	$exchange_rates = get_transient( "siw_exchange_rates_{$month}" );

	if ( false === $exchange_rates ) {
		$exchange_rates = siw_get_exchange_rates( $month );
		if ( false == $exchange_rates ) {
			return false;
		}
		set_transient( "siw_exchange_rates_{$month}", $exchange_rates, MONTH_IN_SECONDS );
	}

	$exchange_rate = isset( $exchange_rates[ $currency ] ) ? $exchange_rates[ $currency ] : false;

	return $exchange_rate;
}


/**
 * Ophalen wisselkoersen bij belastingdienst voor een specifieke maand
 * @param  string $month
 * @return array
 */
function siw_get_exchange_rates( $month ) {
	$url = "https://www.belastingdienst.nl/data/douane_wisselkoersen/wks.douane.wisselkoersen.dd{$month}.xml";
	$args = array(
		'timeout'		=> 10,
		'redirection'	=> 0,
	);
	$response = wp_safe_remote_get( $url, $args );

	if ( is_wp_error( $response ) ) {
		return false; //TODO: foutafhandeling
	}

	$statuscode = wp_remote_retrieve_response_code( $response );
	if ( 200 != $statuscode ) {
		return false; //TODO: foutafhandeling
	}
	$body = simplexml_load_string( wp_remote_retrieve_body( $response ) );

	$exchange_rates = array();
	foreach ( $body->children() as $exchange_rate ) {
		if ( isset( $exchange_rate->muntCode ) ) {
			$currency = (string) $exchange_rate->muntCode;
			$rate = (string) $exchange_rate->tariefInEuro;
			$rate = floatval( str_replace(",", ".", $rate ) );
			$exchange_rates[$currency] = $rate;
		}
	}
	return $exchange_rates;
}
