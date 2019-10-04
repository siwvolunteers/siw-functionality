<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor groepsprojecten
 * 
 * @package   SIW\Data
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'email',
	'title'          => __( 'Groepsprojecten', 'siw' ),
	'settings_pages' => 'workcamps',
	'tabs'           =>[
		'sale'    => __( 'Kortingsactie', 'siw' ),
		'archive' => __( 'Overzichtspagina', 'siw' ),
		'plato'   => __( 'Plato', 'siw' ),
	],
	'tab_style' => 'left',
	'fields'    => [
		[
			'id'      => 'workcamp_sale',
			'type'    => 'group',
			'tab'     => 'sale',
			'fields'  => [
				[
					'id'                => 'active',
					'name'              => __( 'Kortingsactie actief', 'siw' ),
					'type'              => 'switch',
					'on_label'          => __( 'Ja', 'siw' ),
					'off_label'         => __( 'Nee', 'siw'),
				],
				[
					'id'                => 'start_date',
					'name'              => __( 'Startdatum kortingsactie', 'siw' ),
					'type'              => 'date',
					'js_options'        => [
						'dateFormat'      => 'yy-mm-dd',
						'showButtonPanel' => false,
					],
					'readonly'          => true,
					'visible'           => [ 'workcamp_sale[active]', true ],
				],
				[
					'id'                => 'end_date',
					'name'              => __( 'Einddatum kortingsactie', 'siw' ),
					'type'              => 'date',
					'js_options'        => [
						'dateFormat'      => 'yy-mm-dd',
						'showButtonPanel' => false,
					],
					'readonly'          => true,
					'visible'           => [ 'workcamp_sale[active]', true ],
				],
				[
					'type'              => 'custom_html',
					'visible'           => [ 'workcamp_sale[active]', true ],
					'std'               => sprintf( 'Regulier: %s, Student: %s', SIW_Properties::WORKCAMP_FEE_REGULAR_SALE, SIW_Properties::WORKCAMP_FEE_STUDENT_SALE ),
					//TODO: netter + i18n
				],
			],
		],
		[
			'id'      => 'workcamp_teaser_text',
			'type'    => 'group',
			'tab'     => 'archive',
			'fields'  => [
				[
					'id'                => 'active',
					'name'              => __( 'Aankondiging tonen', 'siw' ),
					'type'              => 'switch',
					'on_label'          => __( 'Ja', 'siw' ),
					'off_label'         => __( 'Nee', 'siw'),
				],
				[
					'id'                => 'start_date',
					'name'              => __( 'Startdatum aankonding', 'siw' ),
					'type'              => 'date',
					'js_options'        => [
						'dateFormat'      => 'yy-mm-dd',
						'showButtonPanel' => false,
					],
					'readonly'          => true,
					'visible'           => [ 'workcamp_teaser_text[active]', true ],
				],
				[
					'id'                => 'end_date',
					'name'              => __( 'Einddatum aankonding', 'siw' ),
					'type'              => 'date',
					'js_options'        => [
						'dateFormat'      => 'yy-mm-dd',
						'showButtonPanel' => false,
					],
					'readonly'          => true,
					'visible'           => [ 'workcamp_teaser_text[active]', true ],
				],
			],
		],
		[
			'type'              => 'heading',
			'name'              => __( 'Outgoing Placements', 'siw' ),
			'desc'              => __( 'Afzender van de aanmelding bij export naar Plato', 'siw' ),
			'tab'               => 'plato',
		],
		[
			'id'                => 'plato_export_outgoing_placements_name',
			'name'              => __( 'Naam', 'siw' ),
			'type'              => 'text',
			'tab'               => 'plato',
		],
		[
			'id'                => 'plato_export_outgoing_placements_email',
			'name'              => __( 'E-mail', 'siw' ),
			'type'              => 'email',
			'tab'               => 'plato',
		],
		[
			'type'              => 'heading',
			'name'              => __( 'Coördinatoren voor Plato-import', 'siw' ),
			'desc'              => __( 'Staan op cc van mails naar regiospecialisten en ontvangt niet-toegewezen projecten.', 'siw' ),
			'tab'               => 'plato',
		],
		[
			'id'                => 'plato_import_supervisor',
			'name'              => __( 'E-mail', 'siw' ),
			'type'              => 'user',
			'field_type'        => 'select_advanced',
			'tab'               => 'plato',
			'clone'             => 'true',
		],

	],
];

return $data;
