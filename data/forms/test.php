<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van testformulier
 * 
 * @package   SIW\Forms
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */

$args = [
	'name' => __( 'Testformulier', 'siw' ),
];


$pages = [
	0 => __( 'Personalia', 'siw' ),
	1 => __( 'Project', 'siw' ),
];

$confirmation = [
	'subject' => __( 'Bevestiging informatieverzoek', 'siw' ),
	'message' =>
		sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor het invullen van ons contactformulier.', 'siw' ) . SPACE .
		__( 'Wij hebben je vraag ontvangen en we nemen zo snel mogelijk contact met je op.', 'siw' ),
	'recipient_name' => '%voornaam% %achternaam%',
	'recipient_email' => '%emailadres%',
];

$notification = [
	'subject'  => 'Informatieverzoek %voornaam% %achternaam%',
	'message'  => 'Via de website is een vraag gesteld:' . BR,
	'reply_to' => '%emailadres%',
];

$processors = [

];

$intro = [
	__( 'Heb je een vraag of wil je graag meer informatie?', 'siw' ),
	__( 'Neem gerust contact met ons op.', 'siw' ),
	__( 'We staan voor je klaar en denken graag met jou mee!', 'siw' ),
];

$fields[0] = [
	[
		[
			'slug'  => 'voornaam',
			'type'  => 'text',
			'label' => __( 'Voornaam', 'siw' ),
		],
		[
			'slug'  => 'achternaam',
			'type'  => 'text',
			'label' => __( 'Achternaam', 'siw' ),
		],
	],
	[
		[
			'slug'  => 'emailadres',
			'type'  => 'email',
			'label' => __( 'Emailadres', 'siw' ),
		],
		[
			'slug'     => 'telefoonnummer',
			'type'     => 'text',
			'label'    => __( 'Telefoonnummer', 'siw' ),
			'required' => false,
			'config'   => [
				'type_override' => 'tel',
			],
		],
	],
	[
		[
			'slug'  => 'vraag',
			'type'  => 'paragraph',
			'label' => __( 'Vraag', 'siw' ),
			'width' => 12,
		]
	],
	[
		[
			'slug'   => 'bekend',
			'type'   => 'checkbox',
			'label'  => __( 'Hoe heb je van SIW gehoord?', 'siw' ),
			'config' => [
				'option' => [
					'google'           => __( 'Google', 'siw' ),
					'social_media'     => __( 'Social Media', 'siw' ),
					'familie_vrienden' => __( 'Familie / vrienden', 'siw' ),
					'anders'           => __( 'Anders', 'siw' ),
				],
			],
		],
		[
			'slug'       => 'bekend_anders',
			'type'       => 'text',
			'label'      => __( 'Namelijk', 'siw' ),
			'hide_label' => 1,
			'condition'  => [
				'type'     => 'show',
				'groups'   => [
					[
						[
							'field'   => 'bekend',
							'compare' => 'is',
							'value'   => 'anders',
						],
					],
				],
			],
		],
	]
];

$fields[1] = [
	[
		[
			'slug'  => 'voornaam1',
			'type'  => 'text',
			'label' => __( 'Voornaam', 'siw' ),
		],
		[
			'slug'  => 'achternaam1',
			'type'  => 'text',
			'label' => __( 'Achternaam', 'siw' ),
		],
	],
];

return [
	'args'          => $args,
	'intro'         => $intro,
	'pages'         => $pages,
	'fields'        => $fields,
	'confirmation'  => $confirmation,
	'notification'  => $notification,
	'email_option'  => 'enquiry_general_email',
];
