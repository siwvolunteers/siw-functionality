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
function siw_get_setting( $setting ) {
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}
	return Redux::getOption( SIW_OPT_NAME, $setting );
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


/* Help-tabs laden */
require_once( __DIR__ . '/help-tabs.php' );


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
	'page_priority'			=> 99,
	'page_permissions'		=> 'manage_settings', //TODO: aparte capability maken en dynamisch zetten via filter user_has_cap
	'menu_icon'				=> 'dashicons-welcome-widgets-menus',
	'page_slug'				=> 'siw-settings',
	'save_defaults'			=> true, //TODO: wat is wenselijk, werking niet helemaal duidelijk
	'default_mark'			=> '',
	'show_import_export'	=> false,
	'footer_credit'			=> '&nbsp;',
	'use_cdn'				=> false,
	'hide_expand'			=> true,
	'hide_reset'			=> true,
	//'intro_text'			=> __( '<p>TODO:introductietekst</p>', 'siw' ),
);
Redux::setArgs( SIW_OPT_NAME, $args );


/* Bepaal volgorde van secties */
$sections = array(
	'agenda', /*Agenda*/
	'configuration', /*Configuratie*/
	'evs', 	/*EVS*/
	'workcamps', /*Groepsprojecten*/
	'enquiry', 	/*Infoverzoeken*/
	'countries',  /*Landen*/
	'np', /*Nederlandse projecten*/
	'op_maat', /*Op Maat*/
	'jobs', 	/*Vacatures*/
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

/* Aangepaste instellingen verwerken in VFB-templates en CD-opties (Kan weg na switch naar Caldera Forms) */
add_action( 'redux/options/' . SIW_OPT_NAME . '/settings/change', function( $options, $changed_values ) {
	/* EVS-template bijwerken als naam of functie aangepast is. */
	if ( isset( $changed_values['evs_application_signature_name'] ) || isset( $changed_values['evs_application_signature_title'] ) ) {
		siw_update_vfb_mail_template( 'evs' );
	}
	/* CD-template bijwerken als naam of functie aangepast is. */
	if ( isset( $changed_values['info_day_application_signature_name'] ) || isset( $changed_values['info_day_application_signature_title'] ) ) {
		siw_update_vfb_mail_template( 'community_day' );
	}
	/* Op maat-template bijwerken als naam of functie aangepast is. */
	if ( isset( $changed_values['op_maat_application_signature_name'] ) || isset( $changed_values['op_maat_application_signature_title'] ) ) {
		siw_update_vfb_mail_template( 'op_maat' );
	}

	/* CD-datums bijwerken als de datum aangepast zijn */
	if ( siw_settings_are_info_days_updated( $changed_values ) || isset( $changed_values['hide_application_form_days_before_info_day'] ) ) {
		siw_update_community_day_options();
	}
}, 10, 2);



/* Hulpfunctie om te bepalen of tenminste 1 van de infodagen is aangepast (Kan weg na switch naar Caldera Forms) */
function siw_settings_are_info_days_updated( $changed_values ) {
	for ( $x = 1 ; $x <= SIW_NUMBER_OF_INFO_DAYS; $x++ ) {
		if ( isset( $changed_values["info_day_{$x}"] ) ) {
			return true;
		}
	}
	return false;
}
