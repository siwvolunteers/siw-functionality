<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Sla extra velden op als ordermeta
 * - Talenkennis
 * - Noodcontact
 * - Informatie voor PO
 * - Is gebruiker akkoord gegaan met voorwaarden?
 */
add_action( 'woocommerce_checkout_update_order_meta', function( $order_id ) {
	if ( ! empty( $_POST['language1'] ) ) {
		update_post_meta( $order_id, 'language1', esc_attr( $_POST['language1'] ) );
		update_post_meta( $order_id, 'language1Skill', esc_attr( $_POST['language1Skill'] ) );
	}
	if ( ! empty( $_POST['language2'] ) ) {
		update_post_meta( $order_id, 'language2', esc_attr( $_POST['language2']) );
		update_post_meta( $order_id, 'language2Skill', esc_attr( $_POST['language2Skill'] ) );
	}
	if ( ! empty( $_POST['language3'] ) ) {
		update_post_meta( $order_id, 'language3', esc_attr( $_POST['language3'] ) );
		update_post_meta( $order_id, 'language3Skill', esc_attr( $_POST['language3Skill'] ) );
	}
	if ( ! empty( $_POST['emergencyContactName'] ) ) {
		update_post_meta( $order_id, 'emergencyContactName', sanitize_text_field( $_POST['emergencyContactName'] ) );
	}
	if ( ! empty( $_POST['emergencyContactPhone'] ) ) {
		update_post_meta( $order_id, 'emergencyContactPhone', sanitize_text_field( $_POST['emergencyContactPhone'] ) );
	}
	if( ! empty( $_POST['motivation'] ) ) {
		update_post_meta($order_id, 'motivation', sanitize_text_field($_POST['motivation'] ) );
	}
	if( ! empty( $_POST['healthIssues'] ) ) {
		update_post_meta($order_id, 'healthIssues', sanitize_text_field($_POST['healthIssues'] ) );
	}
	if( ! empty( $_POST['volunteerExperience'] ) ) {
		update_post_meta($order_id, 'volunteerExperience', sanitize_text_field($_POST['volunteerExperience'] ) );
	}
	if( ! empty( $_POST['togetherWith'] ) ) {
		update_post_meta( $order_id, 'togetherWith', sanitize_text_field( $_POST['togetherWith'] ) );
	}
	if( ! empty( $_POST['terms'] ) ) {
		update_post_meta( $order_id, '_terms', esc_attr( $_POST['terms'] ) );
	}
} );
