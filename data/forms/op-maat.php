<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van Op Maat
 * 
 * @package   SIW\Data
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */

$args = [
	'name' => __( 'Op Maat', 'siw' ),
];

$processors = [];

$intro = [
	__( 'Interesse in een Project Op Maat?', 'siw' ) . SPACE .
	__( 'Meld je dan aan via onderstaand formulier.', 'siw' ) . SPACE .
	__( 'Vervolgens zal één van onze regiospecialisten contact met je opnemen voor een kennismakingsgesprek.', 'siw' ) . SPACE .
	sprintf( __( 'Weet je nog niet precies waar je naar toe wil, meld je dan aan voor één van onze <a href="%s">Infodagen</a> en laat je inspireren.', 'siw' ), SIW_i18n::get_translated_page_url( siw_get_option( 'info_days_explanation_page' ) ) )
];

$pages = [
	0 => __( 'Personalia', 'siw' ),
	1 => __( 'Project', 'siw' ),
	2 => __( 'Talenkennis', 'siw' ),
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
			'slug'   => 'geboortedatum',
			'type'   => 'text',
			'label'  => __( 'Geboortedatum', 'siw' ),
			'config' => [
				'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
			],
		],
		[
			'slug'  => 'geslacht',
			'type'  => 'radio',
			'label' => __( 'Geslacht', 'siw' ),
			'config' => [
				'inline' => true,
				'option' => siw_get_genders(),
			]
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
			'slug'   => 'postcode',
			'type'   => 'text',
			'label'  => __( 'Postcode', 'siw' ),
			'config' => [
				'custom_class' => 'postcode',
				'placeholder'  => '1234 AB',
			],
		],
		[
			'slug'   => 'huisnummer',
			'type'   => 'text',
			'label'  => __( 'Huisnummer', 'siw' ),
			'config' => [
				'custom_class' => 'huisnummer',
			],
		]
	],
	[
		[
			'slug'   => 'straat',
			'type'   => 'text',
			'label'  => __( 'Straat', 'siw' ),
			'config' => [
				'custom_class' => 'straat',
			],
		],
		[
			'slug'   => 'woonplaats',
			'type'   => 'text',
			'label'  => __( 'Woonplaats', 'siw' ),
			'config' => [
				'custom_class' => 'plaats',
			],
		],
	],
];

$fields[1] = [
	[
		[
			'slug'   => 'motivatie',
			'type'   => 'paragraph',
			'label'  => __( 'Waarom zou je graag vrijwilligerswerk willen doen?', 'siw' ),
			'config' => [
				'rows' => 7,
			],
			'width'  => 12,
		],
	],
	[
		[
			'slug'   => 'bestemming',
			'type'   => 'paragraph',
			'label'  => __( 'In welk land of welke regio zou je graag vrijwilligerswerk willen doen?', 'siw' ),
			'config' => [
				'rows' => 7,
			],
			'width'  => 12,
		],
	],
	[
		[
			'slug'   => 'periode',
			'type'   => 'paragraph',
			'label'  => __( 'In welke periode zou je op avontuur willen?', 'siw' ),
			'config' => [
				'rows' => 7,
			],
			'width'  => 12,
		]
	],
	[
		[
			'slug'   => 'cv',
			'type'   => 'file',
			'label'  => __( 'Upload hier je CV (optioneel)', 'siw'),
			'config' => [
				'attach'     => true,
				'media_lib'  => false,
				'allowed'    => 'pdf,docx',
				'max_upload' => wp_max_upload_size(),
			],
			'required' => false,
			'width'    => 12,
		],
	],
];


$volunteer_languages = siw_get_languages('volunteer');
foreach ( $volunteer_languages as $language ) {
	$languages[ $language->get_slug() ] = $language->get_name();
}
$language_skill_levels = siw_get_language_skill_levels();

$fields[2] = [
	[
		[
			'slug'    => 'taal_1',
			'type'    => 'dropdown',
			'label'   => __( 'Taal 1', 'siw' ),
			'config'  => [
				'option' => $languages,
			]
		],
		[
			'slug'   => 'taal_1_niveau',
			'type'   => 'radio',
			'label'  => __( 'Niveau taal 1', 'siw' ),
			'config' => [
				'inline' => true,
				'option' => $language_skill_levels,
			],
		]
	],
	[
		[
			'slug'    => 'taal_2',
			'type'    => 'dropdown',
			'label'   => __( 'Taal 2', 'siw' ),
			'config'  => [
				'option' => $languages,
			]
		],
		[
			'slug'   => 'taal_2_niveau',
			'type'   => 'radio',
			'label'  => __( 'Niveau taal 2', 'siw' ),
			'config' => [
				'inline' => true,
				'option' => $language_skill_levels,
			],
		]
	],
	[
		[
			'slug'    => 'taal_3',
			'type'    => 'dropdown',
			'label'   => __( 'Taal 3', 'siw' ),
			'config'  => [
				'option' => $languages,
			]
		],
		[
			'slug'   => 'taal_3_niveau',
			'type'   => 'radio',
			'label'  => __( 'Niveau taal 3', 'siw' ),
			'config' => [
				'inline' => true,
				'option' => $language_skill_levels,
			],
		]
	],
];

/** Bevestigingsmail */
$confirmation = [
	'subject' => __( 'Aanmelding Vrijwilligerswerk Op Maat', 'siw' ),
	'message' =>
		sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor je aanmelding!', 'siw' ) . SPACE .
		 __( 'Leuk dat je hebt gekozen via SIW een Project Op Maat te doen.', 'siw' ) . SPACE .
		__( 'Wij zullen ons best gaan doen om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt.', 'siw' ) . BR2 .
		__( 'Onderaan deze e-mail vind je een overzicht van de gegevens zoals je die op het inschrijfformulier hebt ingevuld.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Wat gaat er nu gebeuren?', 'siw' ) .
		'</span>' . BR .
		__( 'Jouw aanmelding voor Vrijwilligerswerk Op Maat wordt doorgestuurd naar onze SIW-regiospecialisten.', 'siw' ) . SPACE .
		__( 'Vervolgens neemt één van de regiospecialisten contact met je op om een kennismakingsgesprek in te plannen.', 'siw' ) . SPACE .
		__( 'Houd er rekening mee dat SIW met vrijwilligers werkt, waardoor het contact soms iets langer kan duren.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Kennismakingsgesprek', 'siw' ) .
		'</span>' . BR .
		__( 'Tijdens het kennismakingsgesprek gaat onze regiospecialist samen met jou kijken welk Project Op Maat het beste bij jouw wensen en voorkeuren aansluit.', 'siw' ) . SPACE .
		__( 'In dit gesprek komen ook thema’s naar voren zoals interesse in culturen, creativiteit, flexibiliteit, enthousiasme en reis- en vrijwilligerswerkervaring.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Voorbereidingsdag', 'siw' ) .
		'</span>' . BR .
		__( 'Na het kennismakingsgesprek nodigen we je uit voor een voorbereidingsdag.', 'siw' ) . SPACE .
		__( 'Mocht je nog geen keuze hebben gemaakt voor een project, dan kan de voorbereiding je helpen in het bepalen wat jij belangrijk vindt.', 'siw' ) . SPACE .
		__( 'Tijdens de voorbereiding krijg je informatie over de continenten, landen, cultuurverschillen en gezondheidszorg.', 'siw' ) . SPACE .
		__( 'Ook wordt er stilgestaan bij jouw verwachtingen, praktische projectsituatie en het zelfstandig verblijven in het buitenland.', 'siw' ) . SPACE .
		__( 'Tijdens de voorbereiding zullen gastsprekers en oud-deelnemers aanwezig zijn.', 'siw' ) . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Meer informatie', 'siw' ) .
		'</span>' . BR .
		sprintf( __( 'Als je nog vragen hebt, aarzel dan niet om contact op te nemen met ons kantoor via %s of via het nummer %s.', 'siw' ), SIW_Properties::EMAIL, SIW_Properties::PHONE ),
	'recipient_name'  => '%voornaam% %achternaam%',
	'recipient_email' => '%emailadres%',
];

$notification = [
	'subject'  => 'Aanmelding Vrijwilligerswerk Op Maat',
	'message'  => 'Via de website is onderstaande aanmelding voor Vrijwilligerswerk Op Maat binnengekomen:',
	'reply_to' => '%emailadres%',
];

return [
	'args'          => $args,
	'intro'         => $intro,
	'pages'         => $pages,
	'fields'        => $fields,
	'confirmation'  => $confirmation,
	'notification'  => $notification,
	'email_option'  => 'tailor_made_email',
];