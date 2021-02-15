<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van Algemeen contactformulier
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

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
		'voornaam',
		'achternaam',
	],
	[
		'emailadres',
		'telefoonnummer',
	],
	[
		[
			'slug'  => 'vraag',
			'type'  => 'paragraph',
			'label' => __( 'Vraag', 'siw' ),
			'width' => 100,
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
	'primary_email' => 'emailadres',
];