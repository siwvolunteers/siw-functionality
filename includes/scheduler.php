<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
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
			$cron_job_ts = wp_next_scheduled( $scheduled_cron_job );
			wp_unschedule_event( $cron_job_ts, $scheduled_cron_job );
		}
	}

	/* Ophalen cronjobs via filter */
	$cron_jobs = apply_filters( 'siw_cron_jobs', array() );

	/* Bepaal cron timestamp */
	$cron_ts = strtotime( 'tomorrow ' . SIW_CRON_TS_GENERAL );
	$cron_ts_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $cron_ts ) ) . ' GMT' );

	/* Schedule alle jobs met 5 minuten interval */
	$counter = 0;
	$cron_job_interval = 5 * MINUTE_IN_SECONDS;

	foreach ( $cron_jobs as $cron_job ) {
		wp_schedule_event( $cron_ts_gmt + ( $counter * $cron_job_interval ) , 'daily', $cron_job );
		$counter++;
	}

	/* Sla alle scheduled jobs op in een optie */
	update_option( 'siw_scheduled_cron_jobs', $cron_jobs );

	/* Cache rebuild schedulen */
	$cache_rebuild_ts = strtotime( 'tomorrow ' . SIW_CRON_TS_REBUILD_CACHE );
	$cache_rebuild_ts_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $cache_rebuild_ts ) ) . ' GMT' );
	if ( wp_next_scheduled( 'siw_rebuild_cache' ) ) {
		$cron_job_ts = wp_next_scheduled( 'siw_rebuild_cache' );
		wp_unschedule_event( $cron_job_ts, 'siw_rebuild_cache' );
	}
	wp_schedule_event( $cache_rebuild_ts_gmt, 'daily', 'siw_rebuild_cache' );
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
