<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\i18n;

/**
 * Gegevens van Algemeen contactformulier
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$language = i18n::get_current_language();
$project_options = [];

$args = [
	'country'    => 'nederland',
	'return'     => 'objects',
	'limit'      => -1,
];
$projects = wc_get_products( $args );

foreach ( $projects as $project ) {
	$slug = sanitize_title( $project->get_sku() );
	$project_options[ $slug ] = ! empty( $project->get_meta( "dutch_projects_name_{$language}" ) ) ? $project->get_meta( "dutch_projects_name_{$language}" ) : $project->get_attribute( 'Projectnaam' );
}


$args = [
	'name'            => __( 'Projectbegeleider NP', 'siw' ),
	'postcode_lookup' => true,
];

$processors = [];

$intro = [
	__( 'De Nederlandse vrijwilligersprojecten vinden plaats in de zomermaanden.', 'siw' ),
	__( 'We zijn hiervoor altijd op zoek naar projectbegeleiders.', 'siw' ),
	__( 'Meld je aan via onderstaand formulier en geef aan naar welk project je voorkeur uitgaat.', 'siw' ),
	__( 'Vervolgens ontvang je een uitnodiging voor een kennismakingsgesprek.', 'siw' ),
	__( 'In dit gesprek hopen wij erachter te komen wat je verwachtingen en kwaliteiten zijn.', 'siw' ),
];

$pages = [];

$fields[0] = [
	[
		'voornaam',
		'achternaam',
	],
	[
		'geboortedatum',
		'geslacht',
	],
	[
		'emailadres',
		'telefoonnummer',
	],
	[
		'postcode',
		'huisnummer',
	],
	[
		'straat',
		'woonplaats',
	],
	[
		[
			'slug'   => 'motivatie',
			'type'   => 'paragraph',
			'label'  => __( 'Waarom zou je graag een begeleider willen worden voor de Nederlandse vrijwilligersprojecten?', 'siw' ),
			'config' => [
				'rows' => 7,
			]
		],
		[
			'slug'   => 'voorkeur',
			'type'   => 'checkbox',
			'label'  => __( 'Heb je een voorkeur om een bepaald Nederlands vrijwilligersproject te begeleiden?', 'siw' ),
			'config' => [
				'option' => $project_options,
			]
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
			'same_cell' => true,
		],
		[
			'slug'     => 'opmerkingen',
			'type'     => 'paragraph',
			'label'    => __( 'Overige opmerkingen?', 'siw' ),
			'required' => false,
		]
	],
];

/** Bevestigingsmail TODO: tekst conditioneel van datum maken*/
$confirmation = [
	'subject' => __( 'Bevestiging aanmelding', 'siw' ),
	'message' =>
		sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor jouw aanmelding.', 'siw') . SPACE .
		__( 'Wat leuk dat je interesse hebt om projectbegeleider te worden voor de Nederlandse vrijwilligersprojecten.', 'siw' ) . SPACE .
		__( 'Een creatieve uitdaging die je nooit meer zal vergeten!', 'siw' ) . SPACE .
		__( 'Zoals oud-projectbegeleider Diederik (project in Friesland) het omschreef:', 'siw' ) . BR .
		'<span style="font-style:italic">"'.
		__( 'Het is ontzettend leerzaam om met zoveel verschillende mensen om te gaan, iedereen gemotiveerd te houden en te zorgen dat iedereen zich op zijn gemak voelt.', 'siw' ) . SPACE .
		__( 'Daarnaast zie je hoe de groep zich ontwikkelt, een prachtig proces om van zo dichtbij mee te mogen maken.', 'siw' ) .
		'"</span>' . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Hoe gaat het nu verder?', 'siw' ) .
		'</span>' . BR .
		__( 'Wij werven doorgaans in de maanden maart tot en met mei projectbegeleiders om de zomerprojecten te begeleiden.', 'siw' ) . SPACE .
		__( 'Mocht jij je in deze periode hebben aangemeld, dan zullen wij contact met je opnemen.', 'siw' ) . SPACE .
		__( 'Ligt jouw aanmelding buiten onze wervingsperiode? Geen probleem.', 'siw' ) . SPACE .
		__( 'Wij voegen jouw aanmelding toe aan onze database voor een volgend zomerseizoen.', 'siw' ),
	'recipient_name'  => '%voornaam% %achternaam%',
];

$notification = [
	'subject'  => 'Aanmelding projectbegeleider',
	'message'  => 'Via de website is onderstaande aanmelding voor begeleider NP binnengekomen:',
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
