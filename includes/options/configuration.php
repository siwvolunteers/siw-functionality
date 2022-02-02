<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Interfaces\Options\Option as Option_Interface;

use SIW\Util;

/**
 * Opties voor Configuratie
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class Configuration implements Option_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'configuration';
	}

	/** {@inheritDoc} */
	public function get_capability(): string {
		return 'manage_options';
	}
	
	/** {@inheritDoc} */
	public function get_parent_page(): string {
		return 'options-general.php';
	}

	/** {@inheritDoc} */
	public function get_title(): string {
		return __( 'Configuratie', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_tabs() : array {
		$tabs = [
			[
				'id'    => 'api',
				'label' => __( 'API', 'siw' ),
				'icon'  => 'dashicons-admin-network',
			],
			[
				'id'    => 'blacklists',
				'label' => __( 'Blacklists', 'siw' ),
				'icon'  => 'dashicons-shield',
			],
			[
				'id'    => 'email',
				'label' => __( 'Email', 'siw' ),
				'icon'  => 'dashicons-email',
			],
			[
				'id'    => 'pages',
				'label' => __( "Pagina's", 'siw' ),
				'icon'  => 'dashicons-admin-page',
			],
			[
				'id'    => 'other',
				'label' => __( 'Overig', 'siw' ),
				'icon'  => 'dashicons-admin-generic',
			],
		];
		return $tabs;
	}

	/** {@inheritDoc} */
	public function get_fields() : array {
		$fields = [];

		//API
		$fields[] = [
			'id'        => 'facebook',
			'type'      => 'group',
			'tab'       => 'api',
			'fields'    => [
				[
					'type'      => 'heading',
					'name'      => __( 'Facebook', 'siw' ),
				],
				[
					'id'        => 'pixel_id',
					'name'      => __( 'Pixel ID', 'siw' ),
					'type'      => 'text',
					'size'      => 60,
				],
			],
		];
		$fields[] = [
			'id'        => 'google_analytics',
			'type'      => 'group',
			'tab'       => 'api',
			'fields'    => [
				[
					'type'      => 'heading',
					'name'      => __( 'Google Analytics', 'siw' ),
				],
				[
					'id'        => 'property_id',
					'name'      => __( 'Property ID', 'siw' ),
					'type'      => 'text',
					'size'      => 60,
				],
			],
		];
		$fields[] = [
			'id'        => 'google_maps',
			'type'      => 'group',
			'tab'       => 'api',
			'fields'    => [
				[
					'type'      => 'heading',
					'name'      => __( 'Google Maps', 'siw' ),
				],
				[
					'id'        => 'api_key',
					'name'      => __( 'Client side API Key', 'siw' ),
					'type'      => 'text',
					'size'      => 60,
				],
				[
					'id'        => 'server_side_api_key',
					'name'      => __( 'Server side API Key', 'siw' ),
					'type'      => 'text',
					'size'      => 60,
				],
			],
		];
		$fields[] = [
			'id'        => 'fixer',
			'type'      => 'group',
			'tab'       => 'api',
			'fields'    => [
				[
					'type'      => 'heading',
					'name'      => __( 'Fixer.io', 'siw' ),
					'desc'      => __( 'Wisselkoersen', 'siw' ),
				],
				[
					'id'        => 'api_key',
					'name'      => __( 'API Key', 'siw' ),
					'type'      => 'text',
					'size'      => 60,
				],
			],
		];
		$fields[] = [
			'id'        => 'mailjet',
			'type'      => 'group',
			'tab'       => 'api',
			'fields'    => [
				[
					'type'      => 'heading',
					'name'      => __( 'Mailjet', 'siw' ),
				],
				[
					'id'        => 'api_key',
					'name'      => __( 'API Key', 'siw' ),
					'type'      => 'text',
					'size'      => 60,
				],
				[
					'id'        => 'secret_key',
					'name'      => __( 'Secret Key', 'siw' ),
					'type'      => 'text',
					'size'      => 60,
				],
			],
		];
		$fields[] = [
			'id'        => 'plato',
			'type'      => 'group',
			'tab'       => 'api',
			'fields'    => [
				[
					'type'      => 'heading',
					'name'      => __( 'Plato', 'siw' ),
				],
				[
					'id'        => 'organization_webkey',
					'name'      => __( 'Organization webkey', 'siw' ),
					'type'      => 'text',
					'size'      => 60,
				],
				[
					'id'        => 'force_full_update',
					'name'      => __( 'Forceer volledige update', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Aan', 'siw' ),
					'off_label' => __( 'Uit', 'siw'),
				],
				[
					'id'        => 'download_images',
					'name'      => __( 'Download afbeeldingen', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Aan', 'siw' ),
					'off_label' => __( 'Uit', 'siw'),
				],
			],
		];

		//Email
		$fields[] = [
			'name'      => __( 'SMTP', 'siw' ),
			'type'      => 'heading',
			'tab'       => 'email',
		];
		$fields[] = [
			'id'        => 'smtp_enabled',
			'name'      => __( 'Inschakelen', 'siw' ),
			'tab'       => 'email',
			'type'      => 'switch',
			'on_label'  => __( 'Aan', 'siw' ),
			'off_label' => __( 'Uit', 'siw'),
		];
		$fields[] = [
			'id'        => 'smtp_settings',
			'type'      => 'group',
			'tab'       => 'email',
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
					'tab'       => 'email',
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
		];
		$fields[] = [
			'name'      => __( 'DKIM', 'siw' ),
			'type'      => 'heading',
			'tab'       => 'email',
		];
		$fields[] = [
			'id'        => 'dkim_enabled',
			'name'      => __( 'Inschakelen', 'siw' ),
			'type'      => 'switch',
			'tab'       => 'email',
			'on_label'  => __( 'Aan', 'siw' ),
			'off_label' => __( 'Uit', 'siw'),
		];
		$fields[] = [
			'id'      => 'dkim_settings',
			'type'    => 'group',
			'tab'     => 'email',
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
		];
		$fields[] = [
			'id'        => 'pages',
			'type'      => 'group',
			'tab'       => 'pages',
			'fields'    => [
				[ 
					'id'     => 'explanation',
					'type'   => 'group',
					'fields' => [
						[
							'type'    => 'heading',
							'name'    => __( 'Zo werkt het', 'siw' ),
						],
						[
							'id'      => 'workcamps',
							'name'    => __( 'Groepsprojecten', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
						[
							'id'      => 'info_days',
							'name'    => __( 'Infodagen', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
						[
							'id'      => 'esc',
							'name'    => __( 'ESC', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
						[
							'id'      => 'tailor_made',
							'name'    => __( 'Op Maat', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
						[
							'id'      => 'school_projects',
							'name'    => __( 'Scholenprojecten', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
					],
				],
				[
					'type' => 'heading',
					'name' => __( 'Overig', 'siw' ),
				],
				[
					'id'      => 'contact',
					'name'    => __( 'Contact', 'siw' ),
					'type'    => 'select_advanced',
					'options' => Util::get_pages(),
				],
				[
					'id'      => 'child_policy',
					'name'    => __( 'Kinderbeleid', 'siw' ),
					'type'    => 'select_advanced',
					'options' => Util::get_pages(),
				],
				[
					'id'      => 'newsletter_confirmation',
					'name'    => __( 'Bevestiging aanmelding nieuwsbrief', 'siw' ),
					'type'    => 'select_advanced',
					'options' => Util::get_pages(),
				]
			],
		];

		//Blacklists
		$fields[] = [
			'type'       => 'heading',
			'name'       => __( 'Bot blacklist', 'siw' ),
			'tab'        => 'blacklists',
		];
		$fields[] = [
			'id'         => 'blocked_bots',
			'type'       => 'text',
			'tab'        => 'blacklists',
			'clone'      => true,
			'add_button' => __( 'User agent toevoegen', 'siw' ),
		];

		//Overig
		$fields[] = [
			'type'    => 'heading',
			'name'    => __( 'Nieuwsbrief', 'siw' ),
			'tab'     => 'other',
		];
		$fields[] = [
			'id'      => 'newsletter_list',
			'name'    => __( 'Lijst', 'siw' ),
			'type'    => 'select',
			'tab'     => 'other',
			'options' => siw_newsletter_get_lists(),
		];
		return $fields;
	}
}
