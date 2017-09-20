<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * [siw_seo_set_noindex description]
 * @param int $post_id
 * @param bool $value
 */
function siw_seo_set_noindex( $post_id, $value = false ) {
	$noindex = $value ? 1 : 0;
	update_post_meta( $post_id, '_yoast_wpseo_meta-robots-noindex', $noindex );
}


/**
 * [siw_seo_set_title description]
 * @param [type] $post_id [description]
 * @param [type] $title   [description]
 */
function siw_seo_set_title( $post_id, $title ) {
	update_post_meta( $post_id, '_yoast_wpseo_title', $title );//TODO:aanpassen na switch naar SEO Framework
}


/**
 * [siw_seo_set_description description]
 * @param [type] $post_id     [description]
 * @param [type] $description [description]
 */
function siw_seo_set_description( $post_id, $description ) {
	update_post_meta( $post_id, '_yoast_wpseo_metadesc', $description ); //TODO: aanpassen na switch naar SEO Framework
}



