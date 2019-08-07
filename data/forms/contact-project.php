<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van contactformulier bij Groepsproject
 * 
 * @package   SIW\Forms
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */

$args = [
	'name' => __( 'Infoverzoek Groepsproject', 'siw' ),
];

$processors = [];

$intro = [
	__( 'Heb je een vraag over dit project?', 'siw' ),
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
		sprintf( __( 'Leuk om te zien dat je interesse hebt getoond in het project %s.', 'siw' ), '<a href="{embed_post:permalink}" target="_blank" style="text-decoration:none">{embed_post:post_title}<a/>') . SPACE .
		__( 'Je hebt ons een vraag gesteld.', 'siw' ) . SPACE .
		__( 'Wellicht was er iets niet helemaal duidelijk of wil je graag meer informatie ontvangen.', 'siw' ) . SPACE .
		__( 'Wat de reden ook was, wij helpen je graag verder.', 'siw' ) . SPACE .
		__( 'We nemen zo snel mogelijk contact met je op.', 'siw' ),
	'recipient_name'  => '%voornaam% %achternaam%',
	'recipient_email' => '%emailadres%',
];

$notification = [
	'subject' => sprintf( __( 'Informatieverzoek project %s', 'siw'),  '{embed_post:post_title}' ),
	'message' =>
		sprintf( __( 'Via de website is een vraag gesteld over het project %s', 'siw' ), '{embed_post:post_title} (<a href="{embed_post:permalink}" target="_blank" style="text-decoration:none">{embed_post:permalink}<a/>)<br/>' ),
	'reply_to' => '%emailadres%',
];

return [
	'args'          => $args,
	'intro'         => $intro,
	'pages'         => $pages,
	'fields'        => $fields,
	'confirmation'  => $confirmation,
	'notification'  => $notification,
	'email_option'  => 'enquiry_workcamp_email',
];