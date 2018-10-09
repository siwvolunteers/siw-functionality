<?php
/**
 * Functies m.b.t. widgets
 * 
 * @author      Maarten Bruna
 * @package 	SIW\Widgets
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 */

namespace SIW;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Widgets
 * - Agenda
 * - Contactgegevens
 * - Quotes van deelnemers
 * - Nieuwsbrief
 */
require_once( __DIR__ . '/agenda.php' );
require_once( __DIR__ . '/contact.php' );
require_once( __DIR__ . '/cta.php' );
require_once( __DIR__ . '/newsletter.php' );
require_once( __DIR__ . '/quote.php' );
require_once( __DIR__ . '/quick-search.php' );


/* SiteOrigin Widgets bundle overschrijven door eigen widgets */
add_filter( 'siteorigin_widgets_widget_folders', function( $folders ) {
	$folders = array();
	$folders[] =  __DIR__ . '/';
    return $folders;
});
