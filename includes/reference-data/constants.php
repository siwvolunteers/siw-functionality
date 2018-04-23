<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Organisatiegegevens */
define( 'SIW_NAME', 'SIW Internationale Vrijwilligersprojecten' ); //eventueel get_bloginfo( 'name' ) gebruiken
define( 'SIW_STATUTORY_NAME', 'Stichting Internationale Werkkampen' );
define( 'SIW_EMAIL', 'info@siw.nl' );
define( 'SIW_PHONE', '030-2317721' );
define( 'SIW_PHONE_FULL', '+31 30 2317721');
define( 'SIW_KVK', '41165368' );
define( 'SIW_IBAN', 'NL28 INGB 0000 0040 75' );
define( 'SIW_RSIN', '002817482' );
define( 'SIW_ADDRESS', 'Willemstraat 7' );
define( 'SIW_POSTAL_CODE', '3511 RJ' );
define( 'SIW_CITY', 'Utrecht' );
define( 'SIW_OPENING_HOURS', '9:00 - 17:00' );

/* Sociale netwerken */
define( 'SIW_FACEBOOK_URL', 'https://www.facebook.com/siwvolunteers' );
define( 'SIW_TWITTER_URL', 'https://twitter.com/siwvolunteers' );
define( 'SIW_LINKEDIN_URL', 'https://www.linkedin.com/company/siw' );
define( 'SIW_YOUTUBE_URL', 'https://www.youtube.com/user/SIWvolunteerprojects' );
define( 'SIW_INSTAGRAM_URL', 'https://www.instagram.com/siwvolunteers/' );

/* Configuratie */
define( 'SIW_NUMBER_OF_INFO_DAYS', 10 );
define( 'SIW_NUMBER_OF_EVS_DEADLINES', 5 );
define( 'SIW_EVS_WEEKS_BEFORE_DEPARTURE', 14 );
define( 'SIW_IP_WHITELIST_SIZE', 5 );
define( 'SIW_MAX_BOARD_MEMBERS', 7 );
define( 'SIW_MAX_ANNUAL_REPORTS', 5 );
define( 'SIW_MAX_DUTCH_PROJECTS', 10 );

/* Cron jobs */
define( 'SIW_CRON_TS_IMPORT_PROJECTS', '1:00' );
define( 'SIW_CRON_TS_UPDATE_FREE_PLACES', '0:30' );
define( 'SIW_CRON_TS_GENERAL', '03:00' );
define( 'SIW_CRON_TS_BACKUP_DB', '04:00' );
define( 'SIW_CRON_TS_BACKUP_FILES', '04:30' );
define( 'SIW_CRON_TS_REBUILD_CACHE', '05:00' );

/* Tarieven */
define( 'SIW_WORKCAMP_FEE_STUDENT', 225 );
define( 'SIW_WORKCAMP_FEE_REGULAR', 275 );
define( 'SIW_OP_MAAT_FEE_STUDENT', 349 );
define( 'SIW_OP_MAAT_FEE_REGULAR', 399 );
define( 'SIW_EVS_DEPOSIT', 155 );

/* Kortingen */
define( 'SIW_DISCOUNT_SECOND_PROJECT', 25 );
define( 'SIW_DISCOUNT_THIRD_PROJECT', 50 );

/* Constantes voor strings*/
define( 'BR', '<br/>' );
define( 'BR2', '<br/><br/>' );
define( 'SPACE', ' ' );

/* Constantes voor styling */
define( 'SIW_PRIMARY_COLOR', '#ff9900' );
define( 'SIW_PRIMARY_COLOR_HOVER', '#ffcc33' );
define( 'SIW_SECONDARY_COLOR', '#59ab9c' );
define( 'SIW_SECONDARY_COLOR_HOVER', '#8cdecf' );
define( 'SIW_FONT_COLOR', '#444' );

define( 'SIW_COLOR_EUROPE', '#007499' );
define( 'SIW_COLOR_ASIA', '#008e3f' );
define( 'SIW_COLOR_AFRICA', '#e30613' );
define( 'SIW_COLOR_LATIN_AMERICA', '#c7017f' );
define( 'SIW_COLOR_NORTH_AMERICA', '#fbba00' );

/* URL's voor externe services */
define( 'SIW_POSTCODE_API_URL','https://postcode-api.apiwise.nl/v2/addresses/' );
define( 'SIW_PLATO_WEBSERVICE_URL', 'http://www.workcamp-plato.org/files/services/ExternalSynchronization.asmx/' );
define( 'SIW_PLATO_BACKOFFICE_URL', 'http://www.workcamp-plato.org/' );
define( 'SIW_TRAVEL_ADVICE_BASE_URL', 'https://www.nederlandwereldwijd.nl/reizen/reisadviezen/' );
define( 'SIW_EXCHANGE_RATES_API_URL', 'https://data.fixer.io/api/' );

/**
 * Geeft een array met constantes en hun toelichting terug
 *
 * @return array
 */
function siw_get_constants() {
	$constants = array(
		'SIW_NAME' 						=> __( 'Naam', 'siw' ),
		'SIW_STATUTORY_NAME'			=> __( 'Statutaire naam', 'siw' ),
		'SIW_EMAIL'						=> __( 'E-mail', 'siw' ),
		'SIW_PHONE'						=> __( 'Telefoonnummer', 'siw' ),
		'SIW_PHONE_FULL'				=> __( 'Telefoonnummer (volledig)', 'siw' ),
		'SIW_KVK'						=> __( 'KVK', 'siw' ),
		'SIW_IBAN'						=> __( 'IBAN', 'siw' ),
		'SIW_RSIN'						=> __( 'RSIN', 'siw' ),
		'SIW_ADDRESS'					=> __( 'Adres', 'siw' ),
		'SIW_POSTAL_CODE'				=> __( 'Postcode', 'siw' ),
		'SIW_CITY'						=> __( 'Stad', 'siw' ),
		'SIW_OPENING_HOURS'				=> __( 'Openingstijden', 'siw' ),
		'SIW_FACEBOOK_URL'				=> __( 'Facebook URL', 'siw' ),
		'SIW_TWITTER_URL' 				=> __( 'Twitter URL', 'siw' ),
		'SIW_LINKEDIN_URL'				=> __( 'LinkedIn URL', 'siw' ),
		'SIW_YOUTUBE_URL'				=> __( 'YouTube URL', 'siw' ),
		'SIW_INSTAGRAM_URL'				=> __( 'Instagram URL', 'siw' ),
		'SIW_NUMBER_OF_INFO_DAYS' 		=> __( 'Maximum aantal infodagen', 'siw' ),
		'SIW_NUMBER_OF_EVS_DEADLINES'	=> __( 'Maximum aantal EVS-deadlines', 'siw' ),
		'SIW_EVS_WEEKS_BEFORE_DEPARTURE'=> __( 'EVS: weken voor vertrek', 'siw' ),
		'SIW_IP_WHITELIST_SIZE'			=> __( 'Maximale grootte IP-whitelist', 'siw' ),
		'SIW_MAX_DUTCH_PROJECTS'		=> __( 'Maximaal aantal Nederlandse Projecten', 'siw' ),
		'SIW_MAX_BOARD_MEMBERS'			=> __( 'Maximum aantal bestuursleden', 'siw' ),
		'SIW_MAX_ANNUAL_REPORTS'		=> __( 'Maximum aantal jaarverslagen', 'siw' ),
		'SIW_CRON_TS_IMPORT_PROJECTS'	=> __( 'Tijdstip import projecten', 'siw' ),
		'SIW_CRON_TS_UPDATE_FREE_PLACES'=> __( 'Tijdstip bijwerken vrije plaatsen', 'siw' ),
		'SIW_CRON_TS_GENERAL'			=> __( 'Tijdstip algemene cronjob', 'siw' ),
		'SIW_CRON_TS_BACKUP_DB'			=> __( 'Tijdstip backup database', 'siw' ),
		'SIW_CRON_TS_BACKUP_FILES'		=> __( 'Tijdstip backup bestanden', 'siw' ),
		'SIW_CRON_TS_REBUILD_CACHE'		=> __( 'Tijdstip cache verversen', 'siw' ),
		'SIW_WORKCAMP_FEE_STUDENT'		=> __( 'Inschrijfgeld Groepsproject (student)', 'siw' ),
		'SIW_WORKCAMP_FEE_REGULAR'		=> __( 'Inschrijfgeld Groepsproject (regulier)', 'siw' ),
		'SIW_OP_MAAT_FEE_STUDENT'		=> __( 'Inschrijfgeld Op Maat (student)', 'siw' ),
		'SIW_OP_MAAT_FEE_REGULAR'		=> __( 'Inschrijfgeld Op Maat (regulier)', 'siw' ),
		'SIW_EVS_DEPOSIT'				=> __( 'EVS-borg', 'siw' ),
		'SIW_DISCOUNT_SECOND_PROJECT' 	=> __( 'Kortingspercentage tweede project', 'siw' ),
		'SIW_DISCOUNT_THIRD_PROJECT'	=> __( 'Kortingspercentage derde project', 'siw' ),
		'SIW_POSTCODE_API_URL'			=> __( 'Postcode API URL', 'siw' ),
		'SIW_PLATO_WEBSERVICE_URL'		=> __( 'Plato webservice URL', 'siw' ),
		'SIW_TRAVEL_ADVICE_BASE_URL'	=> __( 'Reisadvies base-URL', 'siw' ),
		'SIW_EXCHANGE_RATES_API_URL'	=> __( 'Wisselkoersen API base-url', 'siw' ),
	);

	return $constants;
}
