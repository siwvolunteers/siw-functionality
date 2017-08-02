<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/*
 * Metaboxes tonen voor extra opties
 * - Zichtbaarheid
 * - Opnieuw importeren
*/
add_action( 'cmb2_admin_init', function() {
	//opties voor zichtbaarheid
	$visibility_options = array(
		''		=> __( 'Automatisch', 'siw' ),
		'hide'	=> __( 'Verbergen', 'siw' ),
		//'show'	=> 'Tonen',
	);

	$cmb = new_cmb2_box( array(
		'id'            => 'woocommerce_project_meta',
		'title'         => __( 'Extra opties', 'siw' ),
		'object_types'  => array( 'product', ),
		'context'       => 'normal',
		'priority'      => 'default',
		'show_names'    => true,
		'closed'     	=> false,
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Zichtbaarheid', 'siw' ),
		'id'		=> 'manual_visibility',
		'type'		=> 'select',
		'options'	=> $visibility_options,
	) );
	$cmb->add_field( array(
		'name'		=> __( 'Opnieuw importeren', 'siw' ),
		'id'		=> 'import_again',
		'type'		=> 'checkbox',
	) );
} );


/*
 * Exra metaboxes verbergen
 * - Video tab
 * - sidebar
 * - Kad custom tabs
 * - Subtitle
*/
add_action( 'init', function(){
	remove_filter( 'cmb2_admin_init', 'pinnacle_product_metaboxes');
	remove_filter( 'cmb2_admin_init', 'pinnacle_productvideo_metaboxes');
	remove_filter( 'cmb2_admin_init', 'pinnacle_product_tab_metaboxes');
} );



/*
 * Admin columns verbergen
 * - Producttype
 * - Voorraad
 * - Prijs
 * - Yoast-velden
 * Extra columns toevoegen
 * - Zichtbaarheid
 * - Actie bij volgende update (verbergen, bijwerken)
*/
add_filter( 'manage_edit-product_columns', function( $columns ) {
	unset( $columns['product_type'] );
	unset( $columns['is_in_stock'] );
	//Yoast
	unset( $columns['wpseo-title'] );
	unset( $columns['wpseo-metadesc'] );
	unset( $columns['wpseo-focuskw'] );

	$new_columns = array();
	foreach ( $columns as $column_name => $column_info ) {
		$new_columns[ $column_name ] = $column_info;
		if ( 'sku' == $column_name ) {
			$new_columns['visibility'] = __( 'Zichtbaarheid', 'siw' );
		}
		if ( 'featured' == $column_name ) {
			$new_columns['next_update'] = __( 'Volgende update', 'siw' );
		}
	}
	return $new_columns;
}, 10 );


add_action( 'manage_product_posts_custom_column', function( $column_name, $post_id ) {
	if ( 'visibility' == $column_name ) {
		$visibility = get_post_meta( $post_id, '_visibility', true );

		if ( 'visible' == $visibility ) {
			$dashicon = 'visibility';
		}
		else {
			$dashicon = 'hidden';
		}
		echo sprintf( '<span class="dashicons dashicons-%s"></span>', $dashicon );
	}
	if ( 'next_update' == $column_name ) {
		$import_again = get_post_meta( $post_id, 'import_again', true );
		$manual_visibility = get_post_meta ( $post_id, 'manual_visibility', true );
		$visibility = get_post_meta( $post_id, '_visibility', true );

		if ( true == $import_again ) {
			 echo '<span class="dashicons dashicons-update"></span>';
		}
		if ( 'hide' == $manual_visibility && 'visible' == $visibility ) {
			echo '<span class="dashicons dashicons-hidden"></span>';
		}
	}
}, 10, 2 );


/* Toevoegen optie om project af te keuren en direct te verbergen */
add_action( 'post_submitbox_start', function() {
	$post_id = get_the_ID();
	//Alleen tonen bij groepsprojecten
	if ( 'product' != get_post_type( $post_id) ) {
		return;
	}

	//Alleen tonen als project ter review staat.
	if ( 'draft' != get_post_status ( $post_id ) ) {
		return;
	}

	wp_nonce_field( 'reject_project_nonce_' . $post_id, 'reject_project_nonce' );
	?>
	<div class="hide-rejected-project">
		<label><input type="checkbox" value="1" name="reject_project" /><?php esc_html_e( 'Project afkeuren en direct verbergen', 'siw' ); ?></label>
	</div>
	<?php
} );


/* Verbergen van afgekeurde projecten */
add_action( 'publish_product', function( $post_id, $post ) {
	//TODO: is deze check nodig?
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	/* Nonce check */
	if ( ! isset( $_POST['reject_project_nonce']) || ! wp_verify_nonce( $_POST['reject_project_nonce'], 'reject_project_nonce_' . $post_id ) ) {
		return;
	}
	/* Check op capability */
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	/* Ophalen gegevens gebruiker en huidige datum */
	$current_user = wp_get_current_user();
	$approval_user = $current_user->display_name . ' (' . $current_user->user_login . ')';
	$approval_date = current_time( 'Y-m-d' );

	/* Afgekeurd project direct verbergen */
	if ( isset( $_POST['reject_project']) and 1 == $_POST['reject_project'] ) {
		siw_hide_workcamp( $post_id );
		$approval_result = 'rejected';
	}
	else {
		$approval_result = 'approved';
	}

	/* Bijwerken post_meta */
	update_post_meta( $post_id, 'approval_result', $approval_result );
	update_post_meta( $post_id, 'approval_user', $approval_user );
	update_post_meta( $post_id, 'approval_date', $approval_date );

}, 10, 2 );


/* Historie goedkeuren tonen */
add_action( 'add_meta_boxes_product', function( $post ) {

	if ( ! get_post_meta( $post->ID, 'approval_result', true ) ) {
		return;
	}
	add_meta_box(
		'siw_show_project_approval_result',
		esc_html__( 'Resultaat beoordeling', 'siw' ),
		'siw_show_project_approval_result',
		'product',
		'side',
		'high'
	);

}, 999 );

function siw_show_project_approval_result( $object ) {
	$approval_results = array(
		'approved'	=> __( 'Goedgekeurd', 'siw' ),
		'rejected'	=> __( 'Afgewezen', 'siw' ),
	);
	$approval_result = $approval_results[ get_post_meta( $object->ID, 'approval_result', true) ];
	$approval_user = get_post_meta( $object->ID, 'approval_user', true);
	$approval_date = get_post_meta( $object->ID, 'approval_date', true);

	echo '<div class="misc-pub-section">' . sprintf( __( 'Resultaat: %s', 'siw' ), '<strong>' . esc_html( $approval_result ) . '</strong>' ) . '</div>';
	echo '<div class="misc-pub-section">' . sprintf( __( 'Door: %s', 'siw' ), '<strong>' . esc_html( $approval_user ) . '</strong>' ) . '</div>';
	echo '<div class="misc-pub-section">' . sprintf( __( 'Op: %s', 'siw' ), '<strong>' . esc_html( $approval_date ) . '</strong>' ) . '</div>';
}


/* Verberg editor bij projecten voor niet-admins */
add_action( 'admin_init', function() {
	if ( ! isset( $_GET['post'] ) ) {
		return;
	}
	$post_id = $_GET['post'];

	/* Alleen uitvoeren bij groepsprojecten */
	if ( 'product' != get_post_type( $post_id ) ) {
		return;
	}

	/* Verberg editor voor niet-admins */
	if ( ! current_user_can( 'manage_options' ) ) {
		remove_post_type_support( 'product', 'editor' );
	}
} );


/* Toon aangepaste samenvatting bij projecten voor niet-admins */
add_action( 'add_meta_boxes_product', function() {
	if ( ! current_user_can( 'manage_options' ) ) {
		add_meta_box(
			'siw_show_project_description',
			esc_html__( 'Projectbeschrijving', 'siw' ),
			'siw_show_project_description',
			'product',
			'normal',
			'high'
		);
	}
}, 999 );

function siw_show_project_description( $object ) {
	$content = $object->post_content;
	$content = preg_replace( '/\[pane title="(.*?)"\]/', '<h4>$1</h4><p>', $content );
	$content = preg_replace( '/\[\/pane\]/', '</p><hr>', $content );
	$content = preg_replace( '/\[(.*?)\]/', '', $content );
	echo wp_kses_post( $content );
}


/* Diverse velden verbergen op het productscherm */
add_actions( array( 'admin_menu', 'add_meta_boxes_product' ), function() {
	remove_meta_box( 'slugdiv', 'product', 'normal' );
	remove_meta_box( 'postcustom' , 'product' , 'normal' );
	remove_meta_box( 'woocommerce-product-images' , 'product', 'side', 'low' );
	remove_meta_box( 'commentsdiv' , 'product' , 'normal' );

	if ( ! current_user_can( 'manage_options' ) ) {
		remove_meta_box( 'woocommerce-product-data' , 'product', 'normal' );
		remove_meta_box( 'postimagediv', 'product', 'side' );
		remove_meta_box( 'tagsdiv-product_tag', 'product', 'normal' );
		remove_meta_box( 'product_catdiv', 'product', 'normal' );
	}
}, 999 );
