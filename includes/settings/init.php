<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Optienaam voor Redux */
define( 'SIW_OPT_NAME', 'siw' );


/**
 * Ophalen SIW-instelling
 *
 * @param string $setting Instelling
 *
 * @return mixed
 */
function siw_get_setting( $setting, $default = false ) {
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}
	$value = Redux::getOption( SIW_OPT_NAME, $setting );
	return ( $value ) ? $value : $default;
}

/**
 * Zetten SIW-instelling
 *
 * @param string $setting Instelling
 * @param mixed $value Waarde
 *
 * @return void
 */
function siw_set_setting( $setting, $value ) {
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}
	return Redux::setOption ( SIW_OPT_NAME, $setting, $value );
}


/*
 * Afbreken als Redux niet geladen is.
 */
if ( ! class_exists( 'Redux' ) ) {
	return;
}


/* Extensie (html5 velden) laden */
Redux::setExtensions( SIW_OPT_NAME, SIW_ASSETS_DIR . '/redux-extensions/' );

/* Validatiefuncties laden */
require_once( __DIR__ . '/validation.php' );

add_action( 'plugins_loaded', function() {
	/* Instelling voor Redux optiepanel zetten */
	$args = array(
		'opt_name'				=> SIW_OPT_NAME,
		'display_name'			=>  __( 'Instellingen SIW', 'siw' ),
		'display_version'		=> SIW_PLUGIN_VERSION,
		'menu_type'				=> 'menu',
		'menu_title'			=> __( 'Instellingen SIW', 'siw' ),
		'page_title'			=> __( 'Instellingen SIW', 'siw' ),
		'admin_bar'				=> false,
		'dev_mode'				=> false,
		'page_priority'			=> 97,
		'page_permissions'		=> 'manage_settings',
		'menu_icon'				=> 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAAUCAYAAACAl21KAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAACxEAAAsRAX9kX5EAAAAZdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuMTZEaa/1AAABTElEQVQ4T82TPS8EURSGhxXFIhLK3USCaJGIFoXExx/YQretTjT+gUpFVEKitn6AZP/Clko12cLXYq0Zz7s5l5URc6bzJk/OnXPP+87kzkwUlCTJcBzHFdjKgtlls6XFwBS0oFdv8ACv3atvXZgtLTYV9MLdEmobDmEBSjAP+9C0/ewgkHatV4Z1mMU/QK3kCWowW6Cuwb0Z3+EKqnmCjpntp9Zl6pX2rbqCTpjto57J9Ju8QTcwAkNwAC3zf4ledpAN1qFk/RmoQaebglj7giTWt7DNchB0ZlVo254/SOJaasCizexZP19QEP070IcpnsF12I/m/yH6m6AX0ARX0BHsgM4o6BrGQQev38cVdG7XY7AkeKBRKMCpPZ0r6AMuYQPPJHUCVkCfQOwJmoYQJDqgg32ydeiLmtnS4kZFBlZBP+ufMDtntn+rKPoEpsn/ByanCycAAAAASUVORK5CYII=',
		'page_slug'				=> 'siw-settings',
		'save_defaults'			=> true, //TODO: wat is wenselijk, werking niet helemaal duidelijk
		'default_mark'			=> '',
		'show_import_export'	=> true,
		'footer_credit'			=> '&nbsp;',
		'use_cdn'				=> false,
		'hide_expand'			=> true,
		'hide_reset'			=> true,
		//'intro_text'			=> __( '<p>TODO:introductietekst</p>', 'siw' ),
	);
	Redux::setArgs( SIW_OPT_NAME, $args );


	/* Bepaal volgorde van secties */
	$sections = array(
		'configuration',
		'evs',
		'workcamps',
		'info_day',
		'enquiry',
		'np',
		'op_maat',
		'organisation',
		'topbar',
		'jobs',
	);

	/*
	 * Voor elke sectie:
	 * - Bestand laden
	 * - Sectie tonen
	 */
	foreach ( $sections as $section ) {
		$filename = str_replace('_', '-', $section );
		require_once( __DIR__ . "/sections/{$filename}.php" );
		do_action( "siw_settings_show_{$section}_section" );
	}
});

/*Workaround voor undefined index error TODO:github issue bij Redux aanmaken */
add_action( 'redux/field/' . SIW_OPT_NAME . '/text/render/before', function( &$field, &$value ) {
	if( isset( $field['options'] ) && isset( $field['default'] ) ) {
		$value = wp_parse_args( $value, $field['default'] );
	}
}, 10, 2);


/* Verwijderen advertenties ivm mixed content en 404 TODO:github issue bij Redux aanmaken*/
add_filter( 'redux/'. SIW_OPT_NAME . '/localize', function( $localize_data ) {
	unset( $localize_data['rAds'] );
	return $localize_data;
}, 999 );



/* Capability voor instellingmenu afleiden */
add_filter( 'user_has_cap', function ( $allcaps, $caps, $args, $user ) {
	if ( ! in_array( 'manage_settings', $caps) ) {
		return $allcaps;
	}

	$manage_settings_caps = apply_filters( 'siw_manage_settings_caps', array( 'manage_options' ) );
	foreach ( $manage_settings_caps as $manage_settings_caps ) {
		if ( ! empty ( $allcaps[ $manage_settings_caps ] ) ) {
			$allcaps['manage_settings'] = 1;
		}
	}

	return $allcaps;
}, 10, 4 );
