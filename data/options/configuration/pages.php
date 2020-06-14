<?php

use SIW\Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. configuratie
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo   groups
 */

$data = [
	'id'             => 'pages',
	'title'          => __( "Pagina's", 'siw' ),
	'settings_pages' => 'configuration',
	'tab'            => 'pages',
	'fields'         => [
		[
			'type'    => 'heading',
			'name'    => __( 'Zo werkt het', 'siw' ),
		],
		[
			'id'      => 'workcamps_explanation_page',
			'name'    => __( 'Groepsprojecten', 'siw' ),
			'type'    => 'select_advanced',
			'options' => Util::get_pages(),
		],
		[
			'id'      => 'info_days_explanation_page',
			'name'    => __( 'Infodagen', 'siw' ),
			'type'    => 'select_advanced',
			'options' => Util::get_pages(),
		],
		[
			'id'      => 'esc_explanation_page',
			'name'    => __( 'ESC', 'siw' ),
			'type'    => 'select_advanced',
			'options' => Util::get_pages(),
		],
		[
			'id'      => 'tailor_made_explanation_page',
			'name'    => __( 'Op Maat', 'siw' ),
			'type'    => 'select_advanced',
			'options' => Util::get_pages(),
		],
		[
			'type'    => 'heading',
			'name'    => __( 'Overig', 'siw' ),
		],
		[
			'id'      => 'contact_page',
			'name'    => __( 'Contact', 'siw' ),
			'type'    => 'select_advanced',
			'options' => Util::get_pages(),
		],
		[
			'id'      => 'child_policy_page',
			'name'    => __( 'Kinderbeleid', 'siw' ),
			'type'    => 'select_advanced',
			'options' => Util::get_pages(),
		],
	],
];

return $data;
