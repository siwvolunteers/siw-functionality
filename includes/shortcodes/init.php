<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( __DIR__ . '/chart.php' );
require_once( __DIR__ . '/lightbox.php' );

/*
 * SIW shortcodes toevoegen aan pinnacle shortcodes
 * - Algemene informatie (telefoonnummer, email, IBAN, RSIN, KvK)
 * - Volgende infodag
 * - Volgende EVS-deadline
 * - Volgende EVS-vertrekmoment
 * - EVS-borg
 * - Inschrijfgeld groepsproject (student/regulier)
 * - Inschrijfgeld op maat (student/regulier)
 * - Korting tweede/derde project
 * - Externe link
 * - Pagina-lightbox
 */
add_filter( 'kadence_shortcodes', function( $pinnacle_shortcodes ) {
	$pinnacle_shortcodes['siw_email'] = array(
		'title' => '[SIW] - ' . __( 'E-mailadres', 'siw' ),
	);
	$pinnacle_shortcodes['siw_email_link'] = array(
		'title' => '[SIW] - ' . __( 'E-mailadres (link)', 'siw' ),
	);
	$pinnacle_shortcodes['siw_iban'] = array(
		'title' => '[SIW] - ' . __( 'IBAN', 'siw' ),
	);
	$pinnacle_shortcodes['siw_kvk'] = array(
		'title' => '[SIW] - ' . __( 'KvK-nummer', 'siw' ),
	);
	$pinnacle_shortcodes['siw_openingstijden'] = array(
		'title' => '[SIW] - ' . __( 'Openingstijden', 'siw' ),
	);
	$pinnacle_shortcodes['siw_rsin'] = array(
		'title' => '[SIW] - ' . __( 'RSIN', 'siw' ),
	);
	$pinnacle_shortcodes['siw_telefoon'] = array(
		'title' => '[SIW] - ' . __( 'Telefoonnummer', 'siw' ),
	);
	$pinnacle_shortcodes['siw_telefoon_internationaal'] = array(
		'title' => '[SIW] - ' . __( 'Telefoonnummer (internationaal)', 'siw' ),
	);
	$pinnacle_shortcodes['siw_evs_borg'] = array(
		'title' => '[SIW] - ' . __( 'EVS borg', 'siw' ),
	);
	$pinnacle_shortcodes['siw_evs_volgende_deadline'] = array(
		'title' => '[SIW] - ' . __( 'Volgende EVS-deadline', 'siw' ),
	);
	$pinnacle_shortcodes['siw_evs_volgende_vertrekmoment'] = array(
		'title' => '[SIW] - ' . __( 'Volgende EVS-vertrekmoment', 'siw' ),
	);
	$pinnacle_shortcodes['siw_volgende_infodag'] = array(
		'title' => '[SIW] - ' . __( 'Volgende infodag', 'siw' ),
	);
	$pinnacle_shortcodes['siw_groepsproject_tarief_student'] = array(
		'title' => '[SIW] - ' . __( 'Groepsprojecten - Studententarief', 'siw' ),
	);
	$pinnacle_shortcodes['siw_groepsproject_tarief_regulier'] = array(
		'title' => '[SIW] - ' . __( 'Groepsprojecten - Regulier tarief', 'siw' ),
	);
	$pinnacle_shortcodes['siw_nederlandse_projecten'] = array(
		'title' => '[SIW] - ' . __( 'Nederlandse projecten', 'siw' ),
	);
	$pinnacle_shortcodes['siw_op_maat_tarief_student'] = array(
		'title' => '[SIW] - ' . __( 'Op Maat - Studententarief', 'siw' ),
	);
	$pinnacle_shortcodes['siw_op_maat_tarief_regulier'] = array(
		'title' => '[SIW] - ' . __( 'Op Maat - Regulier tarief', 'siw' ),
	);
	$pinnacle_shortcodes['siw_bestuursleden'] = array(
		'title' => '[SIW] - ' . __( 'Bestuursleden', 'siw' ),
	);
	$pinnacle_shortcodes['siw_jaarverslagen'] = array(
		'title' => '[SIW] - ' . __( 'Jaarverslagen', 'siw' ),
	);
	$pinnacle_shortcodes['siw_korting_tweede_project'] = array(
		'title'	=> '[SIW] - ' . __( 'Tweede project', 'siw' ),
	);
	$pinnacle_shortcodes['siw_korting_derde_project'] = array(
		'title'	=> '[SIW] - ' . __( 'Derde project', 'siw' ),
	);
	$pinnacle_shortcodes['siw_externe_link'] = array(
		'title'	=> '[SIW] - ' . __( 'Externe link', 'siw' ),
		'attr'	=> array(
			'url'	=> array(
				'type'	=>'text',
				'title'	=> __( 'Url', 'siw' ),
			),
			'titel'	=> array(
				'type'	=> 'text',
				'title'	=> __( 'Titel', 'siw' ),
			),
		),
	);
	$pinnacle_shortcodes['siw_pagina_lightbox'] = array(
		'title' => '[SIW] - ' . __( 'Pagina-lightbox', 'siw' ),
		'attr'  => array(
			'link_tekst' => array(
				'type'  => 'text',
				'title' => __( 'Link tekst', 'siw' ),
			),
			'pagina' => array(
				'type'    => 'select',
				'title'   => __( 'Pagina', 'siw' ),
				'default' => '',
				'values'  => array(
					'kinderbeleid' => __( 'Beleid kinderprojecten', 'siw' ),
				),
			),
		),
	);
	return $pinnacle_shortcodes;
} );


/*
 * Algemene informatie
 * - KvK-nummer
 * - E-mailadres (+ link)
 * - Telefoonnummer (+ internationaal)
 * - IBAN
 * - RSIN
 * - Openingstijden
 */
add_shortcode( 'siw_kvk', function() {
	return SIW_KVK;
} );
add_shortcode( 'siw_email', function() {
	return antispambot( SIW_EMAIL );
} );
add_shortcode( 'siw_email_link', function(){
	$email = antispambot( SIW_EMAIL );
	return sprintf( '<a href="mailto:%s">%s</a>', $email, $email );
});
add_shortcode( 'siw_telefoon', function() {
	return SIW_PHONE;
} );
add_shortcode( 'siw_telefoon_internationaal', function() {
	return SIW_PHONE_FULL;
} );
add_shortcode( 'siw_iban', function() {
	return SIW_IBAN;
} );
add_shortcode( 'siw_rsin', function() {
	return SIW_RSIN;
} );
add_shortcode( 'siw_openingstijden', function() {
	return sprintf( esc_html__( 'Maandag t/m vrijdag %s', 'siw' ), SIW_OPENING_HOURS );
} );


/*
 * EVS
 * - Borg
 * - Volgende deadline
 * - Volgende vertrekmoment
 */
add_shortcode( 'siw_evs_borg', function() {
 	return siw_format_amount( SIW_EVS_DEPOSIT );
});
add_shortcode( 'siw_evs_volgende_deadline', function() {
	return siw_get_next_evs_deadline( true );
});
add_shortcode( 'siw_evs_volgende_vertrekmoment', function() {
	return siw_get_next_evs_departure_month();
});


/*
 * Volgende infodag
 */
add_shortcode( 'siw_volgende_infodag', function() {
	return siw_get_next_info_day( true );
});


/*
 * Tarieven en kortingen
 * - Groepsprojecten (student, regulier)
 * - Op Maat (student, regulier)
 * - Korting (tweede/derde project)
 */

add_shortcode( 'siw_groepsproject_tarief_student', function() {
	return siw_format_amount( SIW_WORKCAMP_FEE_STUDENT );
});
add_shortcode( 'siw_groepsproject_tarief_regulier', function() {
	return siw_format_amount( SIW_WORKCAMP_FEE_REGULAR );
});
add_shortcode( 'siw_op_maat_tarief_student', function() {
	return siw_format_amount( SIW_OP_MAAT_FEE_STUDENT );
});
add_shortcode( 'siw_op_maat_tarief_regulier', function() {
	return siw_format_amount( SIW_OP_MAAT_FEE_REGULAR );
});
add_shortcode( 'siw_korting_tweede_project', function() {
	return siw_format_percentage( SIW_DISCOUNT_SECOND_PROJECT );
});
add_shortcode( 'siw_korting_derde_project', function() {
	return siw_format_percentage( SIW_DISCOUNT_THIRD_PROJECT );
});


/* Organisatie */
add_shortcode( 'siw_bestuursleden', function() {
	$board_members = siw_get_board_members();
	if ( empty( $board_members ) ) {
		return;
	}

	$board_members_list = array();

	foreach ( $board_members as $board_member ) {
		if ( isset( $board_member['name'] ) ) {
			$list_item = $board_member['name'];
			if ( isset( $board_member['title'] ) ) {
				$list_item .= '<br/>' . '<i>' . $board_member['title'] . '</i>';
			}
			$board_members_list[] = $list_item;
		}
	}
	return siw_generate_unordered_list( $board_members_list );
});

add_shortcode( 'siw_jaarverslagen', function() {
	$annual_reports = siw_get_annual_reports();
	if ( empty( $annual_reports ) ) {
		return;
	}

	$output = '';
	foreach ( $annual_reports as $year => $annual_report ) {
		if ( ! empty( $annual_report['url'] ) ) {
			$url = $annual_report['url'];
			$text = sprintf( esc_html__( 'Jaarverslag %s', 'siw' ), $year );
			$output .= sprintf('<a href="%s" target="_blank" rel="noopener">%s</a><br/>', esc_url( $url ), esc_html( $text ) );
		}
	}

	return $output;
});

/* Shortcode voor footer credits */
add_shortcode( 'siw_footer', function() {
	return sprintf( '&copy; %s %s', current_time( 'Y' ), SIW_NAME );
});


/*
 * Externe link
 */
add_shortcode( 'siw_externe_link', function( $atts ) {
	extract( shortcode_atts( array(
		'url'	=> '',
		'titel'	=> '',
		), $atts, 'siw_externe_link' )
	);
	$titel = ( $titel ) ? $titel : $url;

	return siw_generate_external_link( $url, $titel );
});


/*
 * Overzicht van Nederlandse projecten
 */
add_shortcode( 'siw_nederlandse_projecten', function() {
	$projects = siw_get_np_projects();
	if ( empty( $projects ) ) {
		return;
	}
	$description = '';
	foreach ( $projects as $project ) {
		$duration = siw_get_date_range_in_text( $project['start_date'], $project['end_date'] );
		$description .= sprintf( '<b>%s - %s</b><br/>', esc_html( $project['name'] ), esc_html( $project['province_name'] ) );
		$description .= esc_html__( 'Data:', 'siw' ) . SPACE . esc_html( $duration ) . BR;
		$description .= esc_html__( 'Deelnemers:', 'siw' ) . SPACE . esc_html( $project['participants'] ) . BR;
	}
	return $description;
});


/*
 * Break
 */
add_shortcode( 'br', function() {
	return '<br>';
});
