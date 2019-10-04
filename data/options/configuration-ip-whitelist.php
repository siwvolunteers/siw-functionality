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
	'id'             => 'ip-whitelist',
	'title'          => __( 'IP Whitelist', 'siw' ),
	'settings_pages' => 'configuration',
	'context'        => 'side',
	'fields' => [
		[
			'id'          => 'ip_whitelist',
			'name'        => __( 'IP', 'siw' ),
			'type'        => 'text',
			'clone'       => true,
			'pattern'     => SIW_Util::get_pattern('ip'),
			'placeholder' => '192.168.0.1',
			'add_button'  => __( 'Toevoegen', 'siw' ),
		],
	],
];

return $data;
