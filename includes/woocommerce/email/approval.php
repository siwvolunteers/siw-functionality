<?php
/*
(c)2017-2018 SIW Internationale Vrijwilligersprojecten
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

	//zoek zichtbare projecten met status 'draft'
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
	$project_ids = get_posts( $args ); //TODO:wc_get_products gebruiken

	/* Maak een lijst met goed te keuren projecten per regiospecialist aan */
	//TODO: Link naar zoekresultaten bijv: https://local.siw.nl/wp-admin/edit.php?s=18699%2C18741&post_type=product&action=-1
	foreach ( $project_ids as $project_id ) {
		$project = wc_get_product( $project_id );
		$country = $project->get_meta( 'land' );
		$regiospecialist_id = siw_get_setting( $country . '_regiospecialist' );

		$admin_url = admin_url( sprintf('post.php?post=%s&action=edit', $project_id ) );
		$admin_link = sprintf( '<a href="%s">%s-%s<a/><br/>', $admin_url, $project->get_sku(), $project->get_name() );

		if ( ! empty( $regiospecialist_id ) ) {
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
	$supervisor_id = siw_get_setting( 'plato_import_supervisor' );
	$supervisor = get_userdata( $supervisor_id );
	if ( false == $supervisor ) {
		$supervisor_email = get_option( 'admin_email' );
		$supervisor_first_name = SIW_NAME;
		$supervisor_name = SIW_NAME;

	}
	else {
		$supervisor_email = $supervisor->user_email;
		$supervisor_first_name = $supervisor->first_name;
		$supervisor_name = $supervisor->display_name;
	}
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		sprintf( 'From: %s <%s>', $supervisor_name, $supervisor_email ),
		'CC: ' . $supervisor_email,
	);

	//verstuur een e-mail naar de regiospecialist met links naar te beoordelen projecten
	foreach ( $projects_for_approval as $regiospecialist_id => $projectlist ) {
		$user = get_userdata( $regiospecialist_id );

		//Als gebruiker niet meer bestaat stuur dan de projecten alsnog naar de coördinator
		if ( false == $user ) {
			$unassigned_projects_for_approval .= $projectlist;
			continue;
		}

		$email = $user->user_email;
		$subject = 'Nog te beoordelen projecten';
		$message = 'Beste' . SPACE . $user->first_name . BR2;
		$message .= 'De volgende projecten wachten op jouw beoordeling:' . BR2 . $projectlist . BR2;
		$message .= 'Met vriendelijke groet,' . BR . $supervisor_first_name;
		wp_mail( $email, $subject, $message, $headers );
	}

	//als er te beoordelen projecten zijn die niet aan een regiospecialist zijn, toegewezen stuur dan een mail naar de coördinator
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		sprintf( 'From: %s <%s>', $supervisor_name, $supervisor_email ),
	);

	if ( ! empty( $unassigned_projects_for_approval ) ) {
		$subject = 'Nog te beoordelen projecten';
		$message = 'Beste ' . $supervisor_first_name . ',' . BR2;
		$message .= 'De volgende projecten wachten op beoordeling, maar zijn niet toegewezen aan een regiospecialist:' . BR . $unassigned_projects_for_approval;
		wp_mail( $supervisor_email, $subject, $message, $headers );
	}

});
