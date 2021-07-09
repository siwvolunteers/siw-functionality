<?php declare(strict_types=1);

namespace SIW\Data;

use \Spatie\Enum\Enum;

/**
 * Nationaliteiten
 * 
 * @todo geen nationaliteit volgens IND:
 * - PS => Palestina
 * - NI => Noord-Ierland
 * - CYD => Kaaimaneilanden
 * - HKG => Hongkong
 * - GL => Groenland
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @method static self AFG() Afghaanse
 * @method static self ALB() Albanese
 * @method static self ALG() Algerijnse
 * @method static self USA() Amerikaanse
 * @method static self AGO() Angolese
 * @method static self ARG() Argentijnse
 * @method static self ARM() Armeense
 * @method static self AUS() Australische
 * @method static self AZB() Azerbaidzjaanse
 * @method static self BHS() (Burger van de) Bahama\'s
 * @method static self BAH() Bahreinse
 * @method static self BBD() Barbadaanse
 * @method static self BEL() Belgische
 * @method static self BLZ() Belizaanse
 * @method static self BYE() Belarussische
 * @method static self BGD() Bengalese
 * @method static self BEN() Beninse
 * @method static self BRM() Bermuda
 * @method static self BUT() Bhutaanse
 * @method static self BOL() Boliviaanse
 * @method static self BOS() (Burger van) Bosnië-Herzegovina
 * @method static self BTW() Botswana
 * @method static self BRZ() Braziliaanse
 * @method static self GBR() Britse
 * @method static self BLG() Bulgaarse
 * @method static self BKF() Burkina Faso
 * @method static self BDI() Burundese
 * @method static self CMG() Cambodjaanse
 * @method static self CAN() Canadese
 * @method static self CAF() Centraalafrikaanse
 * @method static self CHL() Chileense
 * @method static self CHI() Chinese
 * @method static self COL() Colombiaanse
 * @method static self COM() Comorese
 * @method static self COG() Congolese (Republiek Congo)
 * @method static self COD() Congolese (DR Congo)
 * @method static self CRI() Costaricaanse
 * @method static self CUB() Cubaanse
 * @method static self CHY() Cypriotische
 * @method static self DNK() Deense
 * @method static self DOM() Dominicaanse
 * @method static self DMA() Dominicase
 * @method static self GER() Duitse
 * @method static self ECU() Ecuadoraanse
 * @method static self EGY() Egyptische
 * @method static self EST() Estlandse
 * @method static self ETH() Ethiopische
 * @method static self PHL() Filipijnse
 * @method static self FIN() Finse
 * @method static self FRA() Franse
 * @method static self GEO() Georgische
 * @method static self GHA() Ghanese
 * @method static self GRE() Griekse
 * @method static self GAT() Guatemalteekse
 * @method static self HT()  Haïtiaanse
 * @method static self HON() Hondurese
 * @method static self HUN() Hongaarse
 * @method static self EIR() Ierse
 * @method static self ISL() IJslandse
 * @method static self IND() Indiase
 * @method static self IDN() Indonesische
 * @method static self IRN() Iraanse
 * @method static self ISR() Israëlische
 * @method static self ITA() Italiaanse
 * @method static self CIV() Ivoriaanse
 * @method static self JM()  Jamaicaanse
 * @method static self JPN() Japanse
 * @method static self YEM() Jemenitische
 * @method static self JOR() Jordaanse
 * @method static self CVD() Kaapverdische
 * @method static self CMR() Kameroense
 * @method static self KZ()  Kazachstaanse
 * @method static self KEN() Keniaanse
 * @method static self KGZ() Kirgizische
 * @method static self CRO() Kroatische
 * @method static self LAO() Laotiaanse
 * @method static self LTV() Letse
 * @method static self LS()  Lesothaanse
 * @method static self LBN() Libanese
 * @method static self LIT() Litouwse
 * @method static self LUX() Luxemburg
 * @method static self MK()  Macedonische
 * @method static self MG()  Malagassische
 * @method static self MW()  Malawische
 * @method static self MLS() Maleisische
 * @method static self MLI() Malinese
 * @method static self MU()  Mauritiaanse
 * @method static self MEX() Mexicaanse
 * @method static self MOL() Moldavische
 * @method static self MGL() Mongoolse
 * @method static self ME()  Montenegrijnse
 * @method static self MAR() Marokkaanse
 * @method static self MOZ() Mozambikaanse
 * @method static self BM()  (Burger van) Myanmar
 * @method static self HOL() Nederlandse
 * @method static self NEP() Nepalese
 * @method static self NZL() Nieuw-Zeelandse
 * @method static self NIC() Nicaraguaanse
 * @method static self NGR() Nigerese
 * @method static self NIG() Nigeriaanse
 * @method static self NOR() Noorse
 * @method static self UGA() Oegandese
 * @method static self UKR() Oekraïense
 * @method static self UZB() Oezbeekse
 * @method static self AT()  Oostenrijkse
 * @method static self PK()  Pakistaanse
 * @method static self PAR() Paraguayaanse
 * @method static self PER() Peruaanse
 * @method static self POL() Poolse
 * @method static self POR() Portugese
 * @method static self ROM() Roemeense
 * @method static self RUS() Russische
 * @method static self SLV() Salvadoraanse
 * @method static self SEN() Senegalese
 * @method static self RS()  Servische
 * @method static self SL()  Sierra Leoonse
 * @method static self SGP() Singaporaanse
 * @method static self SLO() Sloveense
 * @method static self SLK() Slowaakse
 * @method static self ESP() Spaanse
 * @method static self LK()  Srilankaanse
 * @method static self TWN() Taiwanese
 * @method static self TAN() Tanzaniaanse
 * @method static self THA() Thaise
 * @method static self TKM() Toerkmenistaanse
 * @method static self TOG() Togolese
 * @method static self TCD() Tsjadische
 * @method static self CZE() Tsjechische
 * @method static self TUN() Tunesische
 * @method static self TUR() Turkse
 * @method static self URY() Uruguayaanse
 * @method static self VEN() Venezolaanse
 * @method static self VTN() Vietnamese
 * @method static self ZMB() Zambiaanse
 * @method static self ZIM() Zimbabwaanse
 * @method static self ZAF() Zuid-Afrikaanse
 * @method static self KOR() Zuid-Koreaanse
 * @method static self SVE() Zweedse
 * @method static self CH()  Zwitserse
 */
class Nationality extends Enum {

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'AFG' => __( 'Afghaanse', 'siw' ),
			'ALB' => __( 'Albanese', 'siw' ),
			'ALG' => __( 'Algerijnse', 'siw' ),
			'USA' => __( 'Amerikaanse', 'siw' ),
			'AGO' => __( 'Angolese', 'siw' ),
			'ARG' => __( 'Argentijnse', 'siw' ),
			'ARM' => __( 'Armeense', 'siw' ),
			'AUS' => __( 'Australische', 'siw' ),
			'AZB' => __( 'Azerbaidzjaanse', 'siw' ),
			'BHS' => __( '(Burger van de) Bahama\'s', 'siw' ),
			'BAH' => __( 'Bahreinse', 'siw' ),
			'BBD' => __( 'Barbadaanse', 'siw' ),
			'BEL' => __( 'Belgische', 'siw' ),
			'BLZ' => __( 'Belizaanse', 'siw' ),
			'BYE' => __( 'Belarussische', 'siw' ),
			'BGD' => __( 'Bengalese', 'siw' ),
			'BEN' => __( 'Beninse', 'siw' ),
			'BRM' => __( 'Bermuda', 'siw' ),
			'BUT' => __( 'Bhutaanse', 'siw' ),
			'BOL' => __( 'Boliviaanse', 'siw' ),
			'BOS' => __( '(Burger van) Bosnië-Herzegovina', 'siw' ),
			'BTW' => __( 'Botswana', 'siw' ),
			'BRZ' => __( 'Braziliaanse', 'siw' ),
			'GBR' => __( 'Britse', 'siw' ),
			'BLG' => __( 'Bulgaarse', 'siw' ),
			'BKF' => __( 'Burkina Faso', 'siw' ),
			'BDI' => __( 'Burundese', 'siw' ),
			'CMG' => __( 'Cambodjaanse', 'siw' ),
			'CAN' => __( 'Canadese', 'siw' ),
			'CAF' => __( 'Centraalafrikaanse', 'siw' ),
			'CHL' => __( 'Chileense', 'siw' ),
			'CHI' => __( 'Chinese', 'siw' ),
			'COL' => __( 'Colombiaanse', 'siw' ),
			'COM' => __( 'Comorese', 'siw' ),
			'COG' => __( 'Congolese (Republiek Congo)', 'siw' ),
			'COD' => __( 'Congolese (DR Congo)', 'siw' ),
			'CRI' => __( 'Costaricaanse', 'siw' ),
			'CUB' => __( 'Cubaanse', 'siw' ),
			'CHY' => __( 'Cypriotische', 'siw' ),
			'DNK' => __( 'Deense', 'siw' ),
			'DOM' => __( 'Dominicaanse', 'siw' ),
			'DMA' => __( 'Dominicase', 'siw' ),
			'GER' => __( 'Duitse', 'siw' ),
			'ECU' => __( 'Ecuadoraanse', 'siw' ),
			'EGY' => __( 'Egyptische', 'siw' ),
			'EST' => __( 'Estlandse', 'siw' ),
			'ETH' => __( 'Ethiopische', 'siw' ),
			'PHL' => __( 'Filipijnse', 'siw' ),
			'FIN' => __( 'Finse', 'siw' ),
			'FRA' => __( 'Franse', 'siw' ),
			'GEO' => __( 'Georgische', 'siw' ),
			'GHA' => __( 'Ghanese', 'siw' ),
			'GRE' => __( 'Griekse', 'siw' ),
			'GAT' => __( 'Guatemalteekse', 'siw' ),
			'HT'  => __( 'Haïtiaanse', 'siw' ),
			'HON' => __( 'Hondurese', 'siw' ),
			'HUN' => __( 'Hongaarse', 'siw' ),
			'EIR' => __( 'Ierse', 'siw' ),
			'ISL' => __( 'IJslandse', 'siw' ),
			'IND' => __( 'Indiase', 'siw' ),
			'IDN' => __( 'Indonesische', 'siw' ),
			'IRN' => __( 'Iraanse', 'siw' ),
			'ISR' => __( 'Israëlische', 'siw' ),
			'ITA' => __( 'Italiaanse', 'siw' ),
			'CIV' => __( 'Ivoriaanse', 'siw' ),
			'JM'  => __( 'Jamaicaanse', 'siw' ),
			'JPN' => __( 'Japanse', 'siw' ),
			'YEM' => __( 'Jemenitische', 'siw' ),
			'JOR' => __( 'Jordaanse', 'siw' ),
			'CVD' => __( 'Kaapverdische', 'siw' ),
			'CMR' => __( 'Kameroense', 'siw' ),
			'KZ'  => __( 'Kazachstaanse', 'siw' ),
			'KEN' => __( 'Keniaanse', 'siw' ),
			'KGZ' => __( 'Kirgizische', 'siw' ),
			'CRO' => __( 'Kroatische', 'siw' ),
			'LAO' => __( 'Laotiaanse', 'siw' ),
			'LTV' => __( 'Letse', 'siw' ),
			'LS'  => __( 'Lesothaanse', 'siw' ),
			'LBN' => __( 'Libanese', 'siw' ),
			'LIT' => __( 'Litouwse', 'siw' ),
			'LUX' => __( 'Luxemburg', 'siw' ),
			'MK'  => __( 'Macedonische', 'siw' ),
			'MG'  => __( 'Malagassische', 'siw' ),
			'MW'  => __( 'Malawische', 'siw' ),
			'MLS' => __( 'Maleisische', 'siw' ),
			'MLI' => __( 'Malinese', 'siw' ),
			'MU'  => __( 'Mauritiaanse', 'siw' ),
			'MEX' => __( 'Mexicaanse', 'siw' ),
			'MOL' => __( 'Moldavische', 'siw' ),
			'MGL' => __( 'Mongoolse', 'siw' ),
			'ME'  => __( 'Montenegrijnse', 'siw' ),
			'MAR' => __( 'Marokkaanse', 'siw' ),
			'MOZ' => __( 'Mozambikaanse', 'siw' ),
			'BM'  => __( '(Burger van) Myanmar', 'siw' ),
			'HOL' => __( 'Nederlandse', 'siw' ),
			'NEP' => __( 'Nepalese', 'siw' ),
			'NZL' => __( 'Nieuw-Zeelandse', 'siw' ),
			'NIC' => __( 'Nicaraguaanse', 'siw' ),
			'NGR' => __( 'Nigerese', 'siw' ),
			'NIG' => __( 'Nigeriaanse', 'siw' ),
			'NOR' => __( 'Noorse', 'siw' ),
			'UGA' => __( 'Oegandese', 'siw' ),
			'UKR' => __( 'Oekraïense', 'siw' ),
			'UZB' => __( 'Oezbeekse', 'siw' ),
			'AT'  => __( 'Oostenrijkse', 'siw' ),
			'PK'  => __( 'Pakistaanse', 'siw' ),
			'PAR' => __( 'Paraguayaanse', 'siw' ),
			'PER' => __( 'Peruaanse', 'siw' ),
			'POL' => __( 'Poolse', 'siw' ),
			'POR' => __( 'Portugese', 'siw' ),
			'ROM' => __( 'Roemeense', 'siw' ),
			'RUS' => __( 'Russische', 'siw' ),
			'SLV' => __( 'Salvadoraanse', 'siw' ),
			'SEN' => __( 'Senegalese', 'siw' ),
			'RS'  => __( 'Servische', 'siw' ),
			'SL'  => __( 'Sierra Leoonse', 'siw' ),
			'SGP' => __( 'Singaporaanse', 'siw' ),
			'SLO' => __( 'Sloveense', 'siw' ),
			'SLK' => __( 'Slowaakse', 'siw' ),
			'ESP' => __( 'Spaanse', 'siw' ),
			'LK'  => __( 'Srilankaanse', 'siw' ),
			'TWN' => __( 'Taiwanese', 'siw' ),
			'TAN' => __( 'Tanzaniaanse', 'siw' ),
			'THA' => __( 'Thaise', 'siw' ),
			'TKM' => __( 'Toerkmenistaanse', 'siw' ),
			'TOG' => __( 'Togolese', 'siw' ),
			'TCD' => __( 'Tsjadische', 'siw' ),
			'CZE' => __( 'Tsjechische', 'siw' ),
			'TUN' => __( 'Tunesische', 'siw' ),
			'TUR' => __( 'Turkse', 'siw' ),
			'URY' => __( 'Uruguayaanse', 'siw' ),
			'VEN' => __( 'Venezolaanse', 'siw' ),
			'VTN' => __( 'Vietnamese', 'siw' ),
			'ZMB' => __( 'Zambiaanse', 'siw' ),
			'ZIM' => __( 'Zimbabwaanse', 'siw' ),
			'ZAF' => __( 'Zuid-Afrikaanse', 'siw' ),
			'KOR' => __( 'Zuid-Koreaanse', 'siw' ),
			'SVE' => __( 'Zweedse', 'siw' ),
			'CH'  => __( 'Zwitserse', 'siw' ),
		];
	}
}
