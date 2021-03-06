<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van soorten werk
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo      ESC en groepsprojecten toevoegen
 */

$data = [
	[
		'slug'                  => 'restauratie',
		'plato_code'            => 'RENO',
		'name'                  => __( 'Restauratie', 'siw' ),
		'icon_class'            => 'siw-icon-house-damage',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'natuur',
		'plato_code'            => 'ENVI',
		'name'                  => __( 'Natuur', 'siw' ),
		'icon_class'            => 'siw-icon-tree',
		'tailor_made_projects'  => true,
	],
	[
		'slug'                  => 'constructie',
		'plato_code'            => 'CONS',
		'name'                  => __( 'Constructie', 'siw' ),
		'icon_class'            => 'siw-icon-tools',
		'tailor_made_projects'  => true,
	],
	[
		'slug'                  => 'archeologie',
		'plato_code'            => 'ARCH',
		'name'                  => __( 'Archeologie', 'siw' ),
		'icon_class'            => 'siw-icon-archway',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'sociaal',
		'plato_code'            => 'SOCI',
		'name'                  => __( 'Sociaal', 'siw' ),
		'icon_class'            => 'siw-icon-hands-helping',
		'tailor_made_projects'  => true,
	],
	[
		'slug'                  => 'kinderen',
		'plato_code'            => 'KIDS',
		'name'                  => __( 'Kinderen', 'siw' ),
		'icon_class'            => 'siw-icon-child',
		'tailor_made_projects'  => true,
	],
	[
		'slug'                  => 'thema',
		'plato_code'            => 'STUD',
		'name'                  => __( 'Thema', 'siw' ),
		'icon_class'            => 'siw-icon-camera',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'gehandicapten',
		'plato_code'            => 'DISA',
		'name'                  => __( 'Gehandicapten', 'siw' ),
		'icon_class'            => 'siw-icon-wheelchair',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'handarbeid',
		'plato_code'            => 'MANU',
		'name'                  => __( 'Handarbeid', 'siw' ),
		'icon_class'            => 'siw-icon-paint-roller',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'onderwijs',
		'plato_code'            => 'EDU',
		'name'                  => __( 'Onderwijs', 'siw' ),
		'icon_class'            => 'siw-icon-book-reader',
		'tailor_made_projects'  => true,
	],
	[
		'slug'                  => 'ouderen',
		'plato_code'            => 'ELDE',
		'name'                  => __( 'Ouderen', 'siw' ),
		'icon_class'            => 'siw-icon-blind',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'festival',
		'plato_code'            => 'FEST',
		'name'                  => __( 'Festival', 'siw' ),
		'icon_class'            => 'siw-icon-music',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'cultuur',
		'plato_code'            => 'CULT',
		'name'                  => __( 'Cultuur', 'siw' ),
		'icon_class'            => 'siw-icon-gopuram',
		'tailor_made_projects'  => true,
	],
	[
		'slug'                  => 'landbouw',
		'plato_code'            => 'AGRI',
		'name'                  => __( 'Landbouw', 'siw' ),
		'icon_class'            => 'siw-icon-leaf',
		'tailor_made_projects'  => true,
	],
	[
		'slug'                  => 'kunst',
		'plato_code'            => 'ART',
		'name'                  => __( 'Kunst', 'siw' ),
		'icon_class'            => 'siw-icon-theater-masks',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'sport',
		'plato_code'            => 'SPOR',
		'name'                  => __( 'Sport', 'siw' ),
		'icon_class'            => 'siw-icon-futbol',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'yoga',
		'plato_code'            => 'YOGA',
		'name'                  => __( 'Yoga', 'siw' ),
		'icon_class'            => 'siw-icon-praying-hands',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'taalcursus',
		'plato_code'            => 'LANG',
		'name'                  => __( 'Taalcursus', 'siw' ),
		'icon_class'            => 'siw-icon-comments',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'taal',
		'plato_code'            => 'TRAS',
		'name'                  => __( 'Taal', 'siw' ),
		'icon_class'            => 'siw-icon-language',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'dieren',
		'plato_code'            => 'ZOO',
		'name'                  => __( 'Dieren', 'siw' ),
		'icon_class'            => 'siw-icon-paw',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'projectbegeleider',
		'plato_code'            => 'LEAD',
		'name'                  => __( 'Projectbegeleider', 'siw' ),
		'icon_class'            => 'siw-icon-campground',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'erfgoed',
		'plato_code'            => 'HERI',
		'name'                  => __( 'Erfgoed', 'siw' ),
		'icon_class'            => 'siw-icon-landmark',
		'tailor_made_projects'  => false,
	],
	[
		'slug'                  => 'vluchtelingen',
		'plato_code'            => 'REFU',
		'name'                  => __( 'Vluchtelingen', 'siw' ),
		'icon_class'            => 'siw-icon-hand-holding-heart',
		'tailor_made_projects'  => false,
	],
];
return $data;
