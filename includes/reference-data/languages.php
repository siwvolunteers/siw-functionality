<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function siw_get_volunteer_languages(){
	$volunteer_languages = array(
		''		=> __('Selecteer een taal', 'siw'),
		'ARA'	=> __('Arabisch', 'siw'),
		'CAT'	=> __('Catalaans', 'siw'),
		'CHN'	=> __('Chinees', 'siw'),
		'DNK'	=> __('Deens', 'siw'),
		'GER'	=> __('Duits', 'siw'),
		'ENG'	=> __('Engels', 'siw'),
		'EST'	=> __('Estisch', 'siw'),
		'FIN'	=> __('Fins', 'siw'),
		'FRA'	=> __('Frans', 'siw'),
		'GRE'	=> __('Grieks', 'siw'),
		'HEB'	=> __('Hebreeuws', 'siw'),
		'ITA'	=> __('Italiaans', 'siw'),
		'JAP'	=> __('Japans', 'siw'),
		'KOR'	=> __('Koreaans', 'siw'),
		'HOL'	=> __('Nederlands', 'siw'),
		'UKR'	=> __('Oekraïens', 'siw'),
		'POL'	=> __('Pools', 'siw'),
		'POR'	=> __('Portugees', 'siw'),
		'RUS'	=> __('Russisch', 'siw'),
		'SLK'	=> __('Slowaaks', 'siw'),
		'ESP'	=> __('Spaans', 'siw'),
		'CZE'	=> __('Tsjechisch', 'siw'),
		'TUR'	=> __('Turks', 'siw'),
		'SWE'	=> __('Zweeds', 'siw'),
	);
	return $volunteer_languages;	
}	
function siw_get_volunteer_language_skill_levels(){
	$language_skill_levels = array(
		'1'	=> __('Matig', 'siw'),
		'2'	=> __('Redelijk', 'siw'),
		'3'	=> __('Goed', 'siw'),
		'4'	=> __('Uitstekend', 'siw'),
	);
	return $language_skill_levels;
}

function siw_get_project_languages(){
	$project_languages=array(
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