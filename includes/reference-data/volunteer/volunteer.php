<?php
/**
 * Referentielijsten voor vrijwilligers-data
 * - Geslacht
 * - Nationaliteit
 * 
 * @package 	SIW\Reference-Data
 * @copyright   2017-2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Geeft een array met geslachten terug
 *
 * @return array
 */
function siw_get_genders() {
	$genders = [
		'M' => __( 'Man', 'siw' ),
		'F' => __( 'Vrouw', 'siw' ),
	];
	return $genders;
}


/**
 * Geeft een array met nationaliteiten terug
 *
 * @return array
 */
function siw_get_nationalities() {
	$nationalities = [
		''		=> __( 'Selecteer een nationaliteit', 'siw' ),
		'AFG'	=> __( 'Afghaanse', 'siw' ),
		'ALB'	=> __( 'Albanese', 'siw' ),
		'ALG'	=> __( 'Algerijnse', 'siw' ),
		'USA'	=> __( 'Amerikaanse', 'siw' ),
		'AGO'	=> __( 'Angolese', 'siw' ),
		'ARG'	=> __( 'Argentijnse', 'siw' ),
		'ARM'	=> __( 'Armeense', 'siw' ),
		'AUS'	=> __( 'Australische', 'siw' ),
		'AZB'	=> __( 'Azerbaidzjaanse', 'siw' ),
		'BHS'	=> __( '(Burger van de) Bahama\'s', 'siw' ),
		'BAH'	=> __( 'Bahreinse ', 'siw' ),
		'BBD'	=> __( 'Barbadaanse', 'siw' ),
		'BEL'	=> __( 'Belgische', 'siw' ),
		'BLZ'	=> __( 'Belizaanse', 'siw' ),
		'BYE'	=> __( 'Belarussische ', 'siw' ),
		'BGD'	=> __( 'Bengalese ', 'siw' ),
		'BEN'	=> __( 'Beninse', 'siw' ),
		'BRM'	=> __( 'Bermuda', 'siw' ),
		'BUT'	=> __( 'Bhutaanse', 'siw' ),
		'BOL'	=> __( 'Boliviaanse', 'siw' ),
		'BOS'	=> __( '(Burger van) Bosnië-Herzegovina', 'siw' ),
		'BTW'	=> __( 'Botswana', 'siw' ),
		'BRZ'	=> __( 'Braziliaanse', 'siw' ),
		'GBR'	=> __( 'Britse', 'siw' ),
		'BLG'	=> __( 'Bulgaarse', 'siw' ),
		'BKF'	=> __( 'Burkina Faso', 'siw' ),
		'BDI'	=> __( 'Burundese', 'siw' ),
		'CMG'	=> __( 'Cambodjaanse', 'siw' ),
		'CAN'	=> __( 'Canadese', 'siw' ),
		'CAF'	=> __( 'Centraalafrikaanse', 'siw' ),
		'CHL'	=> __( 'Chileense', 'siw' ),
		'CHI'	=> __( 'Chinese', 'siw' ),
		'COL'	=> __( 'Colombiaanse', 'siw' ),
		'COM'	=> __( 'Comorese', 'siw' ),
		'COG'	=> __( 'Congolese (Republiek Congo)', 'siw' ),
		'COD'	=> __( 'Congolese (DR Congo)', 'siw' ),
		'CRI'	=> __( 'Costaricaanse', 'siw' ),
		'CUB'	=> __( 'Cubaanse', 'siw' ),
		'CHY'	=> __( 'Cypriotische', 'siw' ),
		'DNK'	=> __( 'Deense', 'siw' ),
		'DOM'	=> __( 'Dominicaanse', 'siw' ),
		'DMA'	=> __( 'Dominicase', 'siw' ),
		'GER'	=> __( 'Duitse', 'siw' ),
		'ECU'	=> __( 'Ecuadoraanse', 'siw' ),
		'EGY'	=> __( 'Egyptische', 'siw' ),
		'EST'	=> __( 'Estlandse', 'siw' ),
		'ETH'	=> __( 'Ethiopische', 'siw' ),
		'PHL'	=> __( 'Filipijnse', 'siw' ),
		'FIN'	=> __( 'Finse', 'siw' ),
		'FRA'	=> __( 'Franse', 'siw' ),
		'GEO'	=> __( 'Georgische', 'siw' ),
		'GHA'	=> __( 'Ghanese', 'siw' ),
		'GRE'	=> __( 'Griekse', 'siw' ),
		//'GL'	=> __( 'Groenland', 'siw' ), //TODO: geen nationaliteit volgens IND
		'GAT'	=> __( 'Guatemalteekse', 'siw' ),
		'HT'	=> __( 'Haïtiaanse', 'siw' ),
		'HON'	=> __( 'Hondurese', 'siw' ),
		//'HKG'	=> __( 'Hongkong', 'siw' ), //TODO: geen nationaliteit volgens IND
		'HUN'	=> __( 'Hongaarse', 'siw' ),
		'EIR'	=> __( 'Ierse', 'siw' ),
		'ISL'	=> __( 'IJslandse', 'siw' ),
		'IND'	=> __( 'Indiase', 'siw' ),
		'IDN'	=> __( 'Indonesische', 'siw' ),
		'IRN'	=> __( 'Iraanse', 'siw' ),
		'ISR'	=> __( 'Israëlische', 'siw' ),
		'ITA'	=> __( 'Italiaanse', 'siw' ),
		'CIV'	=> __( 'Ivoriaanse', 'siw' ),
		'JM'	=> __( 'Jamaicaanse', 'siw' ),
		'JPN'	=> __( 'Japanse', 'siw' ),
		'YEM'	=> __( 'Jemenitische', 'siw' ),
		'JOR'	=> __( 'Jordaanse', 'siw' ),
		'CVD'	=> __( 'Kaapverdische', 'siw' ),
		//'CYD'	=> __( 'Kaaimaneilanden', 'siw' ), //TODO:geen nationaliteit volgens IND
		'CMR'	=> __( 'Kameroense', 'siw' ),
		'KZ'	=> __( 'Kazachstaanse', 'siw' ),
		'KEN'	=> __( 'Keniaanse', 'siw' ),
		'KGZ'	=> __( 'Kirgizische', 'siw' ),
		'CRO'	=> __( 'Kroatische', 'siw' ),
		'LAO'	=> __( 'Laotiaanse', 'siw' ),
		'LTV'	=> __( 'Letse', 'siw' ),
		'LS'	=> __( 'Lesothaanse', 'siw' ),
		'LBN'	=> __( 'Libanese', 'siw' ),
		'LIT'	=> __( 'Litouwse', 'siw' ),
		'LUX'	=> __( 'Luxemburg', 'siw' ),
		'MK'	=> __( 'Macedonische', 'siw' ),
		'MG'	=> __( 'Malagassische', 'siw' ),
		'MW'	=> __( 'Malawische', 'siw' ),
		'MLS'	=> __( 'Maleisische', 'siw' ),
		'MLI'	=> __( 'Malinese', 'siw' ),
		'MU'	=> __( 'Mauritiaanse', 'siw' ),
		'MEX'	=> __( 'Mexicaanse', 'siw' ),
		'MOL'	=> __( 'Moldavische', 'siw' ),
		'MGL'	=> __( 'Mongoolse', 'siw' ),
		'ME'	=> __( 'Montenegrijnse', 'siw' ),
		'MAR'	=> __( 'Marokkaanse', 'siw' ),
		'MOZ'	=> __( 'Mozambikaanse', 'siw' ),
		'BM'	=> __( '(Burger van) Myanmar', 'siw' ),
		'HOL'	=> __( 'Nederlandse', 'siw' ),
		'NEP'	=> __( 'Nepalese', 'siw' ),
		'NZL'	=> __( 'Nieuw-Zeelandse', 'siw' ),
		'NIC'	=> __( 'Nicaraguaanse', 'siw' ),
		'NGR'	=> __( 'Nigerese', 'siw' ),
		'NIG'	=> __( 'Nigeriaanse', 'siw' ),
		//'NI'	=> __( 'Noord-Ierland', 'siw' ), //TODO: geen nationaliteit volgens IND
		'NOR'	=> __( 'Noorse', 'siw' ),
		'UGA'	=> __( 'Oegandese', 'siw' ),
		'UKR'	=> __( 'Oekraïense', 'siw' ),
		'UZB'	=> __( 'Oezbeekse', 'siw' ),
		'AT'	=> __( 'Oostenrijkse', 'siw' ),
		'PK'	=> __( 'Pakistaanse', 'siw' ),
		//'PS'	=> __( 'Palestina', 'siw' ), //TODO: geen nationaliteit volgens IND
		'PAR'	=> __( 'Paraguayaanse', 'siw' ),
		'PER'	=> __( 'Peruaanse', 'siw' ),
		'POL'	=> __( 'Poolse', 'siw' ),
		'POR'	=> __( 'Portugese', 'siw' ),
		'ROM'	=> __( 'Roemeense', 'siw' ),
		'RUS'	=> __( 'Russische', 'siw' ),
		'SLV'	=> __( 'Salvadoraanse', 'siw' ),
		'SEN'	=> __( 'Senegalese', 'siw' ),
		'RS'	=> __( 'Servische', 'siw' ),
		'SL'	=> __( 'Sierra Leoonse', 'siw' ),
		'SGP'	=> __( 'Singaporaanse', 'siw' ),
		'SLO'	=> __( 'Sloveense', 'siw' ),
		'SLK'	=> __( 'Slowaakse', 'siw' ),
		'ESP'	=> __( 'Spaanse', 'siw' ),
		'LK'	=> __( 'Srilankaanse', 'siw' ),
		'TWN'	=> __( 'Taiwanese', 'siw' ),
		'TAN'	=> __( 'Tanzaniaanse', 'siw' ),
		'THA'	=> __( 'Thaise', 'siw' ),
		'TKM'	=> __( 'Toerkmenistaanse', 'siw' ),
		'TOG'	=> __( 'Togolese', 'siw' ),
		'TCD'	=> __( 'Tsjadische', 'siw' ),
		'CZE'	=> __( 'Tsjechische', 'siw' ),
		'TUN'	=> __( 'Tunesische', 'siw' ),
		'TUR'	=> __( 'Turkse', 'siw' ),
		'URY'	=> __( 'Uruguayaanse', 'siw' ),
		'VEN'	=> __( 'Venezolaanse', 'siw' ),
		'VTN'	=> __( 'Vietnamese', 'siw' ),
		'ZMB'	=> __( 'Zambiaanse', 'siw' ),
		'ZIM'	=> __( 'Zimbabwaanse', 'siw' ),
		'ZAF'	=> __( 'Zuid-Afrikaanse', 'siw' ),
		'KOR'	=> __( 'Zuid-Koreaanse', 'siw' ),
		'SVE'	=> __( 'Zweedse', 'siw' ),
		'CH'	=> __( 'Zwitserse', 'siw' ),
	];
	return $nationalities;
}
