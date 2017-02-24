<?php
/*
(c)2016-2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('siw_settings_show_enquiry_section', 'siw_settings_show_enquiry_section');
function siw_settings_show_enquiry_section( $opt_name ){
	/*
	Velden
	*/
	$general_fields[] = array(
		'id'			=> 'enquiry_general_signature_section_start',
		'title'			=> __('Ondertekening e-mail', 'siw'),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$general_fields[] = array(
		'id'			=> 'enquiry_general_signature_name',
		'title'			=> __( 'Naam', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_special_chars',
	);
	$general_fields[] = array(
		'id'			=> 'enquiry_general_signature_title',
		'title'			=> __( 'Functie', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_special_chars',
	);
	$general_fields[] = array(
		'id'			=> 'enquiry_general_signature_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);
	$workcamp_fields[] = array(
		'id'			=> 'enquiry_workcamp_signature_section_start',
		'title'			=> __('Ondertekening e-mail', 'siw'),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$workcamp_fields[] = array(
		'id'			=> 'enquiry_workcamp_signature_name',
		'title'			=> __( 'Naam', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_special_chars',
	);
	$workcamp_fields[] = array(
		'id'			=> 'enquiry_workcamp_signature_title',
		'title'			=> __( 'Functie', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_special_chars',
	);
	$workcamp_fields[] = array(
		'id'			=> 'enquiry_workcamp_signature_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);
	$camp_leader_fields[] = array(
		'id'			=> 'enquiry_camp_leader_signature_section_start',
		'title'			=> __('Ondertekening e-mail', 'siw'),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$camp_leader_fields[] = array(
		'id'			=> 'enquiry_camp_leader_signature_name',
		'title'			=> __( 'Naam', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_special_chars',
	);
	$camp_leader_fields[] = array(
		'id'			=> 'enquiry_camp_leader_signature_title',
		'title'			=> __( 'Functie', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_special_chars',
	);
	$camp_leader_fields[] = array(
		'id'			=> 'enquiry_camp_leader_signature_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);

	/*
	Sectie
	*/
	Redux::setSection( $opt_name, array(
		'id'			=> 'enquiry',
		'title'			=> __( 'Infoverzoeken', 'siw' ),
		'icon'			=> 'el el-info-circle',
	));
	Redux::setSection( $opt_name, array(
		'id'			=> 'enquiry_general',
		'title'			=> __( 'Algemeen', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $general_fields,
	));
	Redux::setSection( $opt_name, array(
		'id'			=> 'enquiry_workcamp',
		'title'			=> __( 'Groepsprojecten', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $workcamp_fields,
	));
	Redux::setSection( $opt_name, array(
		'id'			=> 'enquiry_camp_leader',
		'title'			=> __( 'Projectbegeleider NP', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $camp_leader_fields,
	));


}
