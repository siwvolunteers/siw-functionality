<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */


/* KvK-nummer */
add_shortcode( 'siw_kvk', function() {
	return SIW_KVK;
} );
siw_add_shortcode( 'siw_kvk', array( 'title' => __( 'KVK-nummer', 'siw' ) ) );


/* E-mailadres */
add_shortcode( 'siw_email', function() {
	return antispambot( SIW_EMAIL );
} );
siw_add_shortcode( 'siw_email', array( 'title' => __( 'E-mailadres', 'siw' ) ) );


/* E-mailadres (link) */
add_shortcode( 'siw_email_link', function(){
	$email = antispambot( SIW_EMAIL );
	return sprintf( '<a href="mailto:%s">%s</a>', $email, $email );
});
siw_add_shortcode( 'siw_email_link', array( 'title' => __( 'E-mailadres (link)', 'siw' ) ) );


/* Telefoonnummer */
add_shortcode( 'siw_telefoon', function() {
	return SIW_PHONE;
} );
siw_add_shortcode( 'siw_telefoon', array( 'title' => __( 'Telefoonnummer', 'siw' ) ) );


/* Telefoonnummer (internationaal) */
add_shortcode( 'siw_telefoon_internationaal', function() {
	return SIW_PHONE_FULL;
} );
siw_add_shortcode( 'siw_telefoon', array( 'title' => __( 'Telefoonnummer (internationaal)', 'siw' ) ) );


/* IBAN */
add_shortcode( 'siw_iban', function() {
	return SIW_IBAN;
} );
siw_add_shortcode( 'siw_iban', array( 'title' => __( 'IBAN', 'siw' ) ) );


/* RSIN */
add_shortcode( 'siw_rsin', function() {
	return SIW_RSIN;
} );
siw_add_shortcode( 'siw_rsin', array( 'title' => __( 'RSIN', 'siw' ) ) );


/* Openingstijden */
add_shortcode( 'siw_openingstijden', function() {
	return sprintf( esc_html__( 'Maandag t/m vrijdag %s', 'siw' ), SIW_OPENING_HOURS );
} );
siw_add_shortcode( 'siw_openingstijden', array( 'title' => __( 'Openingstijden', 'siw' ) ) );


/* EVS-Borg */
add_shortcode( 'siw_evs_borg', function() {
 	return siw_format_amount( SIW_EVS_DEPOSIT );
});
siw_add_shortcode( 'siw_evs_borg', array( 'title' => __( 'EVS-borg', 'siw' ) ) );


/* Volgende EVS deadline */
add_shortcode( 'siw_evs_volgende_deadline', function() {
	return siw_get_next_evs_deadline( true );
});
siw_add_shortcode( 'siw_evs_volgende_deadline', array( 'title' => __( 'Volgende EVS-deadline', 'siw' ) ) );


/* Volgende EVS-vertrekmoment */
add_shortcode( 'siw_evs_volgende_vertrekmoment', function() {
	return siw_get_next_evs_departure_month();
});
siw_add_shortcode( 'siw_evs_volgende_vertrekmoment', array( 'title' => __( 'Volgende EVS-vertrekmoment', 'siw' ) ) );


/* Volgende infodag */
add_shortcode( 'siw_volgende_infodag', function() {
	return siw_get_next_info_day( true );
});
siw_add_shortcode( 'siw_volgende_infodag', array( 'title' => __( 'Volgende infodag', 'siw' ) ) );


/* Groepsprojecten - Studententarief */
add_shortcode( 'siw_groepsproject_tarief_student', function() {

	if ( siw_is_sale_active() ) {
		$output = '<del>' . siw_format_amount( SIW_WORKCAMP_FEE_STUDENT ) . '</del>&nbsp;<ins>' . siw_format_amount( SIW_WORKCAMP_FEE_STUDENT_SALE ) . '</ins>';
	}
	else {
		$output = siw_format_amount( SIW_WORKCAMP_FEE_STUDENT );
	}

	return $output;
});
siw_add_shortcode( 'siw_groepsproject_tarief_student', array( 'title' => __( 'Groepsprojecten - Studententarief', 'siw' ) ) );


/* Groepsprojecten - Regulier tarief */
add_shortcode( 'siw_groepsproject_tarief_regulier', function() {
	if ( siw_is_sale_active() ) {
		$output = '<del>' . siw_format_amount( SIW_WORKCAMP_FEE_REGULAR ) . '</del>&nbsp;<ins>' . siw_format_amount( SIW_WORKCAMP_FEE_REGULAR_SALE ) . '</ins>';
	}
	else {
		$output = siw_format_amount( SIW_WORKCAMP_FEE_REGULAR );
	}

	return $output;
});
siw_add_shortcode( 'siw_groepsproject_tarief_regulier', array( 'title' => __( 'Groepsprojecten - Regulier tarief', 'siw' ) ) );


/* Op Maat - Studententarief */
add_shortcode( 'siw_op_maat_tarief_student', function() {
	return siw_format_amount( SIW_OP_MAAT_FEE_STUDENT );
});
siw_add_shortcode( 'siw_op_maat_tarief_student', array( 'title' => __( 'Op Maat - Studententarief', 'siw' ) ) );


/* Op Maat - Regulier tarief */
add_shortcode( 'siw_op_maat_tarief_regulier', function() {
	return siw_format_amount( SIW_OP_MAAT_FEE_REGULAR );
});
siw_add_shortcode( 'siw_op_maat_tarief_regulier', array( 'title' => __( 'Op Maat - Regulier tarief', 'siw' ) ) );


/* Korting tweede project */
add_shortcode( 'siw_korting_tweede_project', function() {
	return siw_format_percentage( SIW_DISCOUNT_SECOND_PROJECT );
});
siw_add_shortcode( 'siw_korting_tweede_project', array( 'title' => __( 'Korting tweede project', 'siw' ) ) );


/* Korting derde project */
add_shortcode( 'siw_korting_derde_project', function() {
	return siw_format_percentage( SIW_DISCOUNT_THIRD_PROJECT );
});
siw_add_shortcode( 'siw_korting_derde_project', array( 'title' => __( 'Korting derde project', 'siw' ) ) );


/* Bestuursleden */
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
	return siw_generate_list( $board_members_list );
});
siw_add_shortcode( 'siw_bestuursleden', array( 'title' => __( 'Bestuursleden', 'siw' ) ) );


/* Jaarverslagen */
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
			$output .= sprintf( '<a class="siw-download" href="%s" target="_blank" rel="noopener">%s</a><br/>', esc_url( $url ), esc_html( $text ) );
		}
	}

	return $output;
});
siw_add_shortcode( 'siw_jaarverslagen', array( 'title' => __( 'Jaarverslagen', 'siw' ) ) );


/* Footer credits */
add_shortcode( 'siw_footer', function() {
	return sprintf( '&copy; %s %s', current_time( 'Y' ), SIW_NAME );
});


/* Externe link */
add_shortcode( 'siw_externe_link', function( $atts ) {
	extract( shortcode_atts( array(
		'url'	=> '',
		'titel'	=> '',
		), $atts, 'siw_externe_link' )
	);
	$titel = ( $titel ) ? $titel : $url;

	return siw_generate_external_link( $url, $titel );
});
siw_add_shortcode( 'siw_externe_link', array(
    'title'	=> __( 'Externe link', 'siw' ),
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
));


/* Overzicht van Nederlandse Projecten */
add_shortcode( 'siw_nederlandse_projecten', function() {
	$projects = siw_get_dutch_projects();
	if ( empty( $projects ) ) {
		return;
	}
	$description = '';
	foreach ( $projects as $project ) {
		$duration = siw_get_date_range_in_text( $project['start_date'], $project['end_date'] );
		$description .= sprintf( '<b>%s - %s</b><br/>', esc_html( $project['name'] ), esc_html( $project['province_name'] ) );
		$description .= esc_html__( 'Data:', 'siw' ) . SPACE . esc_html( $duration ) . BR;
		$description .= esc_html__( 'Deelnemers:', 'siw' ) . SPACE . esc_html( $project['participants'] ) . BR;
		$description .= esc_html__( 'Soort werk:', 'siw' ) . SPACE . $project['work_name'] . BR;
		$description .= esc_html__( 'Locatie:', 'siw' ) . SPACE . $project['city'] . ', ' . __( 'provincie', 'siw' ) . SPACE . $project['province_name'] . BR2;
	}
	return $description;
});
siw_add_shortcode( 'siw_nederlandse_projecten', array( 'title' => __( 'Nederlandse Projecten', 'siw' ) ) );


/* Break */
add_shortcode( 'br', function() {
	return '<br>';
});
