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
	'id'             => 'email',
	'title'          => __( 'E-mail', 'siw' ),
	'settings_pages' => 'configuration',
	'tabs'           =>[
		'smtp' => __( 'SMTP', 'siw' ),
		'dkim' => __( 'DKIM', 'siw' ),
	],
	'tab_style' => 'left',
	'fields'    => [
		[
			'name'      => __( 'SMTP', 'siw' ),
			'type'      => 'heading',
			'tab'       => 'smtp',
		],
		[
			'id'        => 'smtp_enabled',
			'name'      => __( 'Inschakelen', 'siw' ),
			'type'      => 'switch',
			'tab'       => 'smtp',
			'on_label'  => __( 'Aan', 'siw' ),
			'off_label' => __( 'Uit', 'siw'),
		],
		[
			'id'        => 'smtp_settings',
			'type'      => 'group',
			'tab'       => 'smtp',
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
					'id'      => 'encryption',
					'name'    => __( 'Encryptie', 'siw' ),
					'type'    => 'button_group',
					'options' => [
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
						'visible' => [
							[ 'smtp_settings[authentication]', true ],
					],
				],
				[
					'id'      => 'password',
					'name'    => __( 'Wachtwoord', 'siw' ),
					'type'    => 'text',
					'visible' => [
						[ 'smtp_settings[authentication]', true ],
					],
				],
			],
		],
		[
			'name'      => __( 'DKIM', 'siw' ),
			'type'      => 'heading',
			'tab'       => 'dkim',
		],
		[
			'id'        => 'dkim_enabled',
			'name'      => __( 'DKIM', 'siw' ),
			'type'      => 'switch',
			'on_label'  => __( 'Aan', 'siw' ),
			'off_label' => __( 'Uit', 'siw'),
			'tab'       => 'dkim',
		],
		[
			'id'      => 'dkim_settings',
			'type'    => 'group',
			'tab'     => 'dkim',
			'visible' => [ 'dkim_enabled', true ],
			'fields'  => [
				[
					'id'       => 'selector',
					'name'     => __( 'Selector', 'siw' ),
					'type'     => 'text',
					'required' => true,

				],
				[
					'id'      => 'domain',
					'name'    => __( 'Domein', 'siw' ),
					'type'    => 'text',
					'required' => true,
				],
				[
					'id'      => 'passphrase',
					'name'    => __( 'Passphrase', 'siw' ),
					'type'    => 'text',
					'required' => true,
				],
			],
		],
	],
];

return $data;
