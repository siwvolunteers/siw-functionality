<?php
/*
(c)2017-2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( __DIR__ . '/chart.php' );
require_once( __DIR__ . '/general.php' );
require_once( __DIR__ . '/lightbox.php' );
require_once( __DIR__ . '/quick-search-results.php' );

/**
 * Hulpfunctie om shortcode toe te voegen aan pinnacle menu
 *
 * @param string $shortcode
 * @param array $parameters
 * @return void
 */
function siw_add_shortcode( $shortcode, $parameters ) {
	add_filter( 'kadence_shortcodes', function( $pinnacle_shortcodes ) use( $shortcode, $parameters ) {
		$parameters['title'] = '[SIW] - ' . $parameters['title'];
		$pinnacle_shortcodes[ $shortcode ] = $parameters;
		return $pinnacle_shortcodes;
	});
}
