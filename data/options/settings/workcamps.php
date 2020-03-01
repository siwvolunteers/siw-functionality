<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Formatting;
use SIW\Properties;

/**
 * Opties voor groepsprojecten
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$continents = siw_get_continents( 'array' );

$approval_fields = [
	[
		'type'              => 'heading',
		'name'              => __( 'Beoordelen projecten', 'siw' ),
		'desc'              => __( 'Ontvangers van mail over te beoordelen projecten')
	],
	[
		'id'                => 'supervisor',
		'name'              => __( 'Coördinator', 'siw' ),
		'type'              => 'user',
		'desc'              => __( 'Staat op cc van alle mails', 'siw' ),
		'field_type'        => 'select_advanced',
	],
];
foreach ( $continents as $slug =>$name ) {
	$approval_fields[] = [
		'id'                => "responsible_{$slug}",
		'name'              => $name,
		'type'              => 'user',
		'field_type'        => 'select_advanced',
	];
}

$data = [
	'id'             => 'workcamps',
	'title'          => __( 'Groepsprojecten', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'workcamps',
	'fields'    => [
		[
			'id'      => 'workcamp_sale',
			'type'    => 'group',
			'fields'  => [
				[
					'type'              => 'heading',
					'name'              => __( 'Kortingsactie', 'siw' ),
				],
				[
					'id'                => 'active',
					'name'              => __( 'Actief', 'siw' ),
					'type'              => 'switch',
					'on_label'          => __( 'Ja', 'siw' ),
					'off_label'         => __( 'Nee', 'siw'),
				],
				[
					'type'              => 'custom_html',
					'visible'           => [ 'workcamp_sale[active]', true ],
					'std'       => Formatting::array_to_text(
						[
							sprintf(
								'%s: %s',
								__( 'Regulier', 'siw' ),
								Formatting::format_sale_amount( Properties::WORKCAMP_FEE_REGULAR, Properties::WORKCAMP_FEE_REGULAR_SALE )
							),
							sprintf(
								'%s: %s',
								__( 'Student', 'siw' ),
								Formatting::format_sale_amount( Properties::WORKCAMP_FEE_STUDENT, Properties::WORKCAMP_FEE_STUDENT_SALE )
							),
						], BR
					),
				],
				[
					'id'                => 'start_date',
					'name'              => __( 'Startdatum', 'siw' ),
					'type'              => 'date',
					'required'          => true,
					'visible'           => [ 'workcamp_sale[active]', true ],
				],
				[
					'id'                => 'end_date',
					'name'              => __( 'Einddatum', 'siw' ),
					'type'              => 'date',
					'required'          => true,
					'visible'           => [ 'workcamp_sale[active]', true ],
				],
			],
		],
		[
			'id'      => 'workcamp_teaser_text',
			'type'    => 'group',
			'fields'  => [
				[
					'type'      => 'heading',
					'name'      => __( 'Aankondiging nieuw seizoen', 'siw' ),
					'desc'      => __( 'Wordt getoond op overzichten van Groepsprojecten.', 'siw' ),
				],
				[
					'id'        => 'active',
					'name'      => __( 'Tonen', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw'),
				],
				[
					'id'        => 'start_date',
					'name'      => __( 'Startdatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'workcamp_teaser_text[active]', true ],
				],
				[
					'id'        => 'end_date',
					'name'      => __( 'Einddatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'workcamp_teaser_text[active]', true ],
				],
			],
		],
		[
			'id'      => 'workcamp_approval',
			'type'    => 'group',
			'fields'  => $approval_fields
		],
	],
];

return $data;
