<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van aanmeldformulier voor Infodag
 * 
 * @package   SIW\Forms
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */



/* Keuzes infodag TODO: fallback als er geen opties zijn TODO: functie siw_get_infodays*/
$infodays = siw_get_option( 'info_days' );

$infodays = array_filter( $infodays, function( $date ) {
	return $date >= date( 'Y-m-d', time() + ( 2 * DAY_IN_SECONDS ) ); //TODO: constante of instelling voor aantal dagen
});

$callback = function( &$value, $key )  {
	$value = SIW_Formatting::format_date( $value, false );
};
array_walk( $infodays, $callback );

if ( empty( $infodays ) ) {
	$infodays[] = 'Nog niet bekend';
}

/* Continenten */
$continents = siw_get_continents();
foreach ( $continents as $continent ) {
	$destinations[ $continent->get_slug() ] = $continent->get_name();
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
				'option' => $destinations,
			]
		],
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
			'hide_label' => true,
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
			'same_cell' => true,
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
	'email_option'  => 'info_day_email',
	'primary_email' => 'emailadres',
];