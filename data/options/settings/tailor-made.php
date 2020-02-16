<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Properties;
use SIW\Formatting;

/**
 * Opties voor Op Maat
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'tailor_made',
	'title'          => __( 'Op Maat', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'tailor_made',
	'fields'         => [
		[
			'id'      => 'tailor_made_sale',
			'type'    => 'group',
			'fields'  => [
				[
					'type'      => 'heading',
					'name'      => __( 'Kortingsactie', 'siw' ),
				],
				[
					'id'        => 'active',
					'name'      => __( 'Actief', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw'),
				],
				[
					'type'      => 'custom_html',
					'visible'   => [ 'tailor_made_sale[active]', true ],
					'std'       => Formatting::array_to_text(
						[
							sprintf(
								'%s: %s',
								__( 'Regulier', 'siw' ),
								Formatting::format_sale_amount( Properties::TAILOR_MADE_FEE_REGULAR, Properties::TAILOR_MADE_FEE_REGULAR_SALE )
							),
							sprintf(
								'%s: %s',
								__( 'Student', 'siw' ),
								Formatting::format_sale_amount( Properties::TAILOR_MADE_FEE_STUDENT, Properties::TAILOR_MADE_FEE_STUDENT_SALE )
							),
						], BR
					),
				],
				[
					'id'        => 'start_date',
					'name'      => __( 'Startdatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'tailor_made_sale[active]', true ],
				],
				[
					'id'        => 'end_date',
					'name'      => __( 'Einddatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'tailor_made_sale[active]', true ],
				],

			],
		],
	],
];

return $data;
