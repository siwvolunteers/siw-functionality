<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [siw_get_topbar_job_content description]
 * @return [type] [description]
 */
function siw_get_topbar_job_content() {

	$job = siw_get_featured_job();
	if ( false == $job ) {
		return false;
	}
	$job_title = lcfirst( $job['title'] );
	$topbar_job_content['intro'] = __( 'Word actief voor SIW.', 'siw' );
	$topbar_job_content['link_url'] = $job['permalink'];
	$topbar_job_content['link_text'] = sprintf( __( 'Wij zoeken een %s', 'siw' ), $job_title );

	return $topbar_job_content;
}
