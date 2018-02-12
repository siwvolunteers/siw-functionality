<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_configuration_section', function() {
	/*
	 * Hulpgegevens
	 * - WP All Imports
	 * - Pagina's
	*/
	$imports = siw_get_wpai_imports();
	$pages = siw_get_pages();
	$maps = siw_get_mapplic_maps();


	$analytics_verification_fields = array(
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
		),
		array(
			'id'			=> 'bing_webmaster_tools_verification',
			'title'			=> __( 'Bing Webmaster Tools', 'siw' ),
			'type'			=> 'text',
		),
		array(
			'id'		=> 'site_verification_section_end',
			'type'		=> 'section',
			'indent' 	=> false,
		),
	);

	$postcode_api_fields = array(
		array(
			'id'			=> 'postcode_api_key',
			'title'			=> __( 'API Key', 'siw' ),
			'type'			=> 'text',
		),
	);

	$plato_fields = array(
		array(
			'id'			=> 'plato_webservice_url',
			'title'			=> __( 'Webservice URL', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'url',
		),
		array(
			'id'			=> 'plato_organization_webkey',
			'title'			=> __( 'Organization webkey', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_special_chars',
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
			'id'			=> 'contact_page',
			'title'			=> __( 'Contact', 'siw' ),
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
	);

	/* Tabel bouwen met alle SIW-contantes */
	$constants = siw_get_constants();

	$constants_html = '<table class="form-table">';
	$constants_html .= '<tbody>';
	$constants_html .= '<tr style="font-weight:bold;">';
	$constants_html .= '<td>' . __( 'Constante', 'siw' ) . '</td>';
	$constants_html .= '<td>' . __( 'Waarde', 'siw' ) . '</td>';
	$constants_html .= '<td>' . __( 'Toelichting', 'siw' ) . '</td>';
	$constants_html .= '</tr>';

	foreach ( $constants as $constant => $name ){
		$constants_html .= '<tr>';
		$constants_html .= '<td>' . $constant . '</td>';
		$constants_html .= '<td>' . constant( $constant ) . '</td>';
		$constants_html .= '<td>' . esc_html( $name ) . '</td>';
		$constants_html .= '</tr>';
	}
	$constants_html .= '</tbody>';
	$constants_html .= '</table>';

	$constants_fields[] = array(
		'id'			=> 'constants',
		'title'			=> __( 'Overzicht van constantes', 'siw' ),
		'type'			=> 'raw',
		'content'		=> $constants_html,
	);

	$login_fields[] = array(
		'id'		=> 'ip_whitelist_section_start',
		'type'		=> 'section',
		'title'		=> __( 'IP whitelist', 'siw' ),
		'indent' 	=> true,
	);
	for ( $x = 1 ; $x <= SIW_IP_WHITELIST_SIZE; $x++) {
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
	$topbar_fields = array(
		array(
			'id'			=> 'topbar_event_section_start',
			'title'			=> __( 'Evenement in topbar', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'		=> 'show_topbar_days_before_event',
			'title'		=> __( 'Toon topbar vanaf aantal dagen voor evenement', 'siw' ),
			'type'		=> 'slider',
			'min'		=> '1',
			'max'		=> '31',
			'default'	=> '14',
		),
		array(
			'id'		=> 'hide_topbar_days_before_event',
			'title'		=> __( 'Verberg topbar vanaf aantal dagen voor evenement', 'siw' ),
			'type'		=> 'slider',
			'min'		=> '1',
			'max'		=> '31',
			'default'	=> '2',
		),
		array(
			'id'			=> 'topbar_event_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
		),
		array(
			'id'			=> 'topbar_social_link_section_start',
			'title'			=> __( 'Social media in topbar', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'topbar_social_link_enabled',
			'title'			=> __( 'Link naar social media', 'siw' ),
			'type'			=> 'switch',
			'on'			=> 'Aan',
			'off'			=> 'Uit',
		),
		array(
			'id'			=> 'topbar_social_link_intro',
			'title'			=> __( 'Introtekst', 'siw' ),
			'subtitle'		=> __( 'Verborgen op mobiel', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
			'required'		=> array(
				'topbar_social_link_enabled',
				'equals',
				'1'
			),
		),
		array(
			'id'			=> 'topbar_social_link_text',
			'title'			=> __( 'Linktekst', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
			'required'		=> array(
				'topbar_social_link_enabled',
				'equals',
				'1'
			),
		),
		array(
			'id'			=> 'topbar_social_link_network',
			'title'			=> __( 'Netwerk', 'siw' ),
			'type'			=> 'radio',
			'options'		=> array(
				'facebook'		=> __( 'Facebook', 'siw' ),
				'instagram'		=> __( 'Instagram', 'siw' ),
				'twitter'		=> __( 'Twitter', 'siw' ),
			),
			'required'		=> array(
				'topbar_social_link_enabled',
				'equals',
				'1'
			),
		),
		array(
			'id'			=> 'topbar_social_link_date_end',
			'title'			=> __( 'Einddatum', 'siw' ),
			'type'			=> 'html5',
			'html5'			=> 'date',
			'required'		=> array(
				'topbar_social_link_enabled',
				'equals',
				'1'
			),
		),
		array(
			'id'			=> 'topbar_social_link_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
		),
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

	$maps_fields = array(
		array(
			'id'			=> 'destinations_map',
			'title'			=> __( 'Bestemmingen', 'siw' ),
			'type'			=> 'select',
			'options'		=> $maps,
			'placeholder'	=> __( 'Selecteer een kaart', 'siw' ),
		),
		array(
			'id'			=> 'evs_map',
			'title'			=> __( 'EVS', 'siw' ),
			'type'			=> 'select',
			'options'		=> $maps,
			'placeholder'	=> __( 'Selecteer een kaart', 'siw' ),
		),
		array(
			'id'			=> 'np_map',
			'title'			=> __( 'Nederlandse projecten', 'siw' ),
			'type'			=> 'select',
			'options'		=> $maps,
			'placeholder'	=> __( 'Selecteer een kaart', 'siw' ),
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
		'id'			=> 'constants',
		'title'			=> __( 'Constantes', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $constants_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'email',
		'title'			=> __( 'E-mail', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $email_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'analytics_verification',
		'title'			=> __( 'Analytics & Verificatie', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $analytics_verification_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'maps',
		'title'			=> __( 'Kaarten', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $maps_fields,
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
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'api',
		'title'			=> __( 'Postcode API', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $postcode_api_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'		=> 'topbar',
		'title'		=> __( 'Topbar', 'siw' ),
		'subsection'=> true,
		'fields'	=> $topbar_fields,
	) );
} );
