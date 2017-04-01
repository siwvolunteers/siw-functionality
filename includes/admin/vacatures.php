<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Metaboxes voor vacature toevoegen */
add_action( 'cmb2_admin_init', function() {
	$prefix = 'siw_vacature_';
	$cmb = new_cmb2_box( array(
			'id'            => 'vacature_meta',
			'title'         => __( 'Vacature', 'siw' ),
			'object_types'  => array(
				'vacatures',
			),
			'context'       => 'normal',
			'priority'      => 'default',
			'show_names'    => true,
			'closed'     	=> false,
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'beschrijving',
		'name'			=> __( 'Beschrijving', 'siw' ),
		'type'			=> 'title',

	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'meervoud',
		'name'			=> __( 'Meervoud', 'siw' ),
		'desc'			=> __( 'Geef aan of de vacaturetitel in het meervoud is. Bijvoorbeeld "regiospecialisten".', 'siw' ),
		'type'			=> 'checkbox',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'inleiding',
		'name'			=> __( 'Inleiding', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop' 		=> true,
			'media_buttons' => false,
			'teeny' 		=> true,
			'textarea_rows'	=> 10,
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wie_ben_jij',
		'name'			=> __( 'Wie ben jij?', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop' 		=> true,
			'media_buttons' => false,
			'teeny' 		=> true,
			'textarea_rows'	=> 10,
		),
		'attributes'  	=> array(
			'required'    	=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wat_ga_je_doen',
		'name'			=> __( 'Wat ga je doen?', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop'		=> true,
			'media_buttons'	=> false,
			'teeny'			=> true,
			'textarea_rows'	=> 10,
		),
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wat_bieden_wij_jou',
		'name'			=> __( 'Wat bieden wij jou?', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop'		=> true,
			'media_buttons'	=> false,
			'teeny'			=> true,
			'textarea_rows'	=> 10,
		),
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'contactpersoon',
		'name'			=> __( 'Contactpersoon?', 'siw' ),
		'type'			=> 'title',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'contactpersoon_naam',
		'name'			=> __( 'Naam', 'siw' ),
		'type'			=> 'text_medium',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'contactpersoon_functie',
		'name'			=> __( 'Functie', 'siw' ),
		'type'			=> 'text_medium',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'contactpersoon_email',
		'name'			=> __( 'E-mail', 'siw' ),
		'type'			=> 'text_email',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'solliciteren',
		'name'			=> __( 'Solliciteren?', 'siw' ),
		'type'			=> 'title',
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
		'id'			=> $prefix . 'solliciteren_naam',
		'name'			=> __( 'Naam', 'siw' ),
		'type'			=> 'text_medium',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'solliciteren_functie',
		'name'			=> __( 'Functie', 'siw' ),
		'type'			=> 'text_medium',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'solliciteren_email',
		'name'			=> __( 'E-mail', 'siw' ),
		'type'			=> 'text_email',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'toelichting_solliciteren',
		'name'			=> __( 'Toelichting sollicteren', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop'		=> true,
			'media_buttons'	=> false,
			'teeny'			=> true,
			'textarea_rows'	=> 5,
		),
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );
} );


/**
 * Formatteert datum voor admin column deadline
 *
 * @param array $field_args
 * @param object $field
 *
 * @return string
 */
function siw_show_vacature_column_date( $field_args, $field ) {
	$date = date( 'Y-m-d', $field->escaped_value() );
	echo siw_get_date_in_text( $date );
}


/* Sorteren op deadline toevoegen */
add_filter( 'manage_edit-vacatures_sortable_columns', function( $columns ) {
	$columns['siw_vacature_deadline'] = 'deadline';
	return $columns;
} );


/* Standaard sorteren op deadline */
add_filter( 'request', function( $vars ) {
	if ( ( isset( $vars['post_type'] ) && 'vacatures' == $vars['post_type'] ) || ( isset( $vars['orderby'] ) && 'deadline' == $vars['orderby'] ) ) {
		$vars = array_merge( $vars, array(
			'meta_key'	=> 'siw_vacature_deadline',
			'orderby'	=> 'meta_value'
		) );
	}
	return $vars;
} );


/* Metaboxes voor vacature-pagina toevoegen */
add_action( 'cmb2_admin_init', function() {
	$prefix = 'siw_vacature_';
	$cmb = new_cmb2_box( array(
			'id'            => 'siw_vacatures_metabox',
			'title'         => __( 'Vacatures grid opties', 'siw' ),
			'object_types'  => array( 'page' ),
			'show_on'		=> array( 'key' => 'page-template', 'value' => array( 'template-vacatures-grid.php' )),
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true,
			'closed'     	=> false,
	) );
	$cmb->add_field( array(
		'id'		=> 'siw_vacature_columns',
		'name'		=> __( 'Kies het aantal kolommen:', 'siw' ),
		'type'		=> 'select',
		'options'	=> array(
			'4'			=> __( 'Vier kolommen', 'siw' ),
			'3'			=> __( 'Drie kolommen', 'siw' ),
			'2'			=> __( 'Twee kolommen', 'siw' ),
		),

	) );
} );
