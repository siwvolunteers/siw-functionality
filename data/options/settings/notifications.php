<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Modules\Topbar;

/**
 * Opties voor notificaties
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'notifications',
	'title'          => __( 'Notificaties', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'notifications',
	'fields'         => [
		[
			'id'      => 'topbar',
			'type'    => 'group',
			'fields'  => [
				[
					'type'      => 'heading',
					'name'      => __( 'Banner', 'siw' ),
				],
				[
					'id'        => 'show_custom_content',
					'name'      => __( 'Custom content tonen', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw'),
				],
				[
					'id'      => 'custom_content',
					'type'    => 'group',
					'visible' => [ 'topbar[show_custom_content]', true ],
					'fields' => [
						[
							'id'                => 'intro',
							'name'              => __( 'Intro', 'siw' ),
							'type'              => 'text',
							'required'          => true,
							'label_description' => __( 'Wordt verborgen op mobiel', 'siw' ),
						],
						[
							'id'       => 'link_text',
							'name'     => __( 'Tekst voor link', 'siw' ),
							'type'     => 'text',
							'required' => true,
						],
						[
							'id'       => 'link_url',
							'name'     => __( 'URL voor link', 'siw' ),
							'type'     => 'url',
							'required' => true,
						],
						[
							'id'        => 'start_date',
							'name'      => __( 'Startdatum', 'siw' ),
							'type'      => 'date',
							'required'  => true,
						],
						[
							'id'        => 'end_date',
							'name'      => __( 'Einddatum', 'siw' ),
							'type'      => 'date',
							'required'  => true,
						],
					],
				],
				[
					'type'              => 'divider',
				],
				[
					'id'                => 'show_sale_content', //TODO: preview van gekozen items
					'name'              => __( 'Kortingsactie tonen', 'siw' ),
					'label_description' => __( 'Indien de kortingsactie actief is', 'siw' ),
					'type'              => 'switch',
					'on_label'          => __( 'Ja', 'siw' ),
					'off_label'         => __( 'Nee', 'siw'),
				],
				[
					'type'              => 'divider',
				],
				[
					'id'                => 'show_event_content',
					'name'              => __( 'Evenement tonen', 'siw' ),
					'label_description' => sprintf( __( 'Als er een evenement binnen %s dagen begint', 'siw' ), Topbar::EVENT_SHOW_DAYS_BEFORE ),
					'type'              => 'switch',
					'on_label'          => __( 'Ja', 'siw' ),
					'off_label'         => __( 'Nee', 'siw'),
				],
				[
					'type'              => 'divider',
				],
				[
					'id'                => 'show_job_posting_content',
					'name'              => __( 'Vacature tonen', 'siw' ),
					'label_description' => __( 'Indien er een actieve vacature uitgelicht is', 'siw' ),
					'type'              => 'switch',
					'on_label'          => __( 'Ja', 'siw' ),
					'off_label'         => __( 'Nee', 'siw'),
				],
			],
		],
	],
];

return $data;
