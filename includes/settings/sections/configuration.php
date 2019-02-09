<?php
/*
 * (c)2016-2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Geeft array met WPAI imports terug
 * 
 * @deprecated
 *
 * @return array
 */
function siw_get_wpai_imports() {
	global $wpdb;
	if ( ! isset( $wpdb->pmxi_imports ) ) {
		$wpdb->pmxi_imports = $wpdb->prefix . 'pmxi_imports';
	}
	$query = "SELECT $wpdb->pmxi_imports.id, $wpdb->pmxi_imports.friendly_name, $wpdb->pmxi_imports.name FROM $wpdb->pmxi_imports ORDER BY $wpdb->pmxi_imports.friendly_name ASC";
	$results = $wpdb->get_results( $query, ARRAY_A);
	foreach ( $results as $result ) {
		$imports[$result['id']] = esc_html( $result['friendly_name'] . ' (' . $result['name'] . ')' );
	}
	return $imports;
}


add_action( 'siw_settings_show_configuration_section', function() {
	/*
	 * Hulpgegevens
	 * - WP All Imports
	 * - Pagina's
	*/
	$imports = siw_get_wpai_imports();
	$pages = SIW_Util::get_pages();

	$analytics_seo_fields = array(
		array(
			'id'			=> 'google_analytics_section_start',
			'type'			=> 'section',
			'title'			=> __( 'Google Analytics', 'siw' ),
			'indent' 		=> true,
		),
		array(
			'id'			=> 'google_analytics_id',
			'title'			=> __( 'Property ID', 'siw' ),
			'type'			=> 'text',
			'placeholder'	=> 'UA-1234567-8',
			'validate'		=> 'no_special_chars',
		),
		array(
			'id'			=> 'google_analytics_enable_linkid',
			'title'			=> __( 'Enhanced link attribution', 'siw' ),
			'type'			=> 'switch',
			'on'			=> 'Aan',
			'off'			=> 'Uit',
		),
		array(
			'id'			=> 'google_analytics_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
		),
		array(
			'id'			=> 'site_verification_section_start',
			'type'			=> 'section',
			'title'			=> __( 'Website verificatie', 'siw' ),
			'indent' 		=> true,
		),
		array(
			'id'			=> 'google_search_console_verification',
			'title'			=> __( 'Google Search Console', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_special_chars',
		),
		array(
			'id'			=> 'bing_webmaster_tools_verification',
			'title'			=> __( 'Bing Webmaster Tools', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_special_chars',
		),
		array(
			'id'			=> 'site_verification_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
		),
		array(
			'id'			=> 'blocked_bots_section_start',
			'type'			=> 'section',
			'title'			=> __( 'Geblokkeerde bots', 'siw' ),
			'indent' 		=> true,
		),
		array(
			'id'			=> 'blocked_bots',
			'type'			=> 'multi_text',
			'title'			=> __( 'User-agent', 'siw' ),
			'validate'		=> 'no_special_chars',
		),
		array(
			'id'		=> 'blocked_bots_section_end',
			'type'		=> 'section',
			'indent' 	=> false,
		),
		array(
			'id'			=> 'newsletter_section_start',
			'type'			=> 'section',
			'title'			=> __( 'Nieuwsbrief', 'siw' ),
			'indent' 		=> true,
		),
		array(
			'id'			=> 'newsletter_list',
			'type'			=> 'select',
			'title'			=> __( 'Lijst', 'siw' ),
			'options'		=> siw_get_mailpoet_lists(),
		),
		array(
			'id'		=> 'newsletter_section_end',
			'type'		=> 'section',
			'indent' 	=> false,
		),		
	);

	$api_fields = array(
		array(
			'id'			=> 'postcode_api_key',
			'title'			=> __( 'Postcode API Key', 'siw' ),
			'subtitle'		=> 'https://www.postcodeapi.nu/',
			'type'			=> 'text',
			'validate'		=> 'no_special_chars',
		),
		array(
			'id'			=> 'exchange_rates_api_key',
			'title'			=> __( 'Wisselkoersen API Key', 'siw' ),
			'subtitle'		=> 'https://fixer.io/',
			'type'			=> 'text',
			'validate'		=> 'no_special_chars',			
		),
	);	

	$plato_fields = array(
		array(
			'id'			=> 'plato_organization_webkey',
			'title'			=> __( 'Organization webkey', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_special_chars',
		),
		array(
			'id'			=> 'plato_test_mode',
			'title'			=> __( 'Test-mode', 'siw' ),
			'type'			=> 'switch',
			'on'			=> 'Aan',
			'off'			=> 'Uit',
		),
		array(
			'id'			=> 'plato_fpl_import_id',
			'title'			=> __( 'FPL-import', 'siw' ),
			'type'			=> 'select',
			'options'		=> $imports,
		),
		array(
			'id'			=> 'plato_full_import_id',
			'title'			=> __( 'Volledige import', 'siw' ),
			'type'			=> 'select',
			'options'		=> $imports,
		),
		array(
			'id'			=> 'plato_force_full_update',
			'title'			=> __( 'Forceer volledige update', 'siw' ),
			'type'			=> 'switch',
			'on'			=> 'Aan',
			'off'			=> 'Uit',
		),
	);
	$pages_fields = array(
		array(
			'id'			=> 'pages_overview_section_start',
			'type'			=> 'section',
			'title'			=> __( 'Overzicht', 'siw' ),
			'indent' 		=> true,
		),		
		array(
			'id'			=> 'agenda_parent_page',
			'title'			=> __( 'Agenda', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'vacatures_parent_page',
			'title'			=> __( 'Vacatures', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'evs_projects_parent_page',
			'title'			=> __( 'EVS-projecten', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'pages_overview_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
		),	
		array(
			'id'			=> 'pages_how_it_works_section_start',
			'type'			=> 'section',
			'title'			=> __( 'Zo werkt het', 'siw' ),
			'indent' 		=> true,
		),					
		array(
			'id'			=> 'info_day_page',
			'title'			=> __( 'Infodagen', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'workcamps_page',
			'title'			=> __( 'Groepsprojecten', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'evs_page',
			'title'			=> __( 'EVS', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'op_maat_page',
			'title'			=> __( 'Op Maat', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'pages_how_it_works_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
		),	
		array(
			'id'			=> 'pages_other_section_start',
			'type'			=> 'section',
			'title'			=> __( 'Overige', 'siw' ),
			'indent' 		=> true,
		),			
		array(
			'id'			=> 'contact_page',
			'title'			=> __( 'Contact', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'quick_search_result_page',
			'title'			=> __( 'Zoekresultaten Snel zoeken', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'child_policy_page',
			'title'			=> __( 'Beleid kinderprojecten', 'siw' ),
			'type'			=> 'select',
			'options'		=> $pages,
			'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
		),
		array(
			'id'			=> 'pages_other_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
		),			
	);

	$login_fields[] = array(
		'id'		=> 'ip_whitelist_section_start',
		'type'		=> 'section',
		'title'		=> __( 'IP whitelist', 'siw' ),
		'indent' 	=> true,
	);
	for ( $x = 1 ; $x <= SIW_Properties::IP_WHITELIST_SIZE; $x++) {
		$login_fields[] = array(
			'id'				=> "whitelist_ip_{$x}",
			'title'				=> __( "IP {$x}", 'siw' ),
			'type'				=> 'text',
			'placeholder' 		=> __( "172.16.254.{$x}", 'siw' ),
			'validate_callback' => 'siw_settings_validate_ip',
		);
	}
	$login_fields[] = array(
		'id'		=> 'ip_whitelist_section_end',
		'type'		=> 'section',
		'indent'	=> false,
	);
	$email_fields = array(
		array(
			'id'			=> 'smtp_section_start',
			'title'			=> __( 'SMTP', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'smtp_enabled',
			'title'			=> __( 'SMTP gebruiken', 'siw' ),
			'type'			=> 'switch',
			'on'			=> 'Aan',
			'off'			=> 'Uit',
		),
		array(
			'id'			=> 'smtp_host',
			'title'			=> __( 'SMTP host', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
			'required'		=> array(
				'smtp_enabled',
				'equals',
				1
			),
		),
		array(
			'id'			=> 'smtp_port',
			'title'			=> __( 'SMTP port', 'siw' ),
			'type'			=> 'html5',
			'html5'			=> 'number',
			'min'			=> 1,
			'required'		=> array(
				'smtp_enabled',
				'equals',
				1
			),
		),
		array(
			'id'			=> 'smtp_encryption',
			'title'			=> __( 'SMTP encryptie', 'siw' ),
			'type'			=> 'radio',
			'options'		=> array(
				''		=> __( 'Geen', 'siw' ),
				'ssl'	=> __( 'SSL', 'siw' ),
				'tls'	=> __( 'TLS', 'siw' ),
			),
			'placeholder'	=> __( 'Selecteer encryptie', 'siw' ),
			'required'		=> array(
				'smtp_enabled',
				'equals',
				1
			),
		),
		array(
			'id'			=> 'smtp_authentication',
			'title'			=> __( 'SMTP authenticatie', 'siw' ),
			'type'			=> 'switch',
			'on'			=> 'Aan',
			'off'			=> 'Uit',
			'required'		=> array(
				'smtp_enabled',
				'equals',
				1
			),
		),
		array(
			'id'			=> 'smtp_credentials',
			'title'			=> __( 'Inloggegevens', 'siw' ),
			'type'			=> 'password',
			'username'		=> true,
			'placeholder' => array(
				'username'   => __( 'Gebruikersnaam', 'siw' ),
				'password'   => __( 'Wachtwoord', 'siw' ),
			),
			'validate'		=> 'no_html',
			'required'		=> array(
				'smtp_authentication',
				'equals',
				'1'
			),
		),
		array(
			'id'			=> 'smtp_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
		array(
			'id'		=> 'dkim_section_start',
			'title'		=> __( 'DKIM', 'siw' ),
			'type'		=> 'section',
			'indent'	=> true,
		),
		array(
			'id'			=> 'dkim_enabled',
			'title'			=> __( 'DKIM gebruiken', 'siw' ),
			'type'			=> 'switch',
			'on'			=> 'Aan',
			'off'			=> 'Uit',
		),
		array(
			'id'			=> 'dkim_selector',
			'title'			=> __( 'DKIM selector', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
			'required'		=> array(
				'dkim_enabled',
				'equals',
				1
			),
		),
		array(
			'id'			=> 'dkim_domain',
			'title'			=> __( 'DKIM domein', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
			'required'		=> array(
				'dkim_enabled',
				'equals',
				1
			),
		),
		array(
			'id'			=> 'dkim_passphrase',
			'title'			=> __( 'DKIM passphrase', 'siw' ),
			'type'			=> 'password',
			'validate'		=> 'no_html',
			'required'		=> array(
				'dkim_enabled',
				'equals',
				1
			),
		),
		array(
			'id'			=> 'dkim_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
	);

	/* Secties */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'configuration',
		'title'			=> __( 'Configuratie', 'siw' ),
		'icon'			=> 'el el-cogs',
		'permissions'	=> 'manage_options'
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'analytics_seo',
		'title'			=> __( 'Analytics & SEO', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $analytics_seo_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'api',
		'title'			=> __( 'API', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $api_fields,
	) );	
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'email',
		'title'			=> __( 'E-mail', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $email_fields,
	) );	
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'login',
		'title'			=> __( 'Login', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $login_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'pages',
		'title'			=> __( "Pagina's", 'siw' ),
		'subsection'	=> true,
		'fields'		=> $pages_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'plato',
		'title'			=> __( 'Plato', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $plato_fields,
	) );
} );
