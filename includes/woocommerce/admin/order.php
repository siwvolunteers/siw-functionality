<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * Actie 'Exporteer naar PLATO' toevoegen aan order-scherm
*/
add_action( 'woocommerce_order_actions', function( $actions ) {
	$actions['siw_export_to_plato'] = __( 'Exporteer naar PLATO', 'siw' );
	return $actions;
} );
add_action( 'woocommerce_order_action_siw_export_to_plato', 'siw_export_application_to_plato' );



/*
 * Metaboxes tonen voor extra opties
 * - Zichtbaarheid
 * - Opnieuw importeren
*/
add_action( 'cmb2_admin_init', function() {
	//TODO: grids of tabs toevoegen om overzichtelijkheid te verbeteren

	//lijsten ophalen
	$languages = siw_get_volunteer_languages();
	$language_skill = siw_get_volunteer_language_skill_levels();
	$gender = siw_get_volunteer_genders();
	$nationalities = siw_get_volunteer_nationalities();
	//tonen metabox
	$cmb = new_cmb2_box( array(
		'id'            => 'woocommerce_order_meta',
		'title'         => __( 'Extra opties', 'siw' ),
		'object_types'  => array( 'shop_order', ),
		'context'       => 'normal',
		'priority'      => 'default',
		'show_names'    => true,
		'closed'     	=> false,
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Talenkennis', 'siw' ),
		'type' 		=> 'title',
		'id'		=> 'language_skill'
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Taal 1', 'siw' ),
		'id'		=> 'language1',
		'type'		=> 'select',
		'options'	=> $languages,
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Niveau taal 1', 'siw' ),
		'id'		=> 'language1Skill',
		'type'		=> 'radio_inline',
		'options'	=> $language_skill,
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Taal 2', 'siw' ),
		'id'		=> 'language2',
		'type'		=> 'select',
		'options'	=> $languages,
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Niveau taal 2', 'siw' ),
		'id'		=> 'language2Skill',
		'type'		=> 'radio_inline',
		'options'	=> $language_skill,
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Taal 3', 'siw' ),
		'id'		=> 'language3',
		'type'		=> 'select',
		'options'	=> $languages,
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Niveau taal 3', 'siw' ),
		'id'		=> 'language3Skill',
		'type'		=> 'radio_inline',
		'options'	=> $language_skill,
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Gegevens voor PO', 'siw' ),
		'desc'		=> __( 's.v.p. in het engels invullen', 'siw' ),
		'type' 		=> 'title',
		'id'		=> 'informationForPartner'
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Motivation', 'siw' ),
		'id' 		=> 'motivation',
		'type'		=> 'textarea',
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Health issues', 'siw' ),
		'id' 		=> 'healthIssues',
		'type'		=> 'textarea'
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Volunteer experience', 'siw' ),
		'id' 		=> 'volunteerExperience',
		'type'		=> 'textarea'
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Together with', 'siw' ),
		'id' 		=> 'togetherWith',
		'type'		=> 'text_medium'
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Gegevens noodcontact', 'siw' ),
		'type' 		=> 'title',
		'id'		=> 'emergencyContact'
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Naam', 'siw' ),
		'id' 		=> 'emergencyContactName',
		'type'		=> 'text_medium'
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Telefoonnummer', 'siw' ),
		'id' 		=> 'emergencyContactPhone',
		'type'		=> 'text_medium'
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Akkoord met inschrijfvoorwaarden', 'siw' ),
		'id' 		=> 'terms',
		'type'		=> 'checkbox',
		'attributes'  => array(
			'readonly' => 'readonly',
			'disabled' => 'disabled',
		),
	) );
} );


/*
 * Admin columns verbergen
 * - Bezorgadres
 * - Toelichting
 * - Order note
 * Admin column toevoegen voor export naar Plato
*/
add_filter( 'manage_edit-shop_order_columns', function( $columns ) {
	unset( $columns['shipping_address']);
	unset( $columns['customer_message']);
	unset( $columns['order_notes']);

	$new_columns = array();
	foreach ( $columns as $column_name => $column_info ) {
		$new_columns[ $column_name ] = $column_info;
		if ( 'order_total' == $column_name ) {
			$new_columns['exported'] = __( 'Export naar PLATO', 'siw' );
		}
	}
	return $new_columns;
}, 10 );

add_action( 'manage_shop_order_posts_custom_column', function( $column_name, $post_id ) {
	if ( 'exported' == $column_name ) {
		$exported = get_post_meta( $post_id, '_exported_to_plato', true );

		//export via xml export suite
		$exported_via_xml_suite = get_post_meta( $post_id, '_wc_customer_order_xml_export_suite_is_exported', true );

		if ( 'success' == $exported or 1 == $exported_via_xml_suite ) {
			$dashicon = 'yes';

		}
		else if ( 'failed' == $exported ) {
			$dashicon = 'no';
		}
		else {
			$dashicon = 'minus';
		}
		echo sprintf( '<span class="dashicons dashicons-%s"></span>', $dashicon );
	}
}, 10, 2 );



//tonen adresgegevens op adminscherm
add_filter( 'woocommerce_admin_billing_fields', function( $fields ) {

	$email = $fields['email'];
	$phone = $fields['phone'];

	//zelfde volgorde + extra velden als bij checkout gebruiken
	$fields = siw_sort_customer_address_fields( $fields );

	//geslacht tonen als select i.p.v. radio.
	$fields['gender']['type'] = 'select';

	//reassign email and phone fields
	$fields['email'] = $email;
	$fields['phone'] = $phone;

	return $fields;
} );


/*
 * Diverse velden verbergen op het orderscherm
 */
add_actions( array( 'admin_menu', 'add_meta_boxes_shop_order' ), function() {
	remove_meta_box( 'postcustom' , 'shop_order' , 'normal' );
	remove_meta_box( 'woocommerce-order-downloads', 'shop_order', 'normal' );

	if ( !current_user_can( 'manage_options' ) ) {
		//TODO: verwijderen meta
	}

}, 999 );
