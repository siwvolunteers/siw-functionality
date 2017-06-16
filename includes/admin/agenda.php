<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Metaboxes voor agenda toevoegen */
add_action( 'cmb2_admin_init', function() {
	$prefix = 'siw_agenda_';
	$cmb = new_cmb2_box( array(
			'id'            => 'agenda_meta',
			'title'         => __( 'Agenda', 'siw' ),
			'object_types'  => array( 'agenda' ),
			'context'       => 'normal',
			'priority'      => 'default',
			'show_names'    => true,
			'closed'     	=> false,
	) );
	$cmb->add_field( array(
		'id'		=> $prefix . 'beschrijving',
		'name'		=> __( 'Beschrijving', 'siw' ),
		'type' 		=> 'wysiwyg',
		'options'		=> array(
			'wpautop'		=> true,
			'media_buttons'	=> false,
			'teeny'			=> true,
			'textarea_rows'	=> 15,
		),
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'highlight_quote',
		'name'			=> __( 'Highlight quote', 'siw' ),
		'type'			=> 'textarea_small',
		'attributes'	=> array(
			'required'		=> 'required',
			'maxlength' 	=> 80,
			'rows'	=> 1,
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'tijden_title',
		'name'			=> __( 'Tijden', 'siw' ),
		'type'			=> 'title',

	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'start',
		'name'			=> __( 'Start', 'siw' ),
		'type'			=> 'text_datetime_timestamp',
		'date_format'	=> 'Y-m-d',
		'time_format'	=> 'H:i',
		'attributes'	=> array(
			'required'		=> 'required',
		),
		'column' => array(
			'name'			=> esc_html__( 'Start', 'siw' ),
			'position'		=> 3,
		),
		'display_cb'	=> 'siw_show_event_column_date_time',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'eind',
		'name'			=> __( 'Eind', 'siw' ),
		'type'			=> 'text_datetime_timestamp',
		'date_format'	=> 'Y-m-d',
		'time_format'	=> 'H:i',
		'attributes'	=> array(
			'required'		=> 'required',
		),
		'column' => array(
			'name'			=> esc_html__( 'Eind', 'siw' ),
			'position'		=> 4,
		),
		'display_cb'	=> 'siw_show_event_column_date_time',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'locatie_title',
		'name'			=> __( 'Locatie', 'siw' ),
		'type'			=> 'title',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'locatie',
		'name'			=> __( 'Locatie', 'siw' ),
		'type'			=> 'text_medium',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'adres',
		'name'			=> __( 'Adres', 'siw' ),
		'type'			=> 'text_medium',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'postcode',
		'name'			=> __( 'Postcode', 'siw' ),
		'type'			=> 'text_medium',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'plaats',
		'name'			=> __( 'Plaats', 'siw' ),
		'type'			=> 'text_medium',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'aanmelden_title',
		'name'			=> __( 'Aanmelden', 'siw' ),
		'type'			=> 'title',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'aanmelden',
		'name'			=> __( 'Aanmelden via:', 'siw' ),
		'desc'			=> __( 'Eventuele extra velden verschijnen na opslaan', 'siw' ),
		'type'			=> 'radio_inline',
		'options'		=> array(
			'formulier'		=> __( 'Aanmeldformulier Community day', 'siw' ),
			'aangepast'		=> __( 'Aangepaste tekst en link', 'siw' ),
		),
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'tekst_na_verbergen_formulier',
		'name'			=> __( 'Tekst als aanmeldformulier verborgen is', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop'		=> true,
			'media_buttons'	=> false,
			'teeny'			=> true,
		),
		'show_on_cb'	=> 'siw_event_show_form_application_fields',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'aanmelden_toelichting',
		'name'			=> __( 'Toelichting aanmelden', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop'		=> true,
			'media_buttons'	=> false,
			'teeny'			=> true,
		),
		'attributes'	=> array(
			'required'		=> 'required',
		),
		'show_on_cb'	=> 'siw_event_show_custom_application_fields',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'aanmelden_link_url',
		'name'			=> __( 'Link om aan te melden', 'siw' ),
		'type'			=> 'text_url',
		'show_on_cb'	=> 'siw_event_show_custom_application_fields',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'aanmelden_link_tekst',
		'name'			=> __( 'Tekst voor link', 'siw' ),
		'type'			=> 'text_medium',
		'show_on_cb'	=> 'siw_event_show_custom_application_fields',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'programma',
		'type'			=> 'group',
		'description'	=> __( 'Programma', 'siw' ),
		'options'		=> array(
			'group_title'	=> __( 'Onderdeel {#}', 'siw' ),
			'add_button'	=> __( 'Onderdeel toevoegen', 'siw' ),
			'remove_button'	=> __( 'Verwijder onderdeel', 'siw' ),
			'sortable'		=> true,
		),
	) );
	$cmb->add_group_field( $prefix . 'programma', array(
		'id'			=> 'starttijd',
		'name'			=> __( 'Starttijd', 'siw' ),
		'type'			=> 'text_time',
		'time_format'	=> 'H:i',
	) );
	$cmb->add_group_field( $prefix . 'programma', array(
		'id'			=> 'eindtijd',
		'name'			=> __( 'Eindtijd', 'siw' ),
		'type'			=> 'text_time',
		'time_format'	=> 'H:i',
	) );
	$cmb->add_group_field( $prefix . 'programma', array(
		'id'			=> 'omschrijving',
		'name'			=> __( 'Omschrijving', 'siw' ),
		'type'			=> 'textarea_small',
	) );
} );


/**
 * Bepaalt of custom aanmeldvelden getoond moeten worden
 *
 * @param object $field
 *
 * @return bool
 */
function siw_event_show_custom_application_fields( $field ) {
	$application = get_post_meta( $field->object_id, 'siw_agenda_aanmelden', 1);
	if ( 'aangepast' == $application ) {
		return true;
	}
	return false;
}


/**
 * Bepaalt of Infodag-velden getoond moeten worden
 *
 * @param object $field
 *
 * @return bool
 */
function siw_event_show_form_application_fields( $field ) {
	$application = get_post_meta( $field->object_id, 'siw_agenda_aanmelden', 1);
	if ( 'formulier' == $application ) {
		return true;
	}
	return false;
}


/**
 * Formatteert datum + tijd voor admin column
 *
 * @param array $field_args
 * @param object $field
 *
 * @return string
 */
function siw_show_event_column_date_time ( $field_args, $field ) {
	$date = date( 'Y-m-d', $field->escaped_value() );
	$time = date( 'H:i', $field->escaped_value() );
	echo siw_get_date_in_text( $date ) . ' ' . $time;
}


/* Sorteren op startdatum toevoegen */
add_filter( 'manage_edit-agenda_sortable_columns', function( $columns ) {
	$columns['siw_agenda_start'] = 'start';
	return $columns;
} );


/* Standaard sorteren op startdatum */
add_filter( 'request', function( $vars ) {
	if ( ( isset( $vars['post_type'] ) && 'agenda' == $vars['post_type'] ) || ( isset( $vars['orderby'] ) && 'start' == $vars['orderby'] ) ) {
		$vars = array_merge( $vars, array(
			'meta_key'	=> 'siw_agenda_start',
			'orderby'	=> 'meta_value'
		) );
	}
	return $vars;
} );
