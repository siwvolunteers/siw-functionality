<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. overige configuratie
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'other',
	'title'          => __( 'Overig', 'siw' ),
	'settings_pages' => 'configuration',
	'tab'            => 'other',
	'fields'    => [
		[
			'type'    => 'heading',
			'name'    => __( 'Nieuwsbrief', 'siw' ),
		],
		[
			'id'      => 'newsletter_list',
			'name'    => __( 'Lijst', 'siw' ),
			'type'    => 'select',
			'tab'     => 'newsletter',
			'options' => siw_newsletter_get_lists(),
		],
	],
];

return $data;
