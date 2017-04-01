<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Geeft een array met projecttalen terug
 *
 * @return array
 */
function siw_get_project_languages() {
	$project_languages = array(
		'ARA'	=> 'arabisch',
		'AZE'	=> 'azerbeidzjaans ',
		'CAT'	=> 'catalaans',
		'CHN'	=> 'chinees',
		'HKG'	=> 'chinees',
		'DNK'	=> 'deens',
		'GER'	=> 'duits',
		'ENG'	=> 'engels',
		'EN' 	=> 'engels',
		'USA'	=> 'engels',
		'EST'	=> 'estisch',
		'FIN'	=> 'fins',
		'FRA'	=> 'frans',
		'GRE'	=> 'grieks',
		'HEB'	=> 'hebreeuws',
		'ITA'	=> 'italiaans',
		'JAP'	=> 'japans',
		'JPN'	=> 'japans',
		'KOR'	=> 'koreaans',
		'HOL'	=> 'nederlands',
		'UKR'	=> 'oekraiens',
		'IRN'	=> 'perzisch',
		'POL'	=> 'pools',
		'POR'	=> 'portugees',
		'RUS'	=> 'russisch',
		'SLK'	=> 'slowaaks',
		'ES'	=> 'spaans',
		'ESP'	=> 'spaans',
		'CZE'	=> 'tsjechisch',
		'TUR'	=> 'turks',
		'BEL'	=> 'waals',
		'BLR'	=> 'wit-russisch',
		'SWE'	=> 'zweeds'
	);
	return $project_languages;
}
