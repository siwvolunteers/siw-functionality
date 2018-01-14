<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Organisatiegegevens */
define ( 'SIW_NAME', 'SIW Internationale Vrijwilligersprojecten' ); //eventueel get_bloginfo( 'name' ) gebruiken
define ( 'SIW_STATUTORY_NAME', 'Stichting Internationale Werkkampen' );
define ( 'SIW_EMAIL', 'info@siw.nl' );
define ( 'SIW_PHONE', '030-2317721' );
define ( 'SIW_PHONE_FULL', '+31302317721');
define ( 'SIW_KVK', '41165368' );
define ( 'SIW_IBAN', 'NL28 INGB 0000 0040 75' );
define ( 'SIW_RSIN', '002817482' );
define ( 'SIW_ADDRESS', 'Willemstraat 7' );
define ( 'SIW_POSTAL_CODE', '3511 RJ' );
define ( 'SIW_CITY', 'Utrecht' );
define ( 'SIW_OPENING_HOURS', '9:00 - 17:00' );

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

/* Cron jobs */
define( 'SIW_CRON_TS_GENERAL', '03:00' );
define( 'SIW_CRON_TS_BACKUP_DB', '04:00' );
define( 'SIW_CRON_TS_BACKUP_FILES', '04:30' ); //nog niet gebruikt
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
		'SIW_CRON_TS_GENERAL'			=> __( 'Tijd algemene cronjob', 'siw' ),
		'SIW_CRON_TS_BACKUP_DB'			=> __( 'Tijdstip backup database', 'siw' ),
		//'SIW_CRON_TS_BACKUP_FILES'	=> __( 'Tijdstip backup bestanden', 'siw' ),
		'SIW_CRON_TS_REBUILD_CACHE'		=> __( 'Tijdstip cache verversen', 'siw' ),
		'SIW_WORKCAMP_FEE_STUDENT'		=> __( 'Inschrijfgeld groepsproject (student)', 'siw' ),
		'SIW_WORKCAMP_FEE_REGULAR'		=> __( 'Inschrijfgeld groepsproject (regulier)', 'siw' ),
		'SIW_OP_MAAT_FEE_STUDENT'		=> __( 'Inschrijfgeld Op Maat (student)', 'siw' ),
		'SIW_OP_MAAT_FEE_REGULAR'		=> __( 'Inschrijfgeld Op Maat (regulier)', 'siw' ),
		'SIW_EVS_DEPOSIT'				=> __( 'EVS-borg', 'siw' ),
		'SIW_DISCOUNT_SECOND_PROJECT' 	=> __( 'Kortingspercentage tweede project', 'siw' ),
		'SIW_DISCOUNT_THIRD_PROJECT'	=> __( 'Kortingspercentage derde project', 'siw' ),
	);

	return $constants;
}
