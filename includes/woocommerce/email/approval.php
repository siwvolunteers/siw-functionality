<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
 * Mail versturen met te beoordelen projecten
 * - Gegroepeerd op regiospecialist
 * - CC naar coördinator
 * - Niet toegewezen projecten naar coördinator
 */
add_action( 'siw_send_projects_for_approval_email', function() {
	$projects_for_approval = array();
	$unassigned_projects_for_approval = '';
	//zoek zichtbare en toegestane projecten met status 'draft'
	//
	$tax_query = array(
	 	array(
			'taxonomy' => 'product_visibility',
			'operator' => 'NOT EXISTS',
		),
 	);
	$args = array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'product',
		'post_status'		=> 'draft',
		'tax_query'			=> $tax_query,
		'fields' 			=> 'ids'
	);
	$project_ids = get_posts( $args );

	/* Maak een lijst met goed te keuren projecten per regiospecialist aan */
	//TODO: Link naar zoekresultaten bijv: https://local.siw.nl/wp-admin/edit.php?s=18699%2C18741&post_type=product&action=-1
	foreach ( $project_ids as $project_id ) {
		$project = wc_get_product( $project_id );
	  	$project_code = $project->get_sku();
		$country = $project->get_meta( 'land' );
		$regiospecialist_id = siw_get_setting( $country . '_regiospecialist' );

		$project_name = get_the_title( $project_id );
		$admin_link ='<a href="' . admin_url( 'post.php?post=' . $project_id . '&action=edit' ) . '">' . $project_code . '-' . $project_name . '<a/><br/>';//TODO:sprintf; want dit is onleesbaar

		if ( '' != $regiospecialist_id ) {
			if ( ! isset( $projects_for_approval[ $regiospecialist_id ] ) ) {
				$projects_for_approval[ $regiospecialist_id ] = $admin_link;
			}
			else {
				$projects_for_approval[ $regiospecialist_id ] .= $admin_link;
			}
		}
		else {
			$unassigned_projects_for_approval .= $admin_link;
		}
	}


	//zoek e-mailadres coördinator op
	$supervisor_id = siw_get_setting( 'plato_import_supervisor' );//TODO: fallback als dit niets oplevert bijv. administrator
	$supervisor = get_userdata( $supervisor_id );
	$supervisor_email = $supervisor->user_email;
	$supervisor_first_name = $supervisor->first_name;

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: SIW website <webmaster@siw.nl>', //TODO:naar configuratie
		'CC: ' . $supervisor_email,
	);

	//verstuur een e-mail naar de regiospecialist met links naar te beoordelen projecten
	foreach ( $projects_for_approval as $regiospecialist_id => $projectlist ) {
		$user_info = get_userdata( $regiospecialist_id );
		$first_name = $user_info->first_name;
		$email = $user_info->user_email;
		$subject = 'Nog te beoordelen projecten';
		$message = 'Beste ' . $first_name . ',<br/><br/>';
		$message .= 'De volgende projecten wachten op jouw beoordeling:<br/><br/>' . $projectlist;
		wp_mail( $email, $subject, $message, $headers );
	}

	//als er te beoordelen projecten zijn die niet aan een regiospecialist zijn toegewezen stuur dan een mail naar de coördinator
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: SIW website <webmaster@siw.nl>', //TODO:naar configuratie
	);

	if ( ! empty( $unassigned_projects_for_approval ) ) {
		$subject = 'Nog te beoordelen projecten';
		$message = 'Beste ' . $supervisor_first_name . ',<br/><br/>';
		$message .= 'De volgende projecten wachten op beoordeling, maar zijn niet toegewezen aan een regiospecialist:<br/>' . $unassigned_projects_for_approval;
		wp_mail( $supervisor_email, $subject, $message, $headers );
	}

});
