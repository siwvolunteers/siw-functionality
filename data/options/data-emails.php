<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor e-mails
 * 
 * @package   SIW\Options
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

$sender = [
	'id'                => 'sender',
	'name'              => __( 'Afzender', 'siw' ),
	'type'              => 'email',
	//'required'          => true,
	'label_description' => __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
];
	
$signature = [
	'id'                => 'signature',
	'name'              => __( 'Ondertekening', 'siw' ),
	'type'              => 'fieldset_text',
	'options'           => [
		'name'    => __( 'Naam', 'siw' ),
		'title'   => __( 'Functie', 'siw' ),
	],
];

$data = [
	'id'             => 'emails',
	'title'          => __( 'E-mails', 'siw' ),
	'settings_pages' => 'emails',
	'tabs'           => [
		'esc'            => __( 'ESC', 'siw' ),
		'workcamps'      => __( 'Groepsprojecten', 'siw' ),
		'info_day'       => __( 'Infodag', 'siw' ),
		'enquiry'        => __( 'Infoverzoeken', 'siw' ),
		'dutch_projects' => __( 'Nederlandse projecten', 'siw' ),
		'tailor_made'    => __( 'Op Maat', 'siw' ),
		'cooperation'    => __( 'Samenwerking', 'siw' ),
	],
	'tab_style' => 'left',
	'fields'    => [
		[
			'id'      => 'esc_email',
			'type'    => 'group',
			'tab'     => 'esc',
			'fields'  => [
				[
					'type'              => 'heading',
					'name'              => __( 'Aanmelding ESC', 'siw' ),
				],
				$sender,
				$signature,
			],
		],
		[
			'id'      => 'tailor_made_email',
			'type'    => 'group',
			'tab'     => 'tailor_made',
			'fields'  => [
				[
					'type'              => 'heading',
					'name'              => __( 'Aanmelding Op Maat', 'siw' ),
				],
				$sender,
				$signature,
			],
		],
		[
			'id'      => 'workcamp_application_email',
			'type'    => 'group',
			'tab'     => 'workcamps',
			'fields'  => [
				[
					'type'              => 'heading',
					'name'              => __( 'Aanmelding Groepsproject', 'siw' ),
				],
				$sender,
				$signature,
			],
		],
		[
			'id'      => 'enquiry_general_email',
			'type'    => 'group',
			'tab'     => 'enquiry',
			'fields'  => [
				[
					'type'              => 'heading',
					'name'              => __( 'Infoverzoek algemeen', 'siw' ),
				],
				$sender,
				$signature,
			],
		],
		[
			'id'      => 'enquiry_workcamp_email',
			'type'    => 'group',
			'tab'     => 'enquiry',
			'fields'  => [
				[
					'type'              => 'heading',
					'name'              => __( 'Infoverzoek groepsproject', 'siw' ),
				],
				$sender,
				$signature,
			],
		],
		[
			'id'      => 'info_day_email',
			'type'    => 'group',
			'tab'     => 'info_day',
			'fields'  => [
				[
					'type'              => 'heading',
					'name'              => __( 'Aanmelding infodag', 'siw' ),
				],
				$sender,
				$signature,
			],
		],
		[
			'id'      => 'camp_leader_email',
			'type'    => 'group',
			'tab'     => 'dutch_projects',
			'fields'  => [
				[
					'type'              => 'heading',
					'name'              => __( 'Aanmelding projectbegeleider NP', 'siw' ),
				],
				$sender,
				$signature,
			],
		],
		[
			'id'      => 'cooperation_email',
			'type'    => 'group',
			'tab'     => 'cooperation',
			'fields'  => [
				[
					'type'              => 'heading',
					'name'              => __( 'Samenwerking', 'siw' ),
				],
				$sender,
				$signature,
			],
		],
	],
];

return $data;
