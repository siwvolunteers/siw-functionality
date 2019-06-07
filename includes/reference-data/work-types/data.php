<?php
/**
 * Gegevens van soorten werk
 *
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo      ESC en groepsprojecten toevoegen
 */

add_filter( 'siw_work_types_data', function( $data ) {

	$data = [
		[
			'slug'                   => 'restauratie',
			'plato'                  => 'RENO',
			'name'                   => __( 'Restauratie', 'siw' ),
			'icon_class'             => 'siw-icon-house-damage',
			'dutch_projects'         => false,
			'tailor_made_projects'   => false,
		],
		[
			'slug'                  => 'natuur',
			'plato'                 => 'ENVI',
			'name'                  => __( 'Natuur', 'siw' ),
			'icon_class'            => 'siw-icon-tree',
			'dutch_projects'        => true,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'constructie',
			'plato'                 => 'CONS',
			'name'                  => __( 'Constructie', 'siw' ),
			'icon_class'            => 'siw-icon-tools',
			'dutch_projects'        => true,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'archeologie',
			'plato'                 => 'ARCH',
			'name'                  => __( 'Archeologie', 'siw' ),
			'icon_class'            => 'siw-icon-archway',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'sociaal',
			'plato'                 => 'SOCI',
			'name'                  => __( 'Sociaal', 'siw' ),
			'icon_class'            => 'siw-icon-hands-helping',
			'dutch_projects'        => true,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'kinderen',
			'plato'                 => 'KIDS',
			'name'                  => __( 'Kinderen', 'siw' ),
			'icon_class'            => 'siw-icon-child',
			'dutch_projects'        => false,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'thema',
			'plato'                 => 'STUD',
			'name'                  => __( 'Thema', 'siw' ),
			'icon_class'            => 'siw-icon-camera',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'gehandicapten',
			'plato'                 => 'DISA',
			'name'                  => __( 'Gehandicapten', 'siw' ),
			'icon_class'            => 'siw-icon-wheelchair',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'handarbeid',
			'plato'                 => 'MANU',
			'name'                  => __( 'Handarbeid', 'siw' ),
			'icon_class'            => 'siw-icon-paint-roller',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'onderwijs',
			'plato'                 => 'EDU',
			'name'                  => __( 'Onderwijs', 'siw' ),
			'icon_class'            => 'siw-icon-book-reader',
			'dutch_projects'        => false,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'ouderen',
			'plato'                 => 'ELDE',
			'name'                  => __( 'Ouderen', 'siw' ),
			'icon_class'            => 'siw-icon-blind',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'festival',
			'plato'                 => 'FEST',
			'name'                  => __( 'Festival', 'siw' ),
			'icon_class'            => 'siw-icon-music',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'cultuur',
			'plato'                 => 'CULT',
			'name'                  => __( 'Cultuur', 'siw' ),
			'icon_class'            => 'siw-icon-gopuram',
			'dutch_projects'        => false,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'landbouw',
			'plato'                 => 'AGRI',
			'name'                  => __( 'Landbouw', 'siw' ),
			'icon_class'            => 'siw-icon-leaf',
			'dutch_projects'        => true,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'kunst',
			'plato'                 => 'ART',
			'name'                  => __( 'Kunst', 'siw' ),
			'icon_class'            => 'siw-icon-theater-masks',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'sport',
			'plato'                 => 'SPOR',
			'name'                  => __( 'Sport', 'siw' ),
			'icon_class'            => 'siw-icon-futbol',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'yoga',
			'plato'                 => 'YOGA',
			'name'                  => __( 'Yoga', 'siw' ),
			'icon_class'            => 'siw-icon-praying-hands',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'taalcursus',
			'plato'                 => 'LANG',
			'name'                  => __( 'Taalcursus', 'siw' ),
			'icon_class'            => 'siw-icon-comments',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'taal',
			'plato'                 => 'TRAS',
			'name'                  => __( 'Taal', 'siw' ),
			'icon_class'            => 'siw-icon-language',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'dieren',
			'plato'                 => 'ZOO',
			'name'                  => __( 'Dieren', 'siw' ),
			'icon_class'            => 'siw-icon-paw',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'projectbegeleider',
			'plato'                 => 'LEAD',
			'name'                  => __( 'Projectbegeleider', 'siw' ),
			'icon_class'            => 'siw-icon-campground',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'erfgoed',
			'plato'                 => 'HERI',
			'name'                  => __( 'Erfgoed', 'siw' ),
			'icon_class'            => 'siw-icon-landmark',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'vluchtelingen',
			'plato'                 => 'REFU',
			'name'                  => __( 'Vluchtelingen', 'siw' ),
			'icon_class'            => 'siw-icon-hand-holding-heart',
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
	];
	return $data;
});
