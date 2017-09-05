<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Stel cron jobs in na het updaten van de plugin */
add_action( 'wppusher_plugin_was_updated', function() {
	$cron_jobs = array(
		'siw_no_index_past_events'						=> 0,
		'siw_no_index_expired_jobs' 					=> 0,
		'siw_update_community_day_options'				=> 0,
		'siw_update_workcamp_tariffs'					=> 5,
		'siw_delete_orphaned_variations'				=> 10,
		'siw_delete_projects'							=> 15,
		'siw_cleanup_terms'								=> 40,
		'siw_reorder_rename_product_attribute_month'	=> 50,
	);
	$cron_ts = strtotime( 'tomorrow ' . SIW_CRON_TS_GENERAL );
	$cron_ts_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $cron_ts ) ) . ' GMT' );
	foreach ( $cron_jobs as $cron_job => $minutes ) {
		if ( wp_next_scheduled( $cron_job ) ) {
			$cron_job_ts = wp_next_scheduled( $cron_job );
			wp_unschedule_event( $cron_job_ts, $cron_job );
		}
		wp_schedule_event( $cron_ts_gmt + ( $minutes * MINUTE_IN_SECONDS ) , 'daily', $cron_job );
	}

	$cache_rebuild_ts = strtotime( 'tomorrow ' . SIW_CRON_TS_REBUILD_CACHE );
	$cache_rebuild_ts_gmt = strtotime( get_gmt_from_date( date( 'Y-m-d H:i:s', $cache_rebuild_ts ) ) . ' GMT' );
	if ( wp_next_scheduled( 'siw_rebuild_cache' ) ) {
		$cron_job_ts = wp_next_scheduled( 'siw_rebuild_cache' );
		wp_unschedule_event( $cron_job_ts, 'siw_rebuild_cache' );
	}
	wp_schedule_event( $cache_rebuild_ts_gmt, 'daily', 'siw_rebuild_cache' );
} );


/* Zet noindex voor evenementen die al begonnen zijn */
add_action( 'siw_no_index_past_events', function() {
	$args = array(
		'post_type'			=> 'agenda',
		'fields'			=> 'ids',
		'posts_per_page'	=> -1,
	);
	$event_ids = get_posts( $args );
	foreach ( $event_ids as $event_id ) {
		$noindex = 0;
		$start_ts = get_post_meta( $event_id, 'siw_agenda_start', true );
		if ( $start_ts < time() ) {//TODO:vergelijken datum i.p.v. ts
			$noindex = 1;
		}
		siw_seo_set_noindex( $event_id, $noindex );
	}
} );


/* Zet noindex voor evenementen waarvan de deadline verstreken is */
add_action( 'siw_no_index_expired_jobs', function() {
	$args = array(
		'post_type'			=> 'vacatures',
		'fields'			=> 'ids',
		'posts_per_page'	=> -1,
	);
	$job_ids = get_posts( $args );
	foreach ( $job_ids as $job_id ) {
		$noindex = 0;
		$deadline_ts = get_post_meta( $job_id, 'siw_vacature_deadline', true );
		if ( $deadline_ts < time() ) {//TODO:vergelijken datum i.p.v. ts
			$noindex = 1;
			//TODO:uitgelicht op off zetten
		}
		siw_seo_set_noindex( $job_id, $noindex );
	}
} );


/* WP Rocket: Leeg de cache en vul deze opnieuw */
add_action( 'siw_rebuild_cache', function() {
	if ( defined( 'WP_ROCKET_VERSION' ) ) {
		rocket_clean_domain();
		run_rocket_sitemap_preload();
	}
} );
