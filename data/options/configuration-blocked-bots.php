<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. configuratie
 * 
 * @package   SIW\Data
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'blocked-bots',
	'title'          => __( 'Geblokkeerde bots', 'siw' ),
	'settings_pages' => 'configuration',
	'context'        => 'side',
	'fields'         => [
		[
			'id'         => 'blocked_bots',
			'name'       => __( 'User agent', 'siw' ),
			'type'       => 'text',
			'clone'      => true,
			'add_button' => __( 'Toevoegen', 'siw' ),
		],
	],
];

return $data;