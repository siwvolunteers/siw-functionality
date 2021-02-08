<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van formulier voor samenwerking
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$args = [
	'name'    => __( 'Samenwerking', 'siw' ),
	'success' => __( 'Uw bericht werd succesvol verzonden.', 'siw' ),
];

$processors = [];

$intro = [
	__( 'Bent u enthousiast geworden?', 'siw' ),
	__( 'SIW is het hele jaar door op zoek naar enthousiaste maatschappelijke organisaties die samen met ons willen onderzoeken wat de mogelijkheden zijn voor een samenwerking.', 'siw' ),
	__( 'Laat uw gegevens achter in onderstaand formulier en wij nemen contact met u op om de mogelijkheden te bespreken.', 'siw' ),
];

$pages = [];

$fields[0] = [
	[
		[
			'slug'  => 'naam_organisatie',
			'type'  => 'text',
			'label' => __( 'Naam organisatie', 'siw' ),
			'width' => 100,
		],
		[
			'slug'  => 'naam_contactpersoon',
			'type'  => 'text',
			'label' => __( 'Naam contactpersoon', 'siw' ),
			'width' => 100,
		],
	],
	[
		'emailadres',
		'telefoonnummer',
	],
	[
		[
			'slug'   => 'toelichting',
			'type'   => 'paragraph',
			'label'  => __( 'Beschrijf kort op welke manier u wilt samenwerken met SIW', 'siw' ),
			'config' => [
				'rows' => 7,
			],
			'width' => 100,
		]
	],
];

/** Bevestigingsmail */
$confirmation = [
	'subject' => __( 'Bevestiging interesse samenwerking', 'siw' ),
	'message' =>
		sprintf( __( 'Beste %s,', 'siw' ), '%naam_contactpersoon%' ) . BR2 .
		__( 'Wat leuk dat u interesse heeft in een samenwerking met SIW Internationale Vrijwilligersprojecten!', 'siw' ) . SPACE .
		__( 'Wij willen u bedanken voor het achterlaten van uw contactgegevens en wensen.', 'siw' ) . SPACE .
		__( 'Ons streven is binnen drie tot vijf werkdagen contact met u op te nemen om de mogelijkheden te bespreken.', 'siw' ),
	'recipient_name'  => '%voornaam% %achternaam%',
];

$notification = [
	'subject'  => 'Interesse samenwerking',
	'message'  => 'Via de website is onderstaand bericht verstuurd:',
];

return [
	'args'          => $args,
	'intro'         => $intro,
	'pages'         => $pages,
	'fields'        => $fields,
	'confirmation'  => $confirmation,
	'notification'  => $notification,
	'email_option'  => 'cooperation_email',
	'primary_email' => 'emailadres',
];