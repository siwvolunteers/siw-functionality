<?php
/*
(c)2017-2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Stel cron jobs in na het updaten van de plugin */
add_action( 'siw_update_plugin', function() {

	/* Unschedulen van alle huidig cron jobs */
	$scheduled_cron_jobs = (array) get_option( 'siw_scheduled_cron_jobs' );
	foreach ( $scheduled_cron_jobs as $scheduled_cron_job ) {
		if ( wp_next_scheduled( $scheduled_cron_job ) ) {
			$timestamp = wp_next_scheduled( $scheduled_cron_job );
			wp_unschedule_event( $timestamp, $scheduled_cron_job );
		}
	}

	/* Ophalen cronjobs via filter */
	$cron_jobs = apply_filters( 'siw_cron_jobs', array() );

	/* Bepaal cron timestamp */
	$cron_ts = strtotime( 'tomorrow ' . SIW_CRON_TS_GENERAL );
	$cron_ts_gmt = siw_get_timestamp_in_gmt( $cron_ts );

	/* Schedule alle jobs met 5 minuten interval */
	$counter = 0;
	$cron_job_interval = 5 * MINUTE_IN_SECONDS;

	foreach ( $cron_jobs as $cron_job ) {
		wp_schedule_event( $cron_ts_gmt + ( $counter * $cron_job_interval ) , 'daily', $cron_job );
		$counter++;
	}

	/* Sla alle scheduled jobs op in een optie */
	update_option( 'siw_scheduled_cron_jobs', $cron_jobs, false );

	/* Cache rebuild schedulen */
	$cache_rebuild_ts = strtotime( 'tomorrow ' . SIW_CRON_TS_REBUILD_CACHE );
	$cache_rebuild_ts_gmt = siw_get_timestamp_in_gmt( $cache_rebuild_ts );
	if ( wp_next_scheduled( 'siw_rebuild_cache' ) ) {
		$timestamp = wp_next_scheduled( 'siw_rebuild_cache' );
		wp_unschedule_event( $timestamp, 'siw_rebuild_cache' );
	}
	wp_schedule_event( $cache_rebuild_ts_gmt, 'daily', 'siw_rebuild_cache' );


	/* FPL-import uit Plato schedulen */
	$plato_import_fpl_ts = strtotime( 'tomorrow ' . SIW_CRON_TS_UPDATE_FREE_PLACES );
	$plato_import_fpl_ts_gmt = siw_get_timestamp_in_gmt( $plato_import_fpl_ts );
	if ( wp_next_scheduled( 'siw_update_free_places' ) ) {
		$timestamp = wp_next_scheduled( 'siw_update_free_places' );
		wp_unschedule_event( $timestamp, 'siw_update_free_places' );
	}
	wp_schedule_event( $plato_import_fpl_ts_gmt, 'daily', 'siw_update_free_places' );	

} );


/* WP Rocket: Leeg de cache en vul deze opnieuw */
add_action( 'siw_rebuild_cache', function() {
	if ( defined( 'WP_ROCKET_VERSION' ) ) {
		rocket_clean_domain();
		run_rocket_sitemap_preload();
	}
} );


/* Sitemap genereren voor cache preload */
add_action( 'before_run_rocket_sitemap_preload', function( $sitemap_type, $sitemap_url ) {

	if ( ! function_exists( 'the_seo_framework' ) ) {
		return;
	} 
	$tsf = the_seo_framework();
	if ( $sitemap_url == $tsf->get_sitemap_xml_url() ) {
		$tsf->setup_sitemap();
	}

}, 10, 2 );
