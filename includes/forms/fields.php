<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * [siw_get_form_field description]
 * @param  string $slug    
 * @param  array  $options
 * @return array
 */
function siw_get_form_field( $slug, $options = array() ){

	$fields = array();
	/*Generieke velden*/
	$fields['html'] = array(
		'ID' => 'html',
		'type' => 'html',
		'label' => '',
		'slug' => 'html',
		'caption' => '',
		'config' =>
		array(
			'custom_class' => '',
			'default' => '',
		),
		'conditions' =>
		array(
			'type' => '',
		),
	);
	$fields['hr'] = array(
		'ID' => 'hr',
		'type' => 'section_break',
		'label' => 'hr',
		'slug' => 'hr',
		'conditions' =>
		array(
			'type' => '',
		),
		'caption' => '',
		'config' =>
		array(
			'custom_class' => '',
			'width' => '',
		),
	);
	$fields['text'] = array(
		'ID' => 'text',
		'type' => 'text',
		'label' => '',
		'slug' => 'text',
		'required' => 1,
		'caption' => '',
		'entry_list' => 0,
		'config' =>
		array(
			'custom_class' => '',
			'placeholder' => '',
			'type_override' => '',
			'default' => '',
			'mask' => '',
		),
		'conditions' =>
		array(
			'type' => '',
		),
	);
	$fields['checkbox'] = array(
		'ID' => 'checkbox',
		'type' => 'checkbox',
		'label' => '',
		'slug' => 'checkbox',
		'required' => 1,
		'conditions' =>
		array(
			'type' => '',
		),
		'caption' => '',
		'config' =>
		array(
			'custom_class' => '',
			'inline' => 0,
			'default' => '',
			'option' => array(),
		),
	);
	$fields['radio'] = array(
		'ID' => 'radio',
		'type' => 'radio',
		'label' => '',
		'slug' => 'radio',
		'required' => 1,
		'conditions' =>
		array(
			'type' => '',
		),
		'caption' => '',
		'config' =>
		array(
			'custom_class' => '',
			'inline' => 0,
			'default' => '',
			'option' => array(),
		),
	);
	$fields['dropdown'] =
	array(
		'ID' => 'dropdown',
		'type' => 'dropdown',
		'label' => '',
		'slug' => 'dropdown',
		'required' => 1,
		'conditions' =>
		array(
			'type' => '',
		),
		'caption' => '',
		'config' =>
		array(
			'custom_class' => '',
			'default' => '',
			'option' => array(),
		),
	);
	$fields['email'] = array(
		'ID' => 'email',
		'type' => 'email',
		'label' => '',
		'slug' => 'email',
		'required' => 1,
		'caption' => '',
		'entry_list' => 1,
		'config' =>
		array(
			'custom_class' => '',
			'placeholder' => '',
			'default' => '',
		),
		'conditions' =>
		array(
			'type' => '',
		),
	);
	$fields['paragraph'] = array(
		'ID' => 'paragraph',
		'type' => 'paragraph',
		'label' => '',
		'slug' => 'paragraph',
		'required' => 1,
		'caption' => '',
		'config' =>
		array(
			'custom_class' => '',
			'placeholder' => '',
			'rows' => 7,
			'default' => '',
		),
		'conditions' =>
		array(
			'type' => '',
		),
	);

	$fields['button'] = array(
		'ID' => 'button',
		'type' => 'button',
		'label' => '',
		'slug' => 'button',
		'caption' => '',
		'config' =>
		array(
			'custom_class' => '',
			'type' => 'submit',
			'class' => 'kad-btn kad-btn-primary',
		),
		'conditions' =>
		array(
			'type' => '',
		),
	);
	$fields['file'] = array(
		'ID' => 'file',
		'type' => 'file',
		'label' => '',
		'slug' => 'file',
		'conditions' =>
		array(
			'type' => '',
		),
		'caption' => '',
		'config' =>
		array(
			'custom_class' => '',
			'attach' => 1,
			'media_lib' => 0,
			'allowed' => 'pdf,docx',
		),
	);

	/* Filter om extra velden toe te voegen */
	$fields = apply_filters( 'siw_form_fields', $fields );

	/* Afbreken als veld niet gevonden is */
	if ( ! isset( $fields[ $slug ] ) ) {
		return;
	}

	$field = $fields[ $slug ]; //TODO: ID kopieren naar slug
	$field = wp_parse_args_recursive( $options, $field );

	return $field;
}


/**
 * [siw_get_standard_form_field description]
 * @param  string $slug
 * @param  array $options
 * @return array
 */
function siw_get_standard_form_field( $slug, $options = array() ) {

	/* Referentiegegevens*/
	$volunteer_genders = siw_get_volunteer_genders();
	foreach ( $volunteer_genders as $volunteer_gender ) {
		$gender_slug = sanitize_title( $volunteer_gender );
		$genders[ $gender_slug ]['value'] = $gender_slug;
		$genders[ $gender_slug ]['label'] = $volunteer_gender;
	}

	$fields = array();
	$fields['intro_hr'] = array(
		'ID' => 'intro_hr',
		'type' => 'section_break',
		'label' => 'intro_hr',
		'slug' => 'intro_hr',
		'conditions' =>
		array(
			'type' => '',
		),
		'caption' => '',
		'config' =>
		array(
			'custom_class' => '',
			'width' => '',
		),
	);
	$fields['voornaam'] = siw_get_form_field( 'text',
		array(
			'ID' => 'voornaam',
			'slug' => 'voornaam',
			'label' => __( 'Voornaam', 'siw' ),
			'entry_list' => 1,
		)
	);
	$fields['achternaam'] = siw_get_form_field( 'text',
		array(
			'ID' => 'achternaam',
			'slug' => 'achternaam',
			'label' => __( 'Achternaam', 'siw' ),
			'entry_list' => 1,
		)
	);
	$fields['emailadres'] = siw_get_form_field( 'email',
		array(
			'ID' => 'emailadres',
			'slug' => 'emailadres',
			'label' => __( 'E-mail', 'siw' ),
			'entry_list' => 1,
		)
	);
	$fields['telefoonnummer'] = siw_get_form_field( 'text',
		array(
			'ID' => 'telefoonnummer',
			'label' => __( 'Telefoonnummer', 'siw' ),
			'slug' => 'telefoonnummer',
			'config' =>
			array(
				'type_override' => 'tel',
			),
		)
	);
	$fields['geslacht'] = siw_get_form_field( 'radio',
		array(
			'ID' => 'geslacht',
			'slug' => 'geslacht',
			'label' => __( 'Geslacht', 'siw' ),
			'config' =>
			array(
				'inline' => 1,
				'option' => $genders,
			),
		)
	);
	$fields['geboortedatum'] = siw_get_form_field( 'text',
		array(
			'ID' => 'geboortedatum',
			'label' => __( 'Geboortedatum', 'siw' ),
			'slug' => 'geboortedatum',
			'config' =>
			array(
				'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
			),
		)
	);
	$fields['postcode'] = siw_get_form_field( 'text',
		array(
			'ID' => 'postcode',
			'label' => __( 'Postcode', 'siw' ),
			'slug' => 'postcode',
			'config' =>
			array(
				'custom_class' => 'postcode',
				'placeholder' => '1234 AB',
			),
		)
	);
	$fields['huisnummer'] = siw_get_form_field( 'text',
		array(
			'ID' => 'huisnummer',
			'label' => __( 'Huisnummer', 'siw' ),
			'slug' => 'huisnummer',
			'config' =>
			array(
				'custom_class' => 'huisnummer',
			),
		)
	);

	$fields['straat'] = siw_get_form_field( 'text',
		array(
			'ID' => 'straat',
			'slug' => 'straat',
			'label' => __( 'Straat', 'siw' ),
			'config' =>
			array(
				'custom_class' => 'straat',
			)
		)
	);
	$fields['woonplaats'] = siw_get_form_field( 'text',
		array(
			'ID' => 'woonplaats',
			'label' => __( 'Woonplaats', 'siw' ),
			'slug' => 'woonplaats',
			'config' =>
			array(
				'custom_class' => 'plaats',
			),
		)
	);

	$fields['vraag'] = siw_get_form_field( 'paragraph',
		array(
			'ID' => 'vraag',
			'label' => __( 'Vraag', 'siw' ),
			'slug' => 'vraag',
		)
	);

	$fields['bekend'] = siw_get_form_field( 'checkbox',
		array(
			'ID' => 'bekend',
			'label' => __( 'Hoe heb je van SIW gehoord?', 'siw' ),
			'slug' => 'bekend',
			'config' =>
			array(
				'option' =>
				array(
					'google' =>
					array(
						'value' => 'google',
						'label' => __( 'Google', 'siw' ),
					),
					'social_media' =>
					array(
						'value' => 'social_media',
						'label' => __( 'Social Media', 'siw' ),
					),
					'familie_vrienden' =>
					array(
						'value' => 'familie_vrienden',
						'label' => __( 'Familie / vrienden', 'siw' ),
					),
					'anders' =>
					array(
						'value' => 'anders',
						'label' => __( 'Anders', 'siw' ),
					),
				),
			),
		)
	);
	$fields['bekend_anders'] = siw_get_form_field( 'text',
		array(
			'ID' => 'bekend_anders',
			'label' => __( 'Namelijk', 'siw' ),
			'hide_label' => 1,
			'slug' => 'bekend_anders',
			'conditions' =>
			array(
				'type' => 'con_bekend_anders',
			),
		)
	);
	$fields['verzenden'] = siw_get_form_field( 'button',
		array(
			'ID' => 'verzenden',
			'label' => __( 'Verzenden', 'siw' ),
			'slug' => 'verzenden',
			array(
				'type' => 'submit',
				'class' => 'kad-btn kad-btn-primary',
			),
		)
	);
	$fields['cv'] = siw_get_form_field( 'file',
		array(
			'ID' => 'cv',
			'label' => __( 'Upload hier je CV (optioneel)', 'siw'),
			'slug' => 'cv',
		)
	);
	$fields['nieuwsbrief'] = siw_get_form_field( 'checkbox',
		array(
			'ID' => 'nieuwsbrief',
			'label' => __( 'Wil je de SIW nieuwsbrief ontvangen?', 'siw' ),
			'hide_label' => 1,
			'slug' => 'nieuwsbrief',
			'config' =>
			array(
				'option' =>
				array(
					'ja' =>
					array(
						'value' => 'ja',
						'label' => __( 'Ja, ik wil graag de SIW nieuwsbrief ontvangen', 'siw' ),
					),
				),
			),
		)
	);

	/* Filter om extra velden toe te voegen */
	$fields = apply_filters( 'siw_standard_form_fields', $fields );

	/* Afbreken als veld niet gevonden is */
	if ( ! isset( $fields[ $slug ] ) ) {
		return;
	}
	$field = $fields[ $slug ];
	$field = wp_parse_args_recursive( $options, $field );

	return $field;
}


/**
 * [siw_get_standard_form_condition description]
 * @param  array $slug
 * @param  array $options
 * @return array
 */
function siw_get_standard_form_condition( $slug, $options = array() ) {

	$conditions = array();

	$conditions['con_bekend_anders'] =
	array(
		'id' => 'con_bekend_anders',
		'name' => 'Bekend anders',
		'type' => 'show',
		'group' =>
		array(
			'con_bekend_anders_group_1' =>
			array(
				'con_bekend_anders_group_1_line_1' =>
				array(
					'parent' => 'con_bekend_anders_group_1',
					'field' => 'bekend',
					'compare' => 'is',
					'value' => 'anders',
				),
			),
		),
	);

	/* Filter om extra condities toe te voegen */
	$conditions = apply_filters( 'siw_standard_form_conditions', $conditions );

	/* Afbreken als conditie niet gevonden is */
	if ( ! isset( $conditions[ $slug ] ) ) {
		return;
	}
	$condition = $conditions[ $slug ];

	return $condition;

}
