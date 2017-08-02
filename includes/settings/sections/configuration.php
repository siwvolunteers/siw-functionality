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
	global $wpdb;
	if ( ! isset( $wpdb->pmxi_imports ) ) {
		$wpdb->pmxi_imports = $wpdb->prefix . 'pmxi_imports';
	}
	$query = "SELECT $wpdb->pmxi_imports.id, $wpdb->pmxi_imports.friendly_name, $wpdb->pmxi_imports.name FROM $wpdb->pmxi_imports ORDER BY $wpdb->pmxi_imports.friendly_name ASC";
	$results = $wpdb->get_results( $query, ARRAY_A);
	foreach ( $results as $result ) {
		$imports[$result['id']] = esc_html( $result['friendly_name'] . ' (' . $result['name'] . ')' );
	}

	$pages = siw_get_pages();

	$google_analytics_fields[] = array(
		'id'			=> 'google_analytics_id',
		'title'			=> __( 'Property ID', 'siw' ),
		'type'			=> 'text',
		'placeholder'	=> 'UA-1234567-8',
	);
	$google_analytics_fields[] = array(
		'id'			=> 'google_analytics_enable_linkid',
		'title'			=> __( 'Enhanced link attribution', 'siw' ),
		'type'			=> 'switch',
		'on'			=> 'Aan',
		'off'			=> 'Uit',
	);

	$postcode_api_fields[] = array(
		'id'			=> 'postcode_api_key',
		'title'			=> __( 'API Key', 'siw' ),
		'type'			=> 'text',
	);

	$plato_fields[]= array(
		'id'			=> 'plato_webservice_url',
		'title'			=> __( 'Webservice URL', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'url',
	);
	$plato_fields[]= array(
		'id'			=> 'plato_organization_webkey',
		'title'			=> __( 'Organization webkey', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_special_chars',
	);
	$plato_fields[] = array(
		'id'			=> 'plato_fpl_import_id',
		'title'			=> __( 'FPL-import', 'siw' ),
		'type'			=> 'select',
		'options'		=> $imports,
	);
	$plato_fields[] = array(
		'id'			=> 'plato_full_import_id',
		'title'			=> __( 'Volledige import', 'siw' ),
		'type'			=> 'select',
		'options'		=> $imports,
	);
	$plato_fields[] = array(
		'id'			=> 'plato_force_full_update',
		'title'			=> __( 'Forceer volledige update', 'siw' ),
		'type'			=> 'switch',
		'on'			=> 'Aan',
		'off'			=> 'Uit',
	);

	$pages_fields[] = array(
		'id'			=> 'agenda_parent_page',
		'title'			=> __( 'Agenda', 'siw' ),
		'type'			=> 'select',
		'options'		=> $pages,
		'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
	);
	$pages_fields[] = array(
		'id'			=> 'vacatures_parent_page',
		'title'			=> __( 'Vacatures', 'siw' ),
		'type'			=> 'select',
		'options'		=> $pages,
		'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
	);
	$pages_fields[] = array(
		'id'			=> 'child_policy_page',
		'title'			=> __( 'Beleid kinderprojecten', 'siw' ),
		'type'			=> 'select',
		'options'		=> $pages,
		'placeholder'	=> __( 'Selecteer een pagina', 'siw' ),
	);

	/* Tabel bouwen met alle SIW-contantes */
	$constants = siw_get_constants();

	$constants_html = '<table class="form-table">';
	$constants_html .= '<thead>';
	$constants_html .= '<tr>';
	$constants_html .= '<th>' . __( 'Constante', 'siw' ) . '</th>';
	$constants_html .= '<th>' . __( 'Waarde', 'siw' ) . '</th>';
	$constants_html .= '<th>' . __( 'Toelichting', 'siw' ) . '</th>';
	$constants_html .= '</tr>';
	$constants_html .= '</thead>';
	$constants_html .= '<tbody>';

	foreach ( $constants as $constant => $name ){
		$constants_html .= '<tr>';
		$constants_html .= '<td> ' . $constant . '</td>';
		$constants_html .= '<td> ' . constant( $constant ) . '</td>';
		$constants_html .= '<td> ' . esc_html( $name ) . '</td>';
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

	$topbar_fields[] = array(
		'id'		=> 'show_topbar_days_before_event',
		'title'		=> __( 'Toon topbar vanaf aantal dagen voor evenement', 'siw' ),
		'type'		=> 'slider',
		'min'		=> '1',
		'max'		=> '31',
		'default'	=> '14',
	);
	$topbar_fields[] = array(
		'id'		=> 'hide_topbar_days_before_event',
		'title'		=> __( 'Verberg topbar vanaf aantal dagen voor evenement', 'siw' ),
		'type'		=> 'slider',
		'min'		=> '1',
		'max'		=> '31',
		'default'	=> '2',
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
		'id'			=> 'google_analytics',
		'title'			=> __( 'Google Analytics', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $google_analytics_fields,
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
		'desc'		=> __( 'Toont eerstvolgende evenement in de agenda', 'siw' ),
		'subsection'=> true,
		'fields'	=> $topbar_fields,
	) );
} );
