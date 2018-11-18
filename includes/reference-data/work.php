<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Geeft een array met soort werk voor projecten terug
 * @deprecated
 * @return array
 */
function siw_get_project_work_types() {
	$project_work_types = array(
		'RENO'	=> 'restauratie',
		'ENVI'	=> 'natuur',
		'CONS'	=> 'constructie',
		'ARCH'	=> 'archeologie',
		'SOCI'	=> 'sociaal',
		'KIDS'	=> 'kinderen',
		'STUD'	=> 'thema',
		'DISA'	=> 'gehandicapten',
		'MANU'	=> 'constructie',
		'EDU'	=> 'onderwijs',
		'ELDE'	=> 'ouderen',
		'FEST'	=> 'festival',
		'CULT'	=> 'cultuur',
		'AGRI'	=> 'landbouw',
		'ART'	=> 'kunst',
		'SPOR'	=> 'sport',
		'YOGA'	=> 'yoga',
		'LANG'	=> 'taalcursus',
		'TRAS'	=> 'taal',
		'ZOO'	=> 'dieren',
		'ANIM'	=> 'dieren',
		'LEAD'	=> 'projectbegeleider',
		'HERI'	=> 'erfgoed',
		'REFU'	=> 'vluchtelingen',
	);
	return $project_work_types;
}


/**
 * Geeft array soorten werk voor EVS-projecten terug
 * @deprecated
 * @return array
 */
function siw_get_evs_project_work_types() {
	$evs_work_types = array(
		'democracy' => __( 'Bewustwording, democracy, beleid', 'siw' ),
		'culture'	=> __( 'Cultuur, creativiteit', 'siw' ),
		'inclusion' => __( 'Inclusion, zorg, hulpverlening Kinderen, Roma', 'siw' ),
		'equality'	=> __( 'Mensenrechten, equality, gender', 'siw' ),
		'nature'	=> __( 'Natuurbescherming, milieu, klimaat', 'siw' ),
	);
	return $evs_work_types;
}