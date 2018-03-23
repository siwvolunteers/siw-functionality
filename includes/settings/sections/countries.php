<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_countries_section', function() {
	/* Sorteer landen op naam i.p.v. op code */
	$countries = siw_get_countries();

	/* Velden */
	foreach ( $countries as $country ) {
		$slug = $country['slug'];
		$name = $country['name'];
		$allowed = ( 'yes' == $country['allowed'] ) ? true : false;
		$continent = $country['continent'];
		$country_fields[ $continent ][] = array(
			'id'		=> $slug . '_section_start',
			'type'		=> 'section',
			'title'		=> $name,
			'indent'	=> true,
		);
		if ( $allowed ) {
			$country_fields[ $continent ][] = array(
				'id'			=> $slug . '_regiospecialist',
				'placeholder'	=> __( 'Selecteer een regiospecialist', 'siw' ),
				'type'			=> 'select',
				'data'			=> 'users',
				'args'			=> array(
					'role__in'		=> array( 'regiospecialist', 'medewerker_uitzendingen' ),
				),
				'title'			=> __( 'Regiospecialist', 'siw' ),
			);
		}
		else {
			$country_fields[ $continent ][] = array(
				'id'			=> $slug . '_not_allowed',
				'title'			=> __( 'In dit land bieden we geen projecten aan.', 'siw' ),
				'type'			=> 'info',
				'style'			=> 'warning',
			);
		}
		$country_fields[ $continent ][] = array(
			'id'		=> $slug . '_section_end',
			'type'		=> 'section',
			'indent'	=> false,
		);
	}

	/* Secties */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'countries',
		'title'			=> __( 'Landen', 'siw' ),
		'icon'			=> 'el el-globe',
		'permissions'	=> 'manage_options',
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'countries_afrika',
		'title'			=> __( 'Afrika', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $country_fields['afrika-midden-oosten']	,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'countries_asia',
		'title'			=> __( 'Azië', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $country_fields['azie']	,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'countries_europe',
		'title'			=> __( 'Europa', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $country_fields['europa']	,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'countries_latin_america',
		'title'			=> __( 'Latijns-Amerika', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $country_fields['latijns-amerika']	,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'countries_north_america',
		'title'			=> __( 'Noord-Amerika', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $country_fields['noord-amerika']	,
	) );
});
