<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van Algemeen contactformulier
 * 
 * @package   SIW\Forms
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */

$args = [
	'name' => __( 'Infoverzoek algemeen', 'siw' ),
];

$processors = [];

$intro = [
	__( 'Heb je een vraag of wil je graag meer informatie?', 'siw' ),
	__( 'Neem gerust contact met ons op.', 'siw' ),
	__( 'We staan voor je klaar en denken graag met jou mee!', 'siw' ),
];

$pages = [];

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
];

/** Bevestigingsmail */
$confirmation = [
	'subject' => __( 'Bevestiging informatieverzoek', 'siw' ),
	'message' =>
		sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor het invullen van ons contactformulier.', 'siw' ) . SPACE .
		__( 'Wij hebben je vraag ontvangen en we nemen zo snel mogelijk contact met je op.', 'siw' ),
	'recipient_name'  => '%voornaam% %achternaam%',
];

$notification = [
	'subject'  => 'Informatieverzoek %voornaam% %achternaam%',
	'message'  => 'Via de website is een vraag gesteld:' . BR,
];

return [
	'args'          => $args,
	'intro'         => $intro,
	'pages'         => $pages,
	'fields'        => $fields,
	'confirmation'  => $confirmation,
	'notification'  => $notification,
	'email_option'  => 'enquiry_general_email',
	'primary_email' => 'emailadres',
];