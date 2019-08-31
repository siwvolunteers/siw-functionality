<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. configuratie
 * 
 * @package   SIW\Options
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

add_filter( 'siw_settings_pages', function( $pages ) {
	$pages[] = [
		'id'          => 'siw-options-configuration',
		'menu_title'  => __( 'Configuratie', 'siw' ),
		'capability'  => 'manage_options',
	];
	return $pages;
});

add_filter( 'siw_settings_meta_boxes', function( $meta_boxes ) {

	//Zoek MailPoet-lijsten
	if ( class_exists( 'WYSIJA' ) ) {
		$model_list = WYSIJA::get( 'list','model' );
		$lists = $model_list->get( ['name','list_id' ], ['is_enabled' => 1] );
		foreach ( $lists as $list ) {
			$mailpoet_lists[ $list['list_id'] ] = $list['name'];
		}
	}
	else {
		$mailpoet_lists = [] ;
	}

	$meta_boxes[] = [
		'id'             => 'configuration',
		'title'          => __( 'Configuratie', 'siw' ),
		'settings_pages' => 'siw-options-configuration',
		'tabs'           => [
			'analytics'    => __( 'Analytics', 'siw' ),
			'api'          => __( 'API keys', 'siw' ),
			'forms'        => __( 'Formulieren', 'siw' ),
			'newsletter'   => __( 'Nieuwsbrief', 'siw' ),
			'plato'        => __( 'Plato', 'siw' ),
			'verification' => __( 'Website verificatie', 'siw' ),
		],
		'tab_style'      => 'left',
		'fields' => [
			[
				'id'    => 'google_analytics_property_id',
				'name'  => __( 'Google Analytics Property ID', 'siw' ),
				'type'  => 'text',
				'tab'   => 'analytics',
				'size'  => 60,
			],
			[
				'id'                => 'exchange_rates_api_key',
				'name'              => __( 'Wisselkoersen API Key', 'siw' ),
				'type'              => 'text',
				'tab'               => 'api',
				'size'              => 60,
				'label_description' => 'https://fixer.io/',
			],
			[
				'id'                => 'google_maps_api_key',
				'name'              => __( 'Google Maps API Key', 'siw' ),
				'type'              => 'text',
				'tab'               => 'api',
				'size'              => 60,
				'label_description' => 'https://cloud.google.com/maps-platform/maps/',
			],
			[
				'id'                => 'plato_organization_webkey',
				'name'              => __( 'Organization webkey', 'siw' ),
				'type'              => 'text',
				'tab'               => 'plato',
				'size'              => 60,
			],
			[
				'id'                => 'plato_production_mode',
				'name'              => __( 'Productie-mode', 'siw' ),
				'type'              => 'switch',
				'tab'               => 'plato',
				'on_label'          => __( 'Aan', 'siw' ),
				'off_label'         => __( 'Uit', 'siw'),
			],
			[
				'id'                => 'plato_force_full_update',
				'name'              => __( 'Forceer volledige update', 'siw' ),
				'type'              => 'switch',
				'tab'               => 'plato',
				'on_label'          => __( 'Aan', 'siw' ),
				'off_label'         => __( 'Uit', 'siw'),
			],
			[
				'id'      => 'spam_check_mode',
				'name'    => __( 'Spam check mode', 'siw' ),
				'type'    => 'button_group',
				'tab'     => 'forms',
				'options' => [
					'report'  => __( 'Rapporteren', 'siw' ),
					'block'   => __( 'Blokkeren', 'siw' ),
				]
			],
			[
				'id'    => 'google_verification',
				'name'  => __( 'Google Search Console', 'siw' ),
				'type'  => 'text',
				'tab'   => 'verification',
				'size'  => 60,
			],
			[
				'id'    => 'bing_verification',
				'name'  => __( 'Bing Webmaster Tools', 'siw' ),
				'type'  => 'text',
				'tab'   => 'verification',
				'size'  => 60,
			],
			[
				'id'      => 'newsletter_list',
				'name'    => __( 'Lijst', 'siw' ),
				'type'    => 'select',
				'tab'     => 'newsletter',
				'options' => $mailpoet_lists,
			],
		],
	];
	$meta_boxes[] = [
		'id'             => 'email',
		'title'          => __( 'E-mail', 'siw' ),
		'settings_pages' => 'siw-options-configuration',
		'tabs' =>[
			'smtp' => __( 'SMTP', 'siw' ),
			'dkim' => __( 'DKIM', 'siw' ),
		],
		'tab_style' => 'left',
		'fields' => [
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
	$meta_boxes[] = [
		'id'             => 'blocked-bots',
		'title'          => __( 'Geblokkeerde bots', 'siw' ),
		'settings_pages' => 'siw-options-configuration',
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
	$meta_boxes[] = [
		'id'             => 'pages',
		'title'          => __( 'Pagina\'s', 'siw' ),
		'settings_pages' => 'siw-options-configuration',
		'tabs'           => [
			'archive'      => __( 'Archief', 'siw' ),
			'explanation'  => __( 'Zo werkt het', 'siw' ),
			'other'        => __( 'Overig', 'siw' ),
		],
		'tab_style'      => 'left',
		'fields' => [

			[
				'id'      => 'events_archive_page',
				'name'    => __( 'Evenementen', 'siw' ),
				'type'    => 'select_advanced',
				'tab'     => 'archive',
				'options' => SIW_Util::get_pages(),
			],
			[
				'id'      => 'job_postings_archive_page',
				'name'    => __( 'Vacatures', 'siw' ),
				'type'    => 'select_advanced',
				'tab'     => 'archive',
				'options' => SIW_Util::get_pages(),
			],
			[
				'id'      => 'workcamps_explanation_page',
				'name'    => __( 'Groepsprojecten', 'siw' ),
				'type'    => 'select_advanced',
				'tab'     => 'explanation',
				'options' => SIW_Util::get_pages(),
			],
			[
				'id'      => 'info_days_explanation_page',
				'name'    => __( 'Infodagen', 'siw' ),
				'type'    => 'select_advanced',
				'tab'     => 'explanation',
				'options' => SIW_Util::get_pages(),
			],
			[
				'id'      => 'esc_explanation_page',
				'name'    => __( 'ESC', 'siw' ),
				'type'    => 'select_advanced',
				'tab'     => 'explanation',
				'options' => SIW_Util::get_pages(),
			],
			[
				'id'      => 'tailor_made_explanation_page',
				'name'    => __( 'Op Maat', 'siw' ),
				'type'    => 'select_advanced',
				'tab'     => 'explanation',
				'options' => SIW_Util::get_pages(),
			],
			[
				'id'      => 'contact_page',
				'name'    => __( 'Contact', 'siw' ),
				'type'    => 'select_advanced',
				'tab'     => 'other',
				'options' => SIW_Util::get_pages(),
			],
			[
				'id'      => 'quick_search_results_page',
				'name'    => __( 'Resultaten Snel Zoeken', 'siw' ),
				'type'    => 'select_advanced',
				'tab'     => 'other',
				'options' => SIW_Util::get_pages(),
			],
			[
				'id'      => 'child_policy_page',
				'name'    => __( 'Kinderbeleid', 'siw' ),
				'type'    => 'select_advanced',
				'tab'     => 'other',
				'options' => SIW_Util::get_pages(),
			],
			
		],

	];
	$meta_boxes[] = [
		'id'             => 'ip-whitelist',
		'title'          => __( 'IP Whitelist', 'siw' ),
		'settings_pages' => 'siw-options-configuration',
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

	return $meta_boxes;
});
