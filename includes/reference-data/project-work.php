<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function siw_get_project_work_types(){
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
		'HERI'	=> 'erfgoed'
	);
	return $siw_get_project_work_types;
}