<?php
/**
 * Widgets
 *
 * @author      Maarten Bruna
 * @package     SIW\Widgets
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 */

add_filter( 'siw_widgets', function( $widgets ) {
	$widgets = [
		'accordion'            => 'Accordion',
		'calendar'             => 'Calendar',
		'contact'              => 'Contact',
		'cta'                  => 'CTA',
		'dutch-projects'       => 'Dutch_Projects',
		'features'             => 'Features',
		'map'                  => 'Map',
		'newsletter'           => 'Newsletter',
		'organisation'         => 'Organisation',
		'quick-search-form'    => 'Quick_Search_Form',
		'quick-search-results' => 'Quick_Search_Results',
		'quote'                => 'Quote',
	];
	return $widgets;
});
