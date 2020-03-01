<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Opties voor e-mails
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */



$forms = [];
if ( class_exists( '\Caldera_Forms_Forms' ) ) {
	$forms = Caldera_Forms_Forms::get_forms( true );
}

$email_fields = [
	[
		'id'      => 'email_settings',
		'type'    => 'group',
		'fields' => [
			[
				'type' => 'heading',
				'name' => __( 'Standaardinstellingen', 'siw' ),
				'desc' => __( 'Afzender en ontvanger van bevestigingsmail', 'siw' ),
			],
			[
				'id'      => 'email',
				'name'    => __( 'E-mailadres', 'siw' ),
				'type'    => 'email',
			],
			[
				'id'      => 'name',
				'name'    => __( 'Naam', 'siw' ),
				'type'    => 'text',
			],
			[
				'id'      => 'title',
				'name'    => __( 'Functie', 'siw' ),
				'type'    => 'text',
			],
		]
	]
];
//TODO: refactor zodat GP en nieuwsbrief hier ook bij kunnen
foreach ( $forms as $form ) {
	$email_fields[] = [
		'id'     => "{$form['ID']}_email",
		'type'   => 'group',
		'fields' => [
			[
				'type' => 'heading',
				'name' => $form['name'],
			],
			[
				'id'        => 'use_specific',
				'name'      => __( 'Gebruik afwijkende instellingen', 'siw' ),
				'type'      => 'switch',
				'on_label'  => __( 'Ja', 'siw' ),
				'off_label' => __( 'Nee', 'siw' ),
			],
			[
				'id'       => 'email',
				'name'     => __( 'E-mailadres', 'siw' ),
				'type'     => 'email',
				'required' => true,
				'visible'  => [ "{$form['ID']}_email[use_specific]", true ],
			],
			[
				'id'       => 'name',
				'name'     => __( 'Naam', 'siw' ),
				'type'     => 'text',
				'required' => true,
				'visible'  => [ "{$form['ID']}_email[use_specific]", true ],
			],
			[
				'id'       => 'title',
				'name'     => __( 'Functie', 'siw' ),
				'type'     => 'text',
				'required' => true,
				'visible'  => [ "{$form['ID']}_email[use_specific]", true ],
			],
		]
	];
}

$email_fields[] = [
	'id'      => 'workcamp_email',
	'type'    => 'group',
		'fields' => [
		[
			'type' => 'heading',
			'name' => __( 'Groepsprojecten', 'siw' ),
		],
		[
			'id'        => 'use_specific',
			'name'      => __( 'Gebruik afwijkende instellingen', 'siw' ),
			'type'      => 'switch',
			'on_label'  => __( 'Ja', 'siw' ),
			'off_label' => __( 'Nee', 'siw' ),
		],
		[
			'id'       => 'email',
			'name'     => __( 'E-mailadres', 'siw' ),
			'type'     => 'email',
			'required' => true,
			'visible'  => [ 'workcamp_email[use_specific]', true ],
		],
		[
			'id'       => 'name',
			'name'     => __( 'Naam', 'siw' ),
			'type'     => 'text',
			'required' => true,
			'visible'  => [ 'workcamp_email[use_specific]', true ],
		],
		[
			'id'       => 'title',
			'name'     => __( 'Functie', 'siw' ),
			'type'     => 'text',
			'required' => true,
			'visible'  => [ 'workcamp_email[use_specific]', true ],
		],
	]
];

$email_fields[] = [
	'id'      => 'newsletter_email',
	'type'    => 'group',
		'fields' => [
		[
			'type' => 'heading',
			'name' => __( 'Nieuwsbrief', 'siw' ),
		],
		[
			'id'        => 'use_specific',
			'name'      => __( 'Gebruik afwijkende instellingen', 'siw' ),
			'type'      => 'switch',
			'on_label'  => __( 'Ja', 'siw' ),
			'off_label' => __( 'Nee', 'siw' ),
		],
		[
			'id'       => 'email',
			'name'     => __( 'E-mailadres', 'siw' ),
			'type'     => 'email',
			'required' => true,
			'visible'  => [ 'newsletter_email[use_specific]', true ],
		],
		[
			'id'       => 'name',
			'name'     => __( 'Naam', 'siw' ),
			'type'     => 'text',
			'required' => true,
			'visible'  => [ 'newsletter_email[use_specific]', true ],
		],
		[
			'id'       => 'title',
			'name'     => __( 'Functie', 'siw' ),
			'type'     => 'text',
			'required' => true,
			'visible'  => [ 'newsletter_email[use_specific]', true ],
		],
	]
];



$data = [
	'id'             => 'emails',
	'title'          => __( 'E-mails', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'email',
	'fields'         => $email_fields,
];

return $data;
