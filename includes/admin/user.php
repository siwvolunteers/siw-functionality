<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Wachtwoord-reset niet via WooCommerce maar via standaard WordPress-methode */
add_action( 'plugins_loaded', function() {
	remove_filter( 'lostpassword_url', 'wc_lostpassword_url', 10 );
} );


/* WooCommerce filter kortsluiten: iedereen die mag inloggen mag het dashboard zien */
add_filter( 'woocommerce_prevent_admin_access', '__return_false' );


/* Overbodige contactmethodes bij een gebruiker verwijderen */
add_filter( 'user_contactmethods',
function( $contactmethods ) {
	unset( $contactmethods['aim'] );
	unset( $contactmethods['jabber'] );
	unset( $contactmethods['yim'] );
	unset( $contactmethods['googleplus'] );
	unset( $contactmethods['twitter'] );
	unset( $contactmethods['facebook'] );

	return $contactmethods;
}, 999, 1 );


/* Verwijder extra gebruikersvelden Pinnacle Premium */
add_action( 'admin_init', function() {
	remove_action( 'show_user_profile', 'kt_show_extra_profile_fields' );
	remove_action( 'edit_user_profile', 'kt_show_extra_profile_fields' );
	remove_action( 'personal_options_update', 'kt_save_extra_profile_fields' );
	remove_action( 'edit_user_profile_update', 'kt_save_extra_profile_fields' );
} );


/* Verwijder extra gebruikersvelden WooCommerce */
add_filter( 'woocommerce_customer_meta_fields', '__return_empty_array' );


/* Custom capabilities toevoegen voor Pinnacle Premium CPT's */
add_filter( 'kadence_portfolio_capability_type', function() { return 'op_maat_project'; } );
add_filter( 'kadence_portfolio_map_meta_cap', '__return_true' );
add_filter( 'kadence_testimonial_capability_type', function() { return 'quote'; } );
add_filter( 'kadence_testimonial_map_meta_cap', '__return_true' );
add_filter( 'kadence_staff_capability_type', function() { return 'volunteer'; } );
add_filter( 'kadence_staff_map_meta_cap', '__return_true' );


/* Capabilites voor Contact Form 7 aanpassen */
add_filter( 'wpcf7_map_meta_cap', function( $meta_caps ) {
	$meta_caps['wpcf7_edit_contact_form'] = 'manage_options';
	$meta_caps['wpcf7_edit_contact_forms'] = 'manage_options';
	$meta_caps['wpcf7_read_contact_forms'] = 'manage_options';
	$meta_caps['wpcf7_delete_contact_form'] = 'manage_options';
	return $meta_caps;
} );


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
