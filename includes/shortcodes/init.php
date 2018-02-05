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
		'title' => __( '[SIW - Algemeen] E-mailadres', 'siw' ),
	);
	$pinnacle_shortcodes['siw_email_link'] = array(
		'title' => __( '[SIW - Algemeen] E-mailadres (link)', 'siw' ),
	);
	$pinnacle_shortcodes['siw_iban'] = array(
		'title' => __( '[SIW - Algemeen] IBAN', 'siw' ),
	);
	$pinnacle_shortcodes['siw_kvk'] = array(
		'title' => __( '[SIW - Algemeen] KvK-nummer', 'siw' ),
	);
	$pinnacle_shortcodes['siw_rsin'] = array(
		'title' => __( '[SIW - Algemeen] RSIN', 'siw' ),
	);
	$pinnacle_shortcodes['siw_telefoon'] = array(
		'title' => __( '[SIW - Algemeen] Telefoonnummer', 'siw' ),
	);
	$pinnacle_shortcodes['siw_telefoon_internationaal'] = array(
		'title' => __( '[SIW - Algemeen] Telefoonnummer internationaal', 'siw' ),
	);
	$pinnacle_shortcodes['siw_evs_borg'] = array(
		'title' => __( '[SIW - EVS] Borg', 'siw' ),
	);
	$pinnacle_shortcodes['siw_evs_volgende_deadline'] = array(
		'title' => __( '[SIW - EVS] Volgende deadline', 'siw' ),
	);
	$pinnacle_shortcodes['siw_evs_volgende_vertrekmoment'] = array(
		'title' => __( '[SIW - EVS] Volgende vertrekmoment', 'siw' ),
	);
	$pinnacle_shortcodes['siw_volgende_infodag'] = array(
		'title' => __( '[SIW - Infodag] Volgende infodag', 'siw' ),
	);
	$pinnacle_shortcodes['siw_groepsproject_tarief_student'] = array(
		'title' => __( '[SIW - Groepsprojecten] Studententarief', 'siw' ),
	);
	$pinnacle_shortcodes['siw_groepsproject_tarief_regulier'] = array(
		'title' => __( '[SIW - Groepsprojecten] Regulier tarief', 'siw' ),
	);
	$pinnacle_shortcodes['siw_op_maat_tarief_student'] = array(
		'title' => __( '[SIW - Op Maat] Studententarief', 'siw' ),
	);
	$pinnacle_shortcodes['siw_op_maat_tarief_regulier'] = array(
		'title' => __( '[SIW - Op Maat] Regulier tarief', 'siw' ),
	);
	$pinnacle_shortcodes['siw_bestuursleden'] = array(
		'title' => __( '[SIW - Organisatie] Bestuursleden', 'siw' ),
	);
	$pinnacle_shortcodes['siw_jaarverslagen'] = array(
		'title' => __( '[SIW - Organisatie] Jaarverslagen', 'siw' ),
	);
	$pinnacle_shortcodes['siw_korting_tweede_project'] = array(
		'title'	=> __( '[SIW - Korting] Tweede project', 'siw' ),
	);
	$pinnacle_shortcodes['siw_korting_derde_project'] = array(
		'title'	=> __( '[SIW - Korting] Derde project', 'siw' ),
	);
	$pinnacle_shortcodes['siw_externe_link'] = array(
		'title'	=> __( '[SIW] Externe link', 'siw' ),
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
		'title' => __( '[SIW] Pagina-lightbox', 'siw' ),
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
 * - E-mailadres
 * - Telefoonnummer
 * - IBAN
 * - RSIN
 * TODO: openingstijden
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

	echo'<ul>';
	foreach ( $board_members as $board_member ) {
		if ( isset( $board_member['name'] ) ) {
			echo '<li>' . $board_member['name'] . '<br/>';
			if ( isset( $board_member['title'] ) ) {
				echo '<i>' . $board_member['title'] . '</i>';
			}
			echo '</li>';
		}
	}
	echo '</ul>';
});

add_shortcode( 'siw_jaarverslagen', function() {
	$annual_reports = siw_get_annual_reports();
	if ( empty( $annual_reports ) ) {
		return;
	}
	foreach ( $annual_reports as $year => $annual_report ) {
		if ( ! empty( $annual_report['url'] ) ) {?>
			<a href="<?php echo esc_url( $annual_report['url'] ); ?>" target="_blank" rel="noopener"><?php printf( esc_html__( 'Jaarverslag %s', 'siw' ), $year ); ?></a><br/>
		<?php
		}
	}


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
 * Break
 */
add_shortcode( 'br', function() {
	return '<br>';
});
