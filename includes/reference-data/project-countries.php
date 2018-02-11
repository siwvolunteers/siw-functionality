<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Geeft een array met landen terug //TODO:hernoemen naar siw_get_countries + deprecated functie
 *
 * Bevat per land de volgende eigenschappen: slug, name, continent, allowed, op maat, evs, mapplic-code
 *
 * @return array
 */
function siw_get_project_countries() {
	$project_countries = array(
		'ALB' => array(
			'slug'			=> 'albanie',
			'name'			=> __( 'Albanië', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'al',
			'travel_advice' => 'albanie',
		),
		'ARG' => array(
			'slug'			=> 'argentinie',
			'name'			=> __( 'Argentinië', 'siw' ),
			'continent'		=> 'latijns-amerika',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'ar',
			'travel_advice' => 'argentinie',
		),
		'ARM' => array(
			'slug'			=> 'armenie',
			'name'			=> __( 'Armenië', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'am',
			'travel_advice' => 'armenie',
		),
		'AUS' => array(
			'slug'			=> 'australie',
			'name'			=> __( 'Australië', 'siw' ),
			'continent'		=> 'oceanie',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'travel_advice'	=> 'australie',
		),
		'AUT' => array(
			'slug'			=> 'oostenrijk',
			'name'			=> __( 'Oostenrijk', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'at',
			'travel_advice'	=> 'oostenrijk',
		),
		'BDI' => array(
			'slug'			=> 'burundi',
			'name'			=> __( 'Burundi', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'travel_advice'	=> 'burundi',
		),
		'BEL' => array(
			'slug'			=> 'belgie',
			'name'			=> __( 'België', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'be',
			'travel_advice'	=> 'belgie',
		),
		'BGR' => array(
			'slug'			=> 'bulgarije',
			'name'			=> __( 'Bulgarije', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'bg',
			'travel_advice'	=> 'bulgarije',
		),
		'BLR' => array(
			'slug'			=> 'wit-rusland',
			'name'			=> __( 'Wit-Rusland', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'travel_advice' => 'belarus-wit-rusland',
		),
		'BOL' => array(
			'slug'			=> 'bolivia',
			'name'			=> __( 'Bolivia', 'siw' ),
			'continent'		=> 'latijns-amerika',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'bo',
			'travel_advice'	=> 'bolivia',
		),
		'BRA' => array(
			'slug'			=> 'brazilie',
			'name'			=> __( 'Brazilië', 'siw' ),
			'continent'		=> 'latijns-amerika',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'br',
			'travel_advice'	=> 'brazilie',
		),
		'BWA' => array(
			'slug'			=> 'botswana',
			'name'			=> __( 'Botswana', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'bw',
			'travel_advice' => 'botswana',
		),
		'CAN' => array(
			'slug'			=> 'canada',
			'name'			=> __( 'Canada', 'siw' ),
			'continent'		=> 'noord-amerika',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'ca',
			'travel_advice' => 'canada',
		),
		'CHE' => array(
			'slug'		=> 'zwitserland',
			'name'		=> __( 'Zwitserland', 'siw' ),
			'continent'	=> 'europa',
			'allowed'	=> 'yes',
			'op_maat'	=> false,
			'mapplic'	=> 'ch',
		),
		'CHN' => array(
			'slug'			=> 'china',
			'name'			=> __( 'China', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'mapplic'		=> 'cn',
			'travel_advice' => 'china',
		),
		'CRI' => array(
			'slug'			=> 'costa-rica',
			'name'			=> __( 'Costa Rica', 'siw' ),
			'continent'		=> 'latijns-amerika',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'cr',
			'travel_advice' => 'costa-rica',
		),
		'CYP' => array(
			'slug'			=> 'cyprus',
			'name'			=> __( 'Cyprus', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'cy',
			'travel_advice' => 'cyprus',
		),
		'CZE' => array(
			'slug'			=> 'tsjechie',
			'name'			=> __( 'Tsjechië', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'cz',
			'travel_advice' => 'tsjechie',
		),
		'DEU' => array(
			'slug'			=> 'duitsland',
			'name'			=> __( 'Duitsland', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'de',
			'travel_advice' => 'duitsland',
		),
		'DNK' => array(
			'slug'			=> 'denemarken',
			'name'			=> __( 'Denemarken', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'dk',
			'travel_advice' => 'denemarken',
		),
		'ECU' => array(
			'slug'			=> 'ecuador',
			'name'			=> __( 'Ecuador', 'siw' ),
			'continent'		=> 'latijns-amerika',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'ec',
			'travel_advice' => 'ecuador',
		),
		'ESP' => array(
			'slug'			=> 'spanje',
			'name'			=> __( 'Spanje', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'es',
			'travel_advice' => 'spanje',
		),
		'EST' => array(
			'slug'			=> 'estland',
			'name'			=> __( 'Estland', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'ee',
			'travel_advice' => 'estland',
		),
		'FIN' => array(
			'slug'			=> 'finland',
			'name'			=> __( 'Finland', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'fi',
			'travel_advice' => 'finland',
		),
		'FRA' => array(
			'slug'			=> 'frankrijk',
			'name'			=> __( 'Frankrijk', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'fr',
			'travel_advice' => 'frankrijk',
		),
		'GBR' => array(
			'slug'			=> 'verenigd-koninkrijk',
			'name'			=> __( 'Verenigd Koninkrijk', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'gb',
			'travel_advice' => 'verenigd-koninkrijk',
		),
		'GEO' => array(
			'slug'			=> 'georgie',
			'name'			=> __( 'Georgië', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic' 		=> 'ge',
			'travel_advice'	=> 'georgie',
		),
		'GHA' => array(
			'slug'			=> 'ghana',
			'name'			=> __( 'Ghana', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'gh',
			'travel_advice'	=> 'ghana',
		),
		'GRC' => array(
			'slug'			=> 'griekenland',
			'name'			=> __( 'Griekenland', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'gr',
			'travel_advice'	=> 'griekenland',
		),
		'GRL' => array(
			'slug'			=> 'groenland',
			'name'			=> __( 'Groenland', 'siw' ),
			'continent'		=> 'noord-amerika',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'mapplic'		=> 'gl',
			'travel_advice' => 'groenland',
		),
		'HKG' => array(
			'slug'			=> 'hong-kong',
			'name'			=> __( 'Hong Kong', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			//'mapplic'		=> '?',
			'travel_advice' => 'hongkong-sar',
		),
		'HUN' => array(
			'slug'			=> 'hongarije',
			'name'			=> __( 'Hongarije', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'hu',
			'travel_advice' => 'hongarije',
		),
		'HRV' => array(
			'slug'			=> 'kroatie',
			'name'			=> __( 'Kroatië', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'hr',
			'travel_advice' => 'kroatie',
		),
		'HTE' => array(
			'slug'			=> 'haiti',
			'name'			=> __( 'Haïti', 'siw' ),
			'continent'		=> 'latijns-amerika',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'mapplic'		=> 'ht',
			'travel_advice' => 'haiti',
		),
		'IDN' => array(
			'slug'			=> 'indonesie',
			'name'			=> __( 'Indonesië', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'id',
			'travel_advice' => 'indonesie',
		),
		'IND' => array(
			'slug'			=> 'india',
			'name'			=> __( 'India', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'in',
			'travel_advice' => 'india',
		),
		'IRL' => array(
			'slug'			=> 'ierland',
			'name'			=> __( 'Ierland', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'ie',
			'travel_advice' => 'ierland',
		),
		'ISL' => array(
			'slug'			=> 'ijsland',
			'name'			=> __( 'IJsland', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'is',
			'travel_advice' => 'ijsland',
		),
		'ITA' => array(
			'slug'			=> 'italie',
			'name'			=> __( 'Italië', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'it',
			'travel_advice' => 'italie',
		),
		'JPN' => array(
			'slug'			=> 'japan',
			'name'			=> __( 'Japan', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'jp',
			'travel_advice' => 'japan',
		),
		'KEN' => array(
			'slug'			=> 'kenia',
			'name'			=> __( 'Kenia', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'ke',
			'travel_advice' => 'kenia',
		),
		'KGZ' => array(
			'slug'			=> 'kirgizie',
			'name'			=> __( 'Kirgizië', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'mapplic'		=> 'kg',
			'travel_advice' => 'kirgizie',
		),
		'KHM' => array(
			'slug'			=> 'cambodja',
			'name'			=> __( 'Cambodja', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'kh',
			'travel_advice' => 'cambodja',
		),
		'KOR' => array(
			'slug'			=> 'zuid-korea',
			'name'			=> __( 'Zuid-Korea', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'kr',
			'travel_advice' => 'zuid-korea',
		),
		'LKA' => array(
			'slug'			=> 'sri-lanka',
			'name'			=> __( 'Sri Lanka', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'mapplic'		=> 'lk',
			'travel_advice' => 'sri-lanka',
		),
		'LTU' => array(
			'slug'			=> 'litouwen',
			'name'			=> __( 'Litouwen', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'lt',
			'travel_advice' => 'litouwen',
		),
		'LVA' => array(
			'slug'			=> 'letland',
			'name'			=> __( 'Letland', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'lv',
			'travel_advice' => 'letland',
		),
		'MAR' => array(
			'slug'			=> 'marokko',
			'name'			=> __( 'Marokko', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'ma',
			'travel_advice' => 'marokko',
		),
		'MEX' => array(
			'slug'			=> 'mexico',
			'name'			=> __( 'Mexico', 'siw' ),
			'continent'		=> 'latijns-amerika',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'mx',
			'travel_advice' => 'mexico',
		),
		'MLT' => array(
			'slug'			=> 'malta',
			'name'			=> __( 'Malta', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'mt',
			'travel_advice' => 'malta',
		),
		'MNE' => array(
			'slug'			=> 'montenegro',
			'name'			=> __( 'Montenegro', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'me',
			'travel_advice' => 'montenegro',
		),
		'MNG' => array(
			'slug'			=> 'mongolie',
			'name'			=> __( 'Mongolië', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'mn',
			'travel_advice' => 'mongolie',
		),
		'NLD' => array(
			'slug'		=> 'nederland',
			'name'		=> __( 'Nederland', 'siw' ),
			'continent'	=> 'europa',
			'allowed'	=> 'yes',
			'op_maat'	=> false,
			'mapplic'	=> 'nl',
		),
		'NOR' => array(
			'slug'			=> 'noorwegen',
			'name'			=> __( 'Noorwegen', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'no',
			'travel_advice' => 'noorwegen',
		),
		'NPL' => array(
			'slug'			=> 'nepal',
			'name'			=> __( 'Nepal', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'np',
			'travel_advice' => 'nepal',
		),
		'PER' => array(
			'slug'			=> 'peru',
			'name'			=> __( 'Peru', 'siw' ),
			'continent'		=> 'latijns-amerika',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'pe',
			'travel_advice' => 'peru',
		),
		'POL' => array(
			'slug'			=> 'polen',
			'name'			=> __( 'Polen', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'pl',
			'travel_advice' => 'polen',
		),
		'PRT' => array(
			'slug'			=> 'portugal',
			'name'			=> __( 'Portugal', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'pt',
			'travel_advice' => 'portugal',
		),
		'ROU' => array(
			'slug'			=> 'roemenie',
			'name'			=> __( 'Roemenië', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'ro',
			'travel_advice' => 'roemenie',
		),
		'RUS' => array(
			'slug'			=> 'rusland',
			'name'			=> __( 'Rusland', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'ru',
			'travel_advice' => 'rusland',
		),
		'SEN' => array(
			'slug'			=> 'senegal',
			'name'			=> __( 'Senegal', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'mapplic'		=> 'sn',
			'travel_advice' => 'senegal',
		),
		'SRB' => array(
			'slug'			=> 'servie',
			'name'			=> __( 'Servië', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'rs',
			'travel_advice' => 'servie',
		),
		'SVK' => array(
			'slug'			=> 'slowakije',
			'name'			=> __( 'Slowakije', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'sk',
			'travel_advice' => 'slowakije',
		),
		'SVN' => array(
			'slug'			=> 'slovenie',
			'name'			=> __( 'Slovenië', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'si',
			'travel_advice' => 'slovenie',
		),
		'SWE' => array(
			'slug'			=> 'zweden',
			'name'			=> __( 'Zweden', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'se',
			'travel_advice' => 'zweden',
		),
		'TGO' => array(
			'slug'			=> 'togo',
			'name'			=> __( 'Togo', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'tg',
			'travel_advice' => 'togo',
		),
		'THA' => array(
			'slug'			=> 'thailand',
			'name'			=> __( 'Thailand', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'th',
			'travel_advice' => 'thailand',
		),
		'TUN' => array(
			'slug'			=> 'tunesie',
			'name'			=> __( 'Tunesië', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'mapplic'		=> 'tn',
			'travel_advice' => 'tunesie',
		),
		'TUR' => array(
			'slug'			=> 'turkije',
			'name'			=> __( 'Turkije', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'evs'			=> true,
			'mapplic'		=> 'tr',
			'travel_advice' => 'turkije',
		),
		'TWN' => array(
			'slug'			=> 'taiwan',
			'name'			=> __( 'Taiwan', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'mapplic'		=> 'tw',
			'travel_advice' => 'taiwan',
		),
		'TZA' => array(
			'slug'			=> 'tanzania',
			'name'			=> __( 'Tanzania', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'tz',
			'travel_advice' => 'tanzania',
		),
		'UGA' => array(
			'slug'			=> 'uganda',
			'name'			=> __( 'Uganda', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'ug',
			'travel_advice' => 'uganda',
		),
		'UKR' => array(
			'slug'			=> 'oekraine',
			'name'			=> __( 'Oekraïne', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			'mapplic'		=> 'ua',
			'travel_advice' => 'oekraine',
		),
		'USA' => array(
			'slug'			=> 'verenigde-staten',
			'name'			=> __( 'Verenigde Staten', 'siw' ),
			'continent'		=> 'noord-amerika',
			'allowed'		=> 'no',
			'op_maat'		=> false,
			'mapplic'		=> 'us',
			'travel_advice' => 'verenigde-staten-van-amerika',
		),
		'VNM' => array(
			'slug'			=> 'vietnam',
			'name'			=> __( 'Vietnam', 'siw' ),
			'continent'		=> 'azie',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'vn',
			'travel_advice' => 'vietnam',
		),
		'XKS' => array(
			'slug'			=> 'kosovo',
			'name'			=> __( 'Kosovo', 'siw' ),
			'continent'		=> 'europa',
			'allowed'		=> 'yes',
			'op_maat'		=> false,
			//'mapplic'		=> '??',
			'travel_advice' => 'kosovo',
		),
		'ZAF' => array(
			'slug'			=> 'zuid-afrika',
			'name'			=> __( 'Zuid-Afrika', 'siw' ),
			'continent'		=> 'afrika-midden-oosten',
			'allowed'		=> 'yes',
			'op_maat'		=> true,
			'mapplic'		=> 'za',
			'travel_advice' => 'zuid-afrika',
		),
	);

	/* Sorteren op naam */
	$country_name = array();
	foreach ( $project_countries as $key => $row ) {
		$country_name[ $key ] = $row['name'];
	}
	array_multisort( $country_name, SORT_ASC, $project_countries );

	return $project_countries;
}


/**
 * Haal eigenschappen van een land op
 * @param  string $country
 * @return mixed
 */
function siw_get_project_country( $country ) {
	$project_countries = siw_get_project_countries();
	$project_country = isset( $project_countries[ $country ] ) ? $project_countries[ $country ] : false;
	return $project_country;
}


/**
 * Geeft een array met continenten terug
 *
 * @return array
 */
function siw_get_continents() {
	$continents = array(
		'europa'				=> __( 'Europa', 'siw' ),
		'azie'					=> __( 'Azië', 'siw' ),
		'afrika-midden-oosten'	=> __( 'Afrika', 'siw' ),
		'latijns-amerika'		=> __( 'Latijns-Amerika', 'siw' ),
		'noord-amerika'			=> __( 'Noord-Amerika', 'siw' ),
	);

	return $continents;
}


/**
 * [siw_get_countries_by_property description]
 * @param  [type] $property [description]
 * @param  [type] $value    [description]
 * @return [type]           [description]
 */
function siw_get_countries_by_property( $property, $value ) {
	$countries = siw_get_project_countries();
	$countries_by_property = array();
	foreach ( $countries as $key => $country ) {
		if ( isset( $country[ $property ] ) && $value == $country[ $property ] ) {
			$countries_by_property[ $key ] = $country;
		}
	}

	return $countries_by_property;
}


/**
 * [siw_get_country_by_property description]
 * @param  [type] $property [description]
 * @param  [type] $value    [description]
 * @return [type]           [description]
 */
function siw_get_country_by_property( $property, $value ) {
	$countries_by_property = siw_get_countries_by_property( $property, $value );
	$country_by_property = array();
	if ( ! empty( $countries_by_property ) ) {
		reset( $countries_by_property );
		$country_by_property = current( $countries_by_property );
	}

	return $country_by_property;
}
