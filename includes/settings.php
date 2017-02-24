<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//opt_name definieren
$opt_name = SIW_OPT_NAME;

//getter
function siw_get_setting( $setting ){
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}
	return Redux::getOption(SIW_OPT_NAME, $setting);
}

//setter
function siw_set_setting( $setting, $value ){
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}
	return Redux::setOption (SIW_OPT_NAME, $setting, $value);
}

//afbreken als Redux niet geladen is.
if ( ! class_exists( 'Redux' ) ) {
	return;
}


//Extensie (html5 velden) laden
Redux::setExtensions( $opt_name, SIW_PLUGIN_DIR . '/assets/redux-extensions/' );

//Validatiefuncties laden
require_once('settings/validation.php');

//Help-tabs laden
require_once('settings/help-tabs.php');

$theme = wp_get_theme();
$args = array(
	'opt_name'				=> $opt_name,
	'display_name'			=> $theme->get( 'Name' ),
	'display_version'		=> $theme->get( 'Version' ),
	'menu_type'				=> 'menu',
	'menu_title'			=> __( 'Instellingen SIW', 'siw' ),
	'page_title'			=> __( 'Instellingen SIW', 'siw' ),
	'admin_bar'				=> false,
	'dev_mode'				=> false,
	'page_priority'			=> null,
	'page_permissions'		=> 'manage_options',
	'menu_icon'				=> 'dashicons-admin-settings',
	'page_slug'				=> 'siw-settings',
	'save_defaults'			=> true, //TODO: wat is wenselijk, werking niet helemaal duidelijk
	'default_mark'			=> '',
	'show_import_export'	=> false,
	'transient_time'		=> 60 * MINUTE_IN_SECONDS,
	'footer_credit'			=> '&nbsp;',
	'use_cdn'				=> false,
	'hide_expand'			=> true,
	'hide_reset'			=> true,
	//'intro_text'			=> __( '<p>TODO:introductietekst</p>', 'siw' ),
);

Redux::setArgs( $opt_name, $args );

/*
Bepaal volgorde van secties
*/
$sections = array(
	'agenda', /*Agenda*/
	'configuration', /*Configuratie*/
	'evs', 	/*EVS*/
	'workcamps', /*Groepsprojecten*/
	'enquiry', 	/*Infoverzoeken*/
	'countries',  /*Landen*/
	'op_maat', /*Op Maat*/
	'jobs', 	/*Vacatures*/
	//TODO: Nederlandse Projecten
);

//voor elke sectie: bestand laden en sectie tonen
foreach ($sections as $section){
	$filename = str_replace("_","-", $section);
	require_once("settings/section/{$filename}.php");
	do_action("siw_settings_show_{$section}_section", $opt_name);
}

/*
* Aangepaste instellingen verwerken in VFB-templates en CD-opties (Kan weg na switch naar Caldera Forms)
*/
add_action( "redux/options/{$opt_name}/settings/change", 'siw_settings_process_changed_values', 10, 2);
function siw_settings_process_changed_values( $options, $changed_values ){

	//EVS-template bijwerken als naam of functie aangepast is.
	if ( isset( $changed_values['evs_application_signature_name'] ) || isset( $changed_values['evs_application_signature_title'] ) ){
		siw_update_vfb_mail_template('evs');
	}
	//CD-template bijwerken als naam of functie aangepast is.
	if ( isset( $changed_values['info_day_application_signature_name'] ) || isset( $changed_values['info_day_application_signature_title'] ) ){
		siw_update_vfb_mail_template('community_day');
	}
	//Op maat-template bijwerken als naam of functie aangepast is.
	if ( isset( $changed_values['op_maat_application_signature_name'] ) || isset( $changed_values['op_maat_application_signature_title'] ) ){
		siw_update_vfb_mail_template('op_maat');
	}

	//CD-datums bijwerken als de datum aangepast zijn
	if (siw_settings_are_info_days_updated( $changed_values ) || isset( $changed_values['hide_application_form_days_before_info_day'] )){
		siw_update_community_day_options();
	}
}

//Hulpfunctie om te bepalen of tenminste 1 van de infodagen is aangepast
function siw_settings_are_info_days_updated( $changed_values ){
	$updated = false;
	for ( $x = 1 ; $x <= SIW_NUMBER_OF_INFO_DAYS; $x++ ) {
		if ( isset( $changed_values["info_day_{$x}"] ) ){
			$updated = true;
		}
	}
	return $updated;
}
