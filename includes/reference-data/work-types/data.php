<?php
/**
 * Gegevens van soorten werk
 *
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo      evs en groepsprojecten toevoegen
 */

add_filter( 'siw_work_types_data', function( $data ) {

	$data = [
		[
			'slug'                   => 'restauratie',
			'plato'                  => 'RENO',
			'name'                   => __( 'Restauratie', 'siw' ),
			'dutch_projects'         => false,
			'tailor_made_projects'   => false,
		],
		[
			'slug'                  => 'natuur',
			'plato'                 => 'ENVI',
			'name'                  => __( 'Natuur', 'siw' ),
			'dutch_projects'        => true,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'constructie',
			'plato'                 => 'CONS',
			'name'                  => __( 'Constructie', 'siw' ),
			'dutch_projects'        => true,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'archeologie',
			'plato'                 => 'ARCH',
			'name'                  => __( 'Archeologie', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'sociaal',
			'plato'                 => 'SOCI',
			'name'                  => __( 'Sociaal', 'siw' ),
			'dutch_projects'        => true,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'kinderen',
			'plato'                 => 'KIDS',
			'name'                  => __( 'Kinderen', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'thema',
			'plato'                 => 'STUD',
			'name'                  => __( 'Thema', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'gehandicapten',
			'plato'                 => 'DISA',
			'name'                  => __( 'Gehandicapten', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'constructie',
			'plato'                 => 'MANU',
			'name'                  => __( 'Constructie', 'siw' ), //TODO: is handarbeid niet beter?
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'onderwijs',
			'plato'                 => 'EDU',
			'name'                  => __( 'Onderwijs', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'ouderen',
			'plato'                 => 'ELDE',
			'name'                  => __( 'Ouderen', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'festival',
			'plato'                 => 'FEST',
			'name'                  => __( 'Festival', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'cultuur',
			'plato'                 => 'CULT',
			'name'                  => __( 'Cultuur', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'landbouw',
			'plato'                 => 'AGRI',
			'name'                  => __( 'Landbouw', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => true,
		],
		[
			'slug'                  => 'kunst',
			'plato'                 => 'ART',
			'name'                  => __( 'Kunst', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'sport',
			'plato'                 => 'SPOR',
			'name'                  => __( 'Sport', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'yoga',
			'plato'                 => 'YOGA',
			'name'                  => __( 'Yoga', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'taalcursus',
			'plato'                 => 'LANG',
			'name'                  => __( 'Taalcursus', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'taal',
			'plato'                 => 'TRAS',
			'name'                  => __( 'Taal', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'dieren',
			'plato'                 => 'ZOO',
			'name'                  => __( 'Dieren', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'dieren',
			'plato'                 => 'ANIM',
			'name'                  => __( 'Dieren', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'projectbegeleider',
			'plato'                 => 'LEAD',
			'name'                  => __( 'Projectbegeleider', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'erfgoed',
			'plato'                 => 'HERI',
			'name'                  => __( 'Erfgoed', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
		[
			'slug'                  => 'vluchtelingen',
			'plato'                 => 'REFU',
			'name'                  => __( 'Vluchtelingen', 'siw' ),
			'dutch_projects'        => false,
			'tailor_made_projects'  => false,
		],
	];
	return $data;
});