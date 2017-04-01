<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Geeft een array met talen terug
 *
 * @return array
 */
function siw_get_volunteer_languages() {
	$volunteer_languages = array(
		''		=> __( 'Selecteer een taal', 'siw' ),
		'ARA'	=> __( 'Arabisch', 'siw' ),
		'CAT'	=> __( 'Catalaans', 'siw' ),
		'CHN'	=> __( 'Chinees', 'siw' ),
		'DNK'	=> __( 'Deens', 'siw' ),
		'GER'	=> __( 'Duits', 'siw' ),
		'ENG'	=> __( 'Engels', 'siw' ),
		'EST'	=> __( 'Estisch', 'siw' ),
		'FIN'	=> __( 'Fins', 'siw' ),
		'FRA'	=> __( 'Frans', 'siw' ),
		'GRE'	=> __( 'Grieks', 'siw' ),
		'HEB'	=> __( 'Hebreeuws', 'siw' ),
		'ITA'	=> __( 'Italiaans', 'siw' ),
		'JAP'	=> __( 'Japans', 'siw' ),
		'KOR'	=> __( 'Koreaans', 'siw' ),
		'HOL'	=> __( 'Nederlands', 'siw' ),
		'UKR'	=> __( 'Oekraïens', 'siw' ),
		'POL'	=> __( 'Pools', 'siw' ),
		'POR'	=> __( 'Portugees', 'siw' ),
		'RUS'	=> __( 'Russisch', 'siw' ),
		'SLK'	=> __( 'Slowaaks', 'siw' ),
		'ESP'	=> __( 'Spaans', 'siw' ),
		'CZE'	=> __( 'Tsjechisch', 'siw' ),
		'TUR'	=> __( 'Turks', 'siw' ),
		'SWE'	=> __( 'Zweeds', 'siw' ),
	);
	return $volunteer_languages;
}

/**
 * Geeft een array met niveau's van taalvaardigheid terug
 *
 * @return array
 */
function siw_get_volunteer_language_skill_levels() {
	$language_skill_levels = array(
		'1'	=> __( 'Matig', 'siw' ),
		'2'	=> __( 'Redelijk', 'siw' ),
		'3'	=> __( 'Goed', 'siw' ),
		'4'	=> __( 'Uitstekend', 'siw' ),
	);
	return $language_skill_levels;
}


/**
 * Geeft een array met geslachten terug
 *
 * @return array
 */
function siw_get_volunteer_genders() {
	$genders = array(
		'M' => __( 'Man', 'siw' ),
		'F' => __( 'Vrouw', 'siw' ),
	);
	return $genders;
}


/**
 * Geeft een array met nationaliteiten terug
 *
 * @return array
 */
function siw_get_volunteer_nationalities() {
	//TODO: Dit zijn landen geen nationaliteiten
	$nationalities = array(
		''		=> __( 'Selecteer een nationaliteit', 'siw' ),
		'AFG'	=> __( 'Afghanistan', 'siw' ),
		'ALB'	=> __( 'Albanië', 'siw' ),
		'ALG'	=> __( 'Algerije', 'siw' ),
		'AGO'	=> __( 'Angola', 'siw' ),
		'ARG'	=> __( 'Argentinië', 'siw' ),
		'ARM'	=> __( 'Armenië', 'siw' ),
		'AUS'	=> __( 'Australië', 'siw' ),
		'AT'	=> __( 'Oostenrijk', 'siw' ),
		'AZB'	=> __( 'Azerbeidzjan', 'siw' ),
		'BHS'	=> __( 'Bahama\'s', 'siw' ),
		'BAH'	=> __( 'Bahrein', 'siw' ),
		'BGD'	=> __( 'Bangladesh', 'siw' ),
		'BBD'	=> __( 'Barbados', 'siw' ),
		'BYE'	=> __( 'Wit-Rusland', 'siw' ),
		'BEL'	=> __( 'België', 'siw' ),
		'BLZ'	=> __( 'Belize', 'siw' ),
		'BEN'	=> __( 'Benin', 'siw' ),
		'BRM'	=> __( 'Bermuda', 'siw' ),
		'BUT'	=> __( 'Bhutan', 'siw' ),
		'BOL'	=> __( 'Bolivia', 'siw' ),
		'BOS'	=> __( 'Bosnië en Herzegovina', 'siw' ),
		'BTW'	=> __( 'Botswana', 'siw' ),
		'BRZ'	=> __( 'Brazilië', 'siw' ),
		'BLG'	=> __( 'Bulgarije', 'siw' ),
		'BKF'	=> __( 'Burkina Faso', 'siw' ),
		'BM'	=> __( 'Myanmar', 'siw' ),
		'BDI'	=> __( 'Burundi', 'siw' ),
		'CMG'	=> __( 'Cambodja', 'siw' ),
		'CMR'	=> __( 'Kameroen', 'siw' ),
		'CAN'	=> __( 'Canada', 'siw' ),
		'CVD'	=> __( 'Kaapverdië', 'siw' ),
		'CYD'	=> __( 'Kaaimaneilanden', 'siw' ),
		'CAF'	=> __( 'Centraal-Afrikaanse Republiek', 'siw' ),
		'TCD'	=> __( 'Tsjaad', 'siw' ),
		'CHL'	=> __( 'Chili', 'siw' ),
		'CHI'	=> __( 'China', 'siw' ),
		'COL'	=> __( 'Colombia', 'siw' ),
		'COM'	=> __( 'Comoren', 'siw' ),
		'COG'	=> __( 'Congo-Brazzaville', 'siw' ),
		'COD'	=> __( 'Congo-Kinshasa', 'siw' ),
		'CRI'	=> __( 'Costa Rica', 'siw' ),
		'CRO'	=> __( 'Kroatië', 'siw' ),
		'CUB'	=> __( 'Cuba', 'siw' ),
		'CHY'	=> __( 'Cyprus', 'siw' ),
		'CZE'	=> __( 'Tsjechië', 'siw' ),
		'DNK'	=> __( 'Denemarken', 'siw' ),
		'DMA'	=> __( 'Dominica', 'siw' ),
		'DOM'	=> __( 'Dominicaanse Republiek', 'siw' ),
		'ECU'	=> __( 'Ecuador', 'siw' ),
		'EGY'	=> __( 'Egypte', 'siw' ),
		'SLV'	=> __( 'El Salvador', 'siw' ),
		'EST'	=> __( 'Estland', 'siw' ),
		'ETH'	=> __( 'Ethiopië', 'siw' ),
		'FIN'	=> __( 'Finland', 'siw' ),
		'FRA'	=> __( 'Frankrijk', 'siw' ),
		'GEO'	=> __( 'Georgië', 'siw' ),
		'GER'	=> __( 'Duitsland', 'siw' ),
		'GHA'	=> __( 'Ghana', 'siw' ),
		'GBR'	=> __( 'Groot-Brittannië', 'siw' ),
		'GRE'	=> __( 'Griekenland', 'siw' ),
		'GL'	=> __( 'Groenland', 'siw' ),
		'GAT'	=> __( 'Guatemala', 'siw' ),
		'HT'	=> __( 'Haïti', 'siw' ),
		'HON'	=> __( 'Honduras', 'siw' ),
		'HKG'	=> __( 'Hongkong', 'siw' ),
		'HUN'	=> __( 'Hongarije', 'siw' ),
		'ISL'	=> __( 'IJsland', 'siw' ),
		'IND'	=> __( 'India', 'siw' ),
		'IDN'	=> __( 'Indonesië', 'siw' ),
		'IRN'	=> __( 'Iran', 'siw' ),
		'EIR'	=> __( 'Ierland', 'siw' ),
		'ISR'	=> __( 'Israël', 'siw' ),
		'ITA'	=> __( 'Italië', 'siw' ),
		'CIV'	=> __( 'Ivoorkust', 'siw' ),
		'JM'	=> __( 'Jamaica', 'siw' ),
		'JPN'	=> __( 'Japan', 'siw' ),
		'JOR'	=> __( 'Jordanië', 'siw' ),
		'KZ'	=> __( 'Kazachstan', 'siw' ),
		'KEN'	=> __( 'Kenia', 'siw' ),
		'KOR'	=> __( 'Zuid-Korea', 'siw' ),
		'KGZ'	=> __( 'Kirgizië', 'siw' ),
		'LAO'	=> __( 'Laos', 'siw' ),
		'LTV'	=> __( 'Letland', 'siw' ),
		'LBN'	=> __( 'Libanon', 'siw' ),
		'LS'	=> __( 'Lesotho', 'siw' ),
		'LIT'	=> __( 'Litouwen', 'siw' ),
		'LUX'	=> __( 'Luxemburg', 'siw' ),
		'MK'	=> __( 'Macedonië', 'siw' ),
		'MG'	=> __( 'Madagaskar', 'siw' ),
		'MW'	=> __( 'Malawi', 'siw' ),
		'MLS'	=> __( 'Maleisië', 'siw' ),
		'MLI'	=> __( 'Mali', 'siw' ),
		'MU'	=> __( 'Mauritius', 'siw' ),
		'MEX'	=> __( 'Mexico', 'siw' ),
		'MOL'	=> __( 'Moldavië', 'siw' ),
		'MGL'	=> __( 'Mongolië', 'siw' ),
		'ME'	=> __( 'Montenegro', 'siw' ),
		'MAR'	=> __( 'Marokko', 'siw' ),
		'MOZ'	=> __( 'Mozambique', 'siw' ),
		'NEP'	=> __( 'Nepal', 'siw' ),
		'HOL'	=> __( 'Nederland', 'siw' ),
		'NZL'	=> __( 'Nieuw-Zeeland', 'siw' ),
		'NIC'	=> __( 'Nicaragua', 'siw' ),
		'NGR'	=> __( 'Niger', 'siw' ),
		'NIG'	=> __( 'Nigeria', 'siw' ),
		'NI'	=> __( 'Noord-Ierland', 'siw' ),
		'NOR'	=> __( 'Noorwegen', 'siw' ),
		'PK'	=> __( 'Pakistan', 'siw' ),
		'PS'	=> __( 'Palestina', 'siw' ),
		'PAR'	=> __( 'Paraguay', 'siw' ),
		'PER'	=> __( 'Peru', 'siw' ),
		'PHL'	=> __( 'Filipijnen', 'siw' ),
		'POL'	=> __( 'Polen', 'siw' ),
		'POR'	=> __( 'Portugal', 'siw' ),
		'ROM'	=> __( 'Roemenië', 'siw' ),
		'RUS'	=> __( 'Rusland', 'siw' ),
		'SEN'	=> __( 'Senegal', 'siw' ),
		'RS'	=> __( 'Servië', 'siw' ),
		'SL'	=> __( 'Sierra Leone', 'siw' ),
		'SGP'	=> __( 'Singapore', 'siw' ),
		'SLK'	=> __( 'Slowakije', 'siw' ),
		'SLO'	=> __( 'Slovenië', 'siw' ),
		'ZAF'	=> __( 'Zuid-Afrika', 'siw' ),
		'ESP'	=> __( 'Spanje', 'siw' ),
		'LK'	=> __( 'Sri Lanka', 'siw' ),
		'SVE'	=> __( 'Zweden', 'siw' ),
		'CH'	=> __( 'Zwitserland', 'siw' ),
		'TWN'	=> __( 'Taiwan', 'siw' ),
		'TAN'	=> __( 'Tanzania', 'siw' ),
		'THA'	=> __( 'Thailand', 'siw' ),
		'TOG'	=> __( 'Togo', 'siw' ),
		'TUN'	=> __( 'Tunesië', 'siw' ),
		'TUR'	=> __( 'Turkije', 'siw' ),
		'TKM'	=> __( 'Turkmenistan', 'siw' ),
		'UGA'	=> __( 'Oeganda', 'siw' ),
		'UKR'	=> __( 'Oekraïne', 'siw' ),
		'USA'	=> __( 'Verenigde Staten', 'siw' ),
		'URY'	=> __( 'Uruguay', 'siw' ),
		'UZB'	=> __( 'Oezbekistan', 'siw' ),
		'VEN'	=> __( 'Venezuela', 'siw' ),
		'VTN'	=> __( 'Vietnam', 'siw' ),
		'YEM'	=> __( 'Jemen', 'siw' ),
		'ZMB'	=> __( 'Zambia', 'siw' ),
		'ZIM'	=> __( 'Zimbabwe', 'siw' ),
	);
	return $nationalities;
}
