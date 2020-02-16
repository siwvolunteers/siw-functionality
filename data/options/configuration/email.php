<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. configuratie van email
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'email',
	'title'          => __( 'E-mail', 'siw' ),
	'settings_pages' => 'configuration',
	'tab'            => 'email',
	'fields'    => [
		[
			'name'      => __( 'SMTP', 'siw' ),
			'type'      => 'heading',
		],
		[
			'id'        => 'smtp_enabled',
			'name'      => __( 'Inschakelen', 'siw' ),
			'type'      => 'switch',
			'on_label'  => __( 'Aan', 'siw' ),
			'off_label' => __( 'Uit', 'siw'),
		],
		[
			'id'        => 'smtp_settings',
			'type'      => 'group',
			'visible'   => [ 'smtp_enabled', true ],
			'fields'    => [
				[
					'id'       => 'host',
					'name'     => __( 'Host', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => 'port',
					'name'     => __( 'Poort', 'siw' ),
					'type'     => 'number',
					'required' => true,
				],
				[
					'id'       => 'encryption',
					'name'     => __( 'Encryptie', 'siw' ),
					'type'     => 'button_group',
					'required' => true,
					'options'  => [
						'none'  => __( 'Geen', 'siw' ),
						'ssl'   => __( 'SSL', 'siw' ),
						'tls'   => __( 'TLS', 'siw' ),
					]
				],
				[
					'id'        => 'authentication',
					'name'      => __( 'Authenticatie', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Aan', 'siw' ),
					'off_label' => __( 'Uit', 'siw'),
				],
				[
					'id'      => 'username',
					'name'    => __( 'Gebruikersnaam', 'siw' ),
					'type'    => 'text',
					'required' => true,
					'visible' => [ 'smtp_settings[authentication]', true ],
				],
				[
					'id'      => 'password',
					'name'    => __( 'Wachtwoord', 'siw' ),
					'type'    => 'text',
					'required' => true,
					'visible' => [ 'smtp_settings[authentication]', true ],
				],
			],
		],
		[
			'name'      => __( 'DKIM', 'siw' ),
			'type'      => 'heading',
		],
		[
			'id'        => 'dkim_enabled',
			'name'      => __( 'Inschakelen', 'siw' ),
			'type'      => 'switch',
			'on_label'  => __( 'Aan', 'siw' ),
			'off_label' => __( 'Uit', 'siw'),
		],
		[
			'id'      => 'dkim_settings',
			'type'    => 'group',
			'visible' => [ 'dkim_enabled', true ],
			'fields'  => [
				[
					'id'       => 'selector',
					'name'     => __( 'Selector', 'siw' ),
					'type'     => 'text',
					'required' => true,

				],
				[
					'id'       => 'domain',
					'name'     => __( 'Domein', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => 'key',
					'name'     => __( 'Key', 'siw' ),
					'type'     => 'textarea',
					'cols'     => 65,
					'rows'     => 20,
					'required' => true,
				],
			],
		],
	],
];

return $data;
