<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van aanmeldformulier voor Infodag
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$infodays = siw_get_upcoming_info_days( 3 );
	
$callback = function( &$value, $key ) {
	$date = siw_meta( 'event_date', [], $value );
	$value = siw_format_date( $date, false );
};
array_walk( $infodays, $callback );

//Fallback voor als er nog geen infodagen bekend zijn
if ( empty( $infodays ) ) {
	$infodays[] = __( 'Nog niet bekend', 'siw' );
}

$args = [
	'name' => __( 'Aanmeldformulier voor de Infodag', 'siw' ),
];

$processors = [];

$intro = [
	__( 'Meld je hieronder aan voor de Infodag.', 'siw' ),
	__( 'Dan zorgen wij ervoor dat je van tevoren het programma en een routebeschrijving ontvangt.', 'siw' ),
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
			'slug'   => 'datum',
			'type'   => 'radio',
			'label'  => __( 'Naar welke Infodag wil je komen?', 'siw' ),
			'config' => [
				'inline' => false,
				'option' => $infodays,
			]
		],
		[

			'slug'     => 'soort_project',
			'type'     => 'checkbox',
			'label'    => __( 'Heb je interesse in een bepaald soort project?', 'siw' ),
			'required' => false,
			'config'   => [
				'option' => siw_get_project_types(),
			],
		]
	],
	[
		[
			'slug'     => 'bestemming',
			'type'     => 'checkbox',
			'label'    => __( 'Heb je interesse in een bepaalde bestemming?', 'siw' ),
			'required' => false,
			'config'   => [
				'option' => siw_get_continents( 'array' ),
			]
		],
	]
];

/** Bevestigingsmail */
$confirmation = [
	'subject' => sprintf( __( 'Aanmelding Infodag %s', 'siw' ), '%datum:label%' ),
	'message' =>
		sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		sprintf( __( 'Bedankt voor je aanmelding voor de Infodag van %s!', 'siw' ), '%datum:label%' )  . SPACE .
		__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
		__( 'Uiterlijk één week van te voren ontvang je de uitnodiging met de definitieve locatie en tijden.', 'siw' ) . BR2 .
		__( 'Als je nog vragen hebt, neem dan gerust contact met ons op.', 'siw' ),
	'recipient_name'  => '%voornaam% %achternaam%',
];

$notification = [
	'subject' => 'Aanmelding Infodag %datum:label%',
	'message' => 'Via de website is onderstaande aanmelding voor de Infodag van %datum:label% binnengekomen:',
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
