<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van soorten werk
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 *
 * @todo      ESC en groepsprojecten toevoegen
 */

$siw_data = [
	[
		'slug'                 => 'restauratie',
		'plato_code'           => 'RENO',
		'name'                 => __( 'Restauratie', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'natuur',
		'plato_code'           => 'ENVI',
		'name'                 => __( 'Natuur', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => true,
	],
	[
		'slug'                 => 'constructie',
		'plato_code'           => 'CONS',
		'name'                 => __( 'Constructie', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => true,
	],
	[
		'slug'                 => 'archeologie',
		'plato_code'           => 'ARCH',
		'name'                 => __( 'Archeologie', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'sociaal',
		'plato_code'           => 'SOCI',
		'name'                 => __( 'Sociaal', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => true,
	],
	[
		'slug'                 => 'kinderen',
		'plato_code'           => 'KIDS',
		'name'                 => __( 'Kinderen', 'siw' ),
		'needs_review'         => true,
		'tailor_made_projects' => true,
	],
	[
		'slug'                 => 'thema',
		'plato_code'           => 'STUD',
		'name'                 => __( 'Thema', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'gehandicapten',
		'plato_code'           => 'DISA',
		'name'                 => __( 'Gehandicapten', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'handarbeid',
		'plato_code'           => 'MANU',
		'name'                 => __( 'Handarbeid', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'onderwijs',
		'plato_code'           => 'EDU',
		'name'                 => __( 'Onderwijs', 'siw' ),
		'needs_review'         => true,
		'tailor_made_projects' => true,
	],
	[
		'slug'                 => 'ouderen',
		'plato_code'           => 'ELDE',
		'name'                 => __( 'Ouderen', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'festival',
		'plato_code'           => 'FEST',
		'name'                 => __( 'Festival', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'cultuur',
		'plato_code'           => 'CULT',
		'name'                 => __( 'Cultuur', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => true,
	],
	[
		'slug'                 => 'landbouw',
		'plato_code'           => 'AGRI',
		'name'                 => __( 'Landbouw', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => true,
	],
	[
		'slug'                 => 'kunst',
		'plato_code'           => 'ART',
		'name'                 => __( 'Kunst', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'sport',
		'plato_code'           => 'SPOR',
		'name'                 => __( 'Sport', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'yoga',
		'plato_code'           => 'YOGA',
		'name'                 => __( 'Yoga', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'taalcursus',
		'plato_code'           => 'LANG',
		'name'                 => __( 'Taalcursus', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'taal',
		'plato_code'           => 'TRAS',
		'name'                 => __( 'Taal', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'dieren',
		'plato_code'           => 'ANIM',
		'name'                 => __( 'Dieren', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'projectbegeleider',
		'plato_code'           => 'LEAD',
		'name'                 => __( 'Projectbegeleider', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'erfgoed',
		'plato_code'           => 'HERI',
		'name'                 => __( 'Erfgoed', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
	[
		'slug'                 => 'vluchtelingen',
		'plato_code'           => 'REFU',
		'name'                 => __( 'Vluchtelingen', 'siw' ),
		'needs_review'         => false,
		'tailor_made_projects' => false,
	],
];
return $siw_data;
