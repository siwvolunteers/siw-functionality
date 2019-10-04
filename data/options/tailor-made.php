<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties
 * 
 * @package   SIW\Data
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'email',
	'title'          => __( 'Op Maat', 'siw' ),
	'settings_pages' => 'tailor-made',
	'fields'         => [
		[
			'id'      => 'tailor_made_sale',
			'type'    => 'group',
			'tab'     => 'tailor_made',
			'fields'  => [
				[
					'id'        => 'active',
					'name'      => __( 'Kortingsactie actief', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw'),
				],
				[
					'id'        => 'start_date',
					'name'      => __( 'Startdatum kortingsactie', 'siw' ),
					'type'      => 'date',
					'visible'   => [ 'tailor_made_sale[active]', true ],
				],
				[
					'id'        => 'end_date',
					'name'      => __( 'Einddatum kortingsactie', 'siw' ),
					'type'      => 'date',
					'visible'   => [ 'tailor_made_sale[active]', true ],
				],
				[
					'type'      => 'custom_html',
					'visible'   => [ 'tailor_made_sale[active]', true ],
					'std'       => sprintf( 'Regulier: %s, Student: %s', SIW_Properties::TAILOR_MADE_FEE_REGULAR_SALE, SIW_Properties::TAILOR_MADE_FEE_STUDENT_SALE ),
					//TODO: netter + i18n
				],
			],
		],
	],
];

return $data;
