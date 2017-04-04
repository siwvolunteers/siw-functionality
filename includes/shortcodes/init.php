<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


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
 */
add_filter( 'kadence_shortcodes', function( $pinnacle_shortcodes ) {
	$pinnacle_shortcodes['siw_email'] = array(
		'title' => __( '[SIW - Algemeen] E-mailadres', 'siw' ),
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
	$pinnacle_shortcodes['siw_korting_tweede_project'] = array(
		'title'	=> __( '[SIW - Korting] Tweede project', 'siw' ),
	);
	$pinnacle_shortcodes['siw_korting_derde_project'] = array(
		'title'	=> __( '[SIW - Korting] Derde project', 'siw' ),
	);
	$pinnacle_shortcodes['siw_nederlands_project'] = array(
		'title'	=> __( '[SIW - NP] Nederlands project', 'siw' ),
		'attr'	=> array(
			'name'	=> array(
				'type'	=>'text',
				'title'	=> __( 'Naam', 'siw' ),
			),
			'participants'=>array(
				'type'	=>'text',
				'title'	=> __( 'Deelnemers', 'siw' ),
				'desc'	=> __( 'Geheel getal', 'siw' ),
			),
			'location'	=> array(
				'type'	=>'text',
				'title'	=> __( 'Locatie', 'siw' ),
			),
			'date_start'	=> array(
				'type'	=>'text',
				'title'	=> __( 'Startdatum', 'siw' ),
				'desc'	=> __( 'dd-mm-jjjj', 'siw' ),
			),
			'date_end'	=> array(
				'type'	=>'text',
				'title'	=> __( 'Einddatum', 'siw' ),
				'desc'	=> __( 'dd-mm-jjjj', 'siw' ),
			),
			'project_type'	=> array(
				'type'	=>'text',
				'title'	=> __( 'Soort project', 'siw' ),
			),
			'partner'	=> array(
				'type'	=>'text',
				'title'	=> __( 'Partner', 'siw' ),
			),
		)
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
 *
 */
add_shortcode( 'siw_kvk', function() {
	return SIW_KVK;
} );
add_shortcode( 'siw_email', function() {
	return antispambot( SIW_EMAIL );
} );
add_shortcode( 'siw_telefoon', function() {
	return SIW_PHONE;
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
 	return '&euro;&nbsp;' . SIW_EVS_DEPOSIT;
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
	return '&euro;&nbsp;' . SIW_WORKCAMP_FEE_STUDENT;
});
add_shortcode( 'siw_groepsproject_tarief_regulier', function() {
	return '&euro;&nbsp;' . SIW_WORKCAMP_FEE_REGULAR;
});
add_shortcode( 'siw_op_maat_tarief_student', function() {
	return '&euro;&nbsp;' . SIW_OP_MAAT_FEE_STUDENT;
});
add_shortcode( 'siw_op_maat_tarief_regulier', function() {
	return '&euro;&nbsp;' . SIW_OP_MAAT_FEE_REGULAR;
});
add_shortcode( 'siw_korting_tweede_project', function() {
	return SIW_DISCOUNT_SECOND_PROJECT . '&percnt;';
});
add_shortcode( 'siw_korting_derde_project', function() {
	return SIW_DISCOUNT_THIRD_PROJECT . '&percnt;';
});


/*
 * Nederlands project
 */
add_shortcode( 'siw_nederlands_project', function( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'name'			=> '',
		'date_start'	=> '1-1-1970',
		'date_end'		=> '15-1-1970',
		'participants'	=> '',
		'location'		=> '',
		'project_type'	=> '',
		'partner'		=> '',
		), $atts, 'siw_nederlands_project' ) );
?>
<div class="hrule clearfix" style="background:#ff9900; height:1px"></div>
<div class="row">
	<div class="col-md-12 ">
		<h5><b><?php echo esc_html( $name )?></b></h5>
	</div>
	<div class="col-md-6  ">
		<?php echo wp_kses_post( $content )?>
	</div>
	<div class="col-md-6  ">
		<dl class="dl-horizontal">
			<dt><?php esc_html_e( 'Data', 'siw' );?></dt>
			<dd><?php echo esc_html( siw_get_date_range_in_text( $date_start, $date_end ) );?></dd>
			<dt><?php esc_html_e( 'Deelnemers', 'siw' );?></dt>
			<dd><?php echo esc_html( $participants )?></dd>
			<dt><?php esc_html_e( 'Locatie', 'siw' );?></dt>
			<dd><?php echo esc_html( $location )?></dd>
			<dt><?php esc_html_e( 'Soort werkzaamheden', 'siw' );?></dt>
			<dd><?php echo esc_html( $project_type )?></dd>
			<dt><?php esc_html_e( 'Partnerorganisatie', 'siw' );?></dt>
			<dd><?php echo esc_html( $partner )?></dd>
		</dl>
	</div>
</div>
<?php
});


/*
 * Break
 */
add_shortcode( 'br', function() {
	return '<br>';
});
