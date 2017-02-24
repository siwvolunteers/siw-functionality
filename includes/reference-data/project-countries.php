<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function siw_get_project_countries(){
	$project_countries = array();
	$project_countries['ALB'] = array(
		'slug'		=> 'albanie',
		'name'		=> __('Albanië', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	); 
	$project_countries['ARG'] = array(
		'slug'		=> 'argentinie',
		'name'		=> __('Argentinië', 'siw'),
		'continent'	=> 'latijns-amerika',
		'allowed'	=> 'no',
	); 
	$project_countries['ARM'] = array(
		'slug'		=> 'armenie',
		'name'		=> __('Armenië', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	); 			
	$project_countries['AUT'] = array(
		'slug'		=> 'oostenrijk',
		'name'		=> __('Oostenrijk', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	); 				
	$project_countries['BDI'] = array(
		'slug'		=> 'burundi',
		'name'		=> __('Burundi', 'siw'),
		'continent'	=> 'afrika-midden-oosten',
		'allowed'	=> 'no',
	); 					
	$project_countries['BEL'] = array(
		'slug'		=> 'belgie',
		'name'		=> __('België', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	); 	
	$project_countries['BLR'] = array(
		'slug'		=> 'wit-rusland',
		'name'		=> __('Wit-Rusland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	); 	
	$project_countries['CAN'] = array(
		'slug'		=> 'canada',
		'name'		=> __('Canada', 'siw'),
		'continent'	=> 'noord-amerika',
		'allowed'	=> 'yes',
	); 	
	$project_countries['CHE'] = array(
		'slug'		=> 'zwitserland',
		'name'		=> __('Zwitserland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	); 	
	$project_countries['CHN'] = array(
		'slug'		=> 'china',
		'name'		=> __('China', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'no',
	); 
	$project_countries['CRI'] = array(
		'slug'		=> 'costa-rica',
		'name'		=> __('Costa Rica', 'siw'),
		'continent'	=> 'latijns-amerika',
		'allowed'	=> 'no',
	); 				
	$project_countries['CZE'] = array(
		'slug'		=> 'tsjechie',
		'name'		=> __('Tsjechië', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	); 	
	$project_countries['DEU'] = array(
		'slug'		=> 'duitsland',
		'name'		=> __('Duitsland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	); 
	$project_countries['DNK'] = array(
		'slug'		=> 'denemarken',
		'name'		=> __('Denemarken', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['ECU'] = array(
		'slug'		=> 'ecuador',
		'name'		=> __('Ecuador', 'siw'),
		'continent'	=> 'latijns-amerika',
		'allowed'	=> 'yes',
	);
	$project_countries['ESP'] = array(
		'slug'		=> 'spanje',
		'name'		=> __('Spanje', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['EST'] = array(
		'slug'		=> 'estland',
		'name'		=> __('Estland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['FIN'] = array(
		'slug'		=> 'finland',
		'name'		=> __('Finland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['FRA'] = array(
		'slug'		=> 'frankrijk',
		'name'		=> __('Frankrijk', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['GBR'] = array(
		'slug'		=> 'verenigd-koninkrijk',
		'name'		=> __('Verenigd Koninkrijk', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['GEO'] = array(
		'slug'		=> 'georgie',
		'name'		=> __('Georgië', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['GRC'] = array(
		'slug'		=> 'griekenland',
		'name'		=> __('Griekenland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['HKG'] = array(
		'slug'		=> 'hong-kong',
		'name'		=> __('Hong Kong', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'no',
	);
	$project_countries['HUN'] = array(
		'slug'		=> 'hongarije',
		'name'		=> __('Hongarije', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['HRV'] = array(
		'slug'		=> 'kroatie',
		'name'		=> __('Kroatië', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);				
	$project_countries['HTE'] = array(
		'slug'		=> 'haiti',
		'name'		=> __('Haïti', 'siw'),
		'continent'	=> 'latijns-amerika',
		'allowed'	=> 'no',
	);
	$project_countries['IDN'] = array(
		'slug'		=> 'indonesie',
		'name'		=> __('Indonesië', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'yes',
	);
	$project_countries['IND'] = array(
		'slug'		=> 'india',
		'name'		=> __('India', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'yes',
	);
	$project_countries['IRL'] = array(
		'slug'		=> 'ierland',
		'name'		=> __('Ierland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['ISL'] = array(
		'slug'		=> 'ijsland',
		'name'		=> __('IJsland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['ITA'] = array(
		'slug'		=> 'italie',
		'name'		=> __('Italië', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['JPN'] = array(
		'slug'		=> 'japan',
		'name'		=> __('Japan', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'no',
	);
	$project_countries['KEN'] = array(
		'slug'		=> 'kenia',
		'name'		=> __('Kenia', 'siw'),
		'continent'	=> 'afrika-midden-oosten',
		'allowed'	=> 'yes',
	);
	$project_countries['KGZ'] = array(
		'slug'		=> 'kirgizie',
		'name'		=> __('Kirgizië', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'no',
	);
	$project_countries['KHM'] = array(
		'slug'		=> 'cambodja',
		'name'		=> __('Cambodja', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'no',
	);
	$project_countries['KOR'] = array(
		'slug'		=> 'zuid-korea',
		'name'		=> __('Zuid-Korea', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'no',
	);
	$project_countries['LKA'] = array(
		'slug'		=> 'sri-lanka',
		'name'		=> __('Sri Lanka', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'no',
	);
	$project_countries['LTU'] = array(
		'slug'		=> 'litouwen',
		'name'		=> __('Litouwen', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['LVA'] = array(
		'slug'		=> 'letland',
		'name'		=> __('Letland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['MAR'] = array(
		'slug'		=> 'marokko',
		'name'		=> __('Marokko', 'siw'),
		'continent'	=> 'afrika-midden-oosten',
		'allowed'	=> 'no',
	);
	$project_countries['MEX'] = array(
		'slug'		=> 'mexico',
		'name'		=> __('Mexico', 'siw'),
		'continent'	=> 'latijns-amerika',
		'allowed'	=> 'yes',
	);
	$project_countries['MNE'] = array(
		'slug'		=> 'montenegro',
		'name'		=> __('Montenegro', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['MNG'] = array(
		'slug'		=> 'mongolie',
		'name'		=> __('Mongolië', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'yes',
	);
	$project_countries['NLD'] = array(
		'slug'		=> 'nederland',
		'name'		=> __('Nederland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'no',
	);
	$project_countries['NPL'] = array(
		'slug'		=> 'nepal',
		'name'		=> __('Nepal', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'no',
	);
	$project_countries['PER'] = array(
		'slug'		=> 'peru',
		'name'		=> __('Peru', 'siw'),
		'continent'	=> 'latijns-amerika',
		'allowed'	=> 'yes',
	);
	$project_countries['POL'] = array(
		'slug'		=> 'polen',
		'name'		=> __('Polen', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['PRT'] = array(
		'slug'		=> 'portugal',
		'name'		=> __('Portugal', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['ROU'] = array(
		'slug'		=> 'roemenie',
		'name'		=> __('Roemenië', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);				
	$project_countries['RUS'] = array(
		'slug'		=> 'rusland',
		'name'		=> __('Rusland', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['SRB'] = array(
		'slug'		=> 'servie',
		'name'		=> __('Servië', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['SVK'] = array(
		'slug'		=> 'slowakije',
		'name'		=> __('Slowakije', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['SVN'] = array(
		'slug'		=> 'slovenie',
		'name'		=> __('Slovenië', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['TGO'] = array(
		'slug'		=> 'togo',
		'name'		=> __('Togo', 'siw'),
		'continent'	=> 'afrika-midden-oosten',
		'allowed'	=> 'no',
	);
	$project_countries['THA'] = array(
		'slug'		=> 'thailand',
		'name'		=> __('Thailand', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'yes',
	);
	$project_countries['TUN'] = array(
		'slug'		=> 'tunesie',
		'name'		=> __('Tunesië', 'siw'),
		'continent'	=> 'afrika-midden-oosten',
		'allowed'	=> 'no',
	);
	$project_countries['TUR'] = array(
		'slug'		=> 'turkije',
		'name'		=> __('Turkije', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['TWN'] = array(
		'slug'		=> 'taiwan',
		'name'		=> __('Taiwan', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'no',
	);
	$project_countries['TZA'] = array(
		'slug'		=> 'tanzania',
		'name'		=> __('Tanzania', 'siw'),
		'continent'	=> 'afrika-midden-oosten',
		'allowed'	=> 'yes',
	);						
	$project_countries['UGA'] = array(
		'slug'		=> 'uganda',
		'name'		=> __('Uganda', 'siw'),
		'continent'	=> 'afrika-midden-oosten',
		'allowed'	=> 'yes',
	);
	$project_countries['UKR'] = array(
		'slug'		=> 'oekraine',
		'name'		=> __('Oekraïne', 'siw'),
		'continent'	=> 'europa',
		'allowed'	=> 'yes',
	);
	$project_countries['USA'] = array(
		'slug'		=> 'verenigde-staten',
		'name'		=> __('Verenigde Staten', 'siw'),
		'continent'	=> 'noord-amerika',
		'allowed'	=> 'yes',
	);
	$project_countries['VNM'] = array(
		'slug'		=> 'vietnam',
		'name'		=> __('Vietnam', 'siw'),
		'continent'	=> 'azie',
		'allowed'	=> 'yes',
	);
	return $project_countries;	
}