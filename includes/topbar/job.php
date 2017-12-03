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

	$meta_query = array(
		'relation'	=> 'AND',
		array(
			'key'		=> 'siw_vacature_deadline',
			'value'		=> time(),
			'compare'	=> '>=',
		),
		array(
			'key'		=> 'siw_vacature_uitgelicht',
			'value'		=> 'on',
			'compare'	=> '=',
		),
	);
	$query_args = array(
		'post_type'				=>	'vacatures',
		'posts_per_page'		=>	1,
		'post_status'			=>	'publish',
		'ignore_sticky_posts'	=>	true,
		'meta_key'				=>	'siw_vacature_deadline',
		'orderby'				=>	'meta_value_num',
		'order'					=>	'ASC',
		'meta_query'			=>	$meta_query,
		'fields' 				=> 'ids'
	);
	$featured_job_id = get_posts( $query_args );
	if ( empty ( $featured_job_id ) ) {
		return false;
	}
	$job = siw_get_job_data( $featured_job_id[0] );
	$job_title = lcfirst( $job['title'] );
	$topbar_job_content['intro'] = __( 'Word actief voor SIW.', 'siw' );
	$topbar_job_content['link_url'] = $job['permalink'];
	$topbar_job_content['link_text'] = sprintf( __( 'Wij zoeken een %s', 'siw' ), $job_title );

	return $topbar_job_content;
}
