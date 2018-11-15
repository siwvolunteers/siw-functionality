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
		'id'			=> $prefix . 'inleiding',
		'name'			=> __( 'Inleiding', 'siw' ),
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
		'id'			=> $prefix . 'highlight_quote',
		'name'			=> __( 'Highlight quote', 'siw' ),
		'type'			=> 'textarea_small',
		'attributes'	=> array(
			'required'		=> 'required',
			'maxlength' 	=> 100,
			'rows'			=> 1,
			'cols'			=> 100,
		),
	) );
	$cmb->add_field( array(
		'id'   => $prefix . 'uitgelicht',
		'name' => __( 'Vacature uitlichten', 'siw' ),
		'desc' => __( 'Wordt getoond in topbar als er geen evenementen zijn', 'siw'),
		'type' => 'checkbox',
		'column' => array(
			'name'			=> esc_html__( 'Uitgelicht', 'siw' ),
			'position'		=> 3,
		),
		'display_cb'	=> 'siw_show_vacature_column_featured',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wie_ben_jij_titel',
		'name'			=> __( 'Wie ben jij?', 'siw' ),
		'type'			=> 'title',

	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wie_ben_jij',
		'name'			=> __( 'Intro', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop' 		=> true,
			'media_buttons' => false,
			'teeny' 		=> true,
			'textarea_rows'	=> 5,
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wie_ben_jij_lijst',
		'name'			=> __( 'Lijst', 'siw' ),
		'type'			=> 'textarea_small',
		'repeatable'	=> true,
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wat_ga_je_doen_titel',
		'name'			=> __( 'Wat ga je doen?', 'siw' ),
		'type'			=> 'title',

	) );	
	$cmb->add_field( array(
		'id'			=> $prefix . 'wat_ga_je_doen',
		'name'			=> __( 'Introtekst', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop'		=> true,
			'media_buttons'	=> false,
			'teeny'			=> true,
			'textarea_rows'	=> 5,
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wat_ga_je_doen_lijst',
		'name'			=> __( 'Lijst', 'siw' ),
		'type'			=> 'textarea_small',
		'repeatable'	=> true,
	) );	
	$cmb->add_field( array(
		'id'			=> $prefix . 'wat_bieden_wij_jou_titel',
		'name'			=> __( 'Wat bieden wij jou?', 'siw' ),
		'type'			=> 'title',

	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wat_bieden_wij_jou',
		'name'			=> __( 'Intro', 'siw' ),
		'type'			=> 'wysiwyg',
		'options'		=> array(
			'wpautop'		=> true,
			'media_buttons'	=> false,
			'teeny'			=> true,
			'textarea_rows'	=> 5,
		),
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'wat_bieden_wij_jou_lijst',
		'name'			=> __( 'Lijst', 'siw' ),
		'type'			=> 'textarea_small',
		'repeatable'	=> true,
	) );	
	$cmb->add_field( array(
		'name'			=> __( 'Aantal uur per week', 'siw' ),
		'id'			=> $prefix . 'uur_per_week',
		'type'			=> 'text_small',
	) );
	$cmb->add_field( array(
		'id'			=> $prefix . 'betaald',
		'name'			=> __( 'Betaalde vacature', 'siw' ),
		'type'			=> 'checkbox',
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
		'name'			=> __( 'Toelichting solliciteren', 'siw' ),
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
 * @return void
 */
function siw_show_vacature_column_date( $field_args, $field ) {
	$date = date( 'm-d', $field->escaped_value() );
	echo siw_get_date_in_text( $date );
}


/**
 * [siw_show_vacature_column_featured description]
 *
 * @param  array $field_args
 * @param  object $field
 *
 * @return void
 */
function siw_show_vacature_column_featured ( $field_args, $field ) {
	if ( 'on' == $field->escaped_value() ) {
	 echo '<span class="dashicons dashicons-yes"></span>';
	}
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


/* Verberg editor bij vacacture-grid-pagina */
add_action( 'admin_init', function() {
	if ( ! isset( $_GET['post'] ) ) {
		return;
	}
	$post_id = $_GET['post'];

	$template_file = get_post_meta( $post_id, '_wp_page_template', true );

	if ( 'template-vacatures-grid.php' == $template_file ) {
		remove_post_type_support( 'page', 'editor' );
	}
} );


/* Metaboxes voor vacature-pagina toevoegen */
add_action( 'cmb2_admin_init', function() {
	$prefix = 'siw_vacature_';
	$cmb = new_cmb2_box( array(
			'id'            => 'siw_vacatures_metabox',
			'title'         => __( 'Instellingen vacature-pagina', 'siw' ),
			'object_types'  => array( 'page' ),
			'show_on'		=> array( 'key' => 'page-template', 'value' => array( 'template-vacatures-grid.php' ) ),
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true,
			'closed'     	=> false,
	) );
	$cmb->add_field( array(
		'id'      => 'siw_vacatures_introduction',
		'name'    => 'Introductie',
		'type'    => 'wysiwyg',
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
	$cmb->add_field( array(
		'id'      => 'siw_vacatures_open_application',
		'name'    => 'Open sollicitatie',
		'type'    => 'wysiwyg',
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
	$cmb->add_field( array(
		'id'		=> 'siw_vacatures_open_application_email',
		'name'		=> __( 'E-mail voor open sollicitaties:', 'siw' ),
		'before' 	=> 'Stuur jouw motivatie en curriculum vitae onder vermelding van \'Open sollicitatie\' naar ',
		'type'		=> 'text_email',
	) );
	$cmb->add_field( array(
		'id'			=> 'siw_vacatures_no_jobs',
		'name'			=> __( 'Infotekst geen vacatures', 'siw' ),
		'type'			=> 'textarea_small',
		'attributes'	=> array(
			'required'		=> 'required',
		),
	) );

} );
