<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Util;

/**
 * Opties t.b.v. black en whitelists
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'black_whitelists',
	'title'          => __( 'Black/Whitelist', 'siw' ),
	'settings_pages' => 'configuration',
	'tab'            => 'black-white-lists',
	'fields' => [
		[
			'type'        => 'heading',
			'name'        => __( 'IP whitelist', 'siw' ),
		],
		[
			'id'          => 'ip_whitelist',
			'type'        => 'text',
			'clone'       => true,
			'pattern'     => Util::get_pattern('ip'),
			'placeholder' => '192.168.0.1',
			'add_button'  => __( 'IP toevoegen', 'siw' ),
		],
		[
			'type'       => 'heading',
			'name'       => __( 'Bot blacklist', 'siw' ),
		],
		[
			'id'         => 'blocked_bots',
			'type'       => 'text',
			'clone'      => true,
			'add_button' => __( 'User agent toevoegen', 'siw' ),
		],
		[
			'type'       => 'heading',
			'name'       => __( 'Domein blacklist', 'siw' ),
		],
		[
			'id'         => 'blocked_domains',
			'type'       => 'text',
			'clone'      => true,
			'add_button' => __( 'Domein toevoegen', 'siw' ),
		],
	],
];

return $data;
