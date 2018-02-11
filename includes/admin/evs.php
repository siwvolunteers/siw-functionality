<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Metaboxes voor agenda toevoegen */
add_action( 'cmb2_admin_init', function() {


	$prefix = 'siw_evs_project_';
	$cmb = new_cmb2_box( array(
			'id'            => 'evs_meta',
			'title'         => __( 'EVS-project', 'siw' ),
			'object_types'  => array( 'evs_project' ),
			'context'       => 'normal',
			'priority'      => 'default',
			'show_names'    => true,
			'closed'     	=> false,
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'land',
		'name'			=> __( 'Land', 'siw' ),
		'type'			=> 'select',
		'options'		=> siw_get_evs_countries(),
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
		'id'			=> $prefix . 'soort_werk',
		'name'			=> __( 'Soort werk', 'siw' ),
		'type'			=> 'radio',
		'options'		=> siw_get_evs_work_types(),
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'startdatum',
		'name'			=> __( 'Startdatum', 'siw' ),
		'type'			=> 'text_date_timestamp',
		'date_format'	=> 'Y-m-d',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'einddatum',
		'name'			=> __( 'Einddatum', 'siw' ),
		'type'			=> 'text_date_timestamp',
		'date_format'	=> 'Y-m-d',
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
		'id'			=> $prefix . 'deadline',
		'name'			=> __( 'Deadline', 'siw' ),
		'type'			=> 'text_date_timestamp',
		'date_format'	=> 'Y-m-d',
		'attributes'	=> array(
			'required'		=> 'required',
		),
		'column' => array(
			'name'			=> esc_html__( 'Deadline', 'siw' ),
			'position'		=> 3,
		),
	) );

	$cmb->add_field( array(
		'id'		=> $prefix . 'wat_ga_je_doen',
		'name'		=> __( 'Wat ga je doen?', 'siw' ),
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
		'id'		=> $prefix . 'organisatie',
		'name'		=> __( 'Bij welke organisatie ga je werken?', 'siw' ),
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
});
