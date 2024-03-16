<?php declare(strict_types=1); // phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded

namespace SIW\Data;

use SIW\Data\Continent;
use SIW\Interfaces\Enums\Labels;
use SIW\Interfaces\Enums\Plato_Code;
use SIW\Traits\Enum_List;
use SIW\Traits\Plato_Enum;

enum Country: string implements Labels, Plato_Code {

	use Enum_List;
	use Plato_Enum;

	// Afrikaanse landen
	case BURUNDI = 'burundi';
	case BOTSWANA = 'botswana';
	case GHANA = 'ghana';
	case KENYA = 'kenia';
	case MADAGASCAR = 'madagaskar';
	case MOROCCO = 'marokko';
	case MOZAMBIQUE = 'mozambique';
	case SENEGAL = 'senegal';
	case TOGO = 'togo';
	case TUNISIA = 'tunesie';
	case TANZANIA = 'tanzania';
	case UGANDA = 'uganda';
	case SOUTH_AFRICA = 'zuid-afrika';
	case ZAMBIA = 'zambia';
	case ZIMBABWE = 'zimbabwe';

	// Aziatische landen
	case CHINA = 'china';
	case HONG_KONG = 'hong-kong';
	case INDONESIA = 'indonesie';
	case INDIA = 'india';
	case JAPAN = 'japan';
	case KYRGYZSTAN = 'kirgizie';
	case CAMBODIA = 'cambodja';
	case SOUTH_KOREA = 'zuid-korea';
	case SRI_LANKA = 'sri-lanka';
	case LAOS = 'laos';
	case MONGOLIA = 'mongolie';
	case MALAYSIA = 'maleisie';
	case NEPAL = 'nepal';
	case THAILAND = 'thailand';
	case TAIWAN = 'taiwan';
	case VIETNAM = 'vietnam';
	case PHILIPPINES = 'filipijnen';

	// Europese landen
	case ALBANIA = 'albanie';
	case ARMENIA = 'armenie';
	case AUSTRIA = 'oostenrijk';
	case BELGIUM = 'belgie';
	case BULGARIA = 'bulgarije';
	case BELARUS = 'wit-rusland';
	case SWITZERLAND = 'zwitserland';
	case CYPRUS = 'cyprus';
	case CZECH_REPUBLIC = 'tsjechie';
	case GERMANY = 'duitsland';
	case DENMARK = 'denemarken';
	case SPAIN = 'spanje';
	case ESTONIA = 'estland';
	case FINLAND = 'finland';
	case FRANCE = 'frankrijk';
	case UNITED_KINGDOM = 'verenigd-koninkrijk';
	case GEORGIA = 'georgie';
	case GREECE = 'griekenland';
	case HUNGARY = 'hongarije';
	case CROATIA = 'kroatie';
	case IRELAND = 'ierland';
	case ICELAND = 'ijsland';
	case ITALY = 'italie';
	case LIECHTENSTEIN = 'liechtenstein';
	case LITHUANIA = 'litouwen';
	case LUXEMBOURG = 'luxemburg';
	case LATVIA = 'letland';
	case MOLDOVA = 'moldavie';
	case MACEDONIA = 'macedonie';
	case MALTA = 'malta';
	case MONTENEGRO = 'montenegro';
	case NETHERLANDS = 'nederland';
	case NORWAY = 'noorwegen';
	case POLAND = 'polen';
	case PORTUGAL = 'portugal';
	case ROMANIA = 'roemenie';
	case RUSSIA = 'rusland';
	case SERBIA = 'servie';
	case SLOVAKIA = 'slowakije';
	case SLOVENIA = 'slovenie';
	case SWEDEN = 'zweden';
	case TURKEY = 'turkije';
	case UKRAINE = 'oekraine';
	case KOSOVO = 'kosovo';

	//Latijns-Amerikaanse landen
	case ARUBA = 'aruba';
	case ARGENTINA = 'argentinie';
	case BOLIVIA = 'bolivia';
	case BRAZIL = 'brazilie';
	case COLOMBIA = 'colombia';
	case COSTA_RICA = 'costa-rica';
	case ECUADOR = 'ecuador';
	case GUATEMALA = 'guatemale';
	case HAITI = 'haiti';
	case MEXICO = 'mexico';
	case PERU = 'peru';

	// Noord-Amerikaanse landen
	case CANADA = 'canada';
	case GREENLAND = 'groenland';
	case UNITED_STATES = 'verenigde-staten';


	#[\Override]
	public function label(): string {
		// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
		return match ( $this ) {
			//Afrikaanse landen
			self::BURUNDI => __( 'Burundi', 'woocommerce' ),
			self::BOTSWANA => __( 'Botswana', 'woocommerce' ),
			self::GHANA => __( 'Ghana', 'woocommerce' ),
			self::KENYA => __( 'Kenya', 'woocommerce' ),
			self::MADAGASCAR => __( 'Madagascar', 'woocommerce' ),
			self::MOROCCO => __( 'Morocco', 'woocommerce' ),
			self::MOZAMBIQUE => __( 'Mozambique', 'woocommerce' ),
			self::SENEGAL => __( 'Senegal', 'woocommerce' ),
			self::TOGO => __( 'Togo', 'woocommerce' ),
			self::TUNISIA => __( 'Tunisia', 'woocommerce' ),
			self::TANZANIA => __( 'Tanzania', 'woocommerce' ),
			self::UGANDA => __( 'Uganda', 'woocommerce' ),
			self::SOUTH_AFRICA => __( 'South Africa', 'woocommerce' ),
			self::ZAMBIA => __( 'Zambia', 'woocommerce' ),
			self::ZIMBABWE => __( 'Zimbabwe', 'woocommerce' ),

			//Aziatische landens
			self::CHINA => __( 'China', 'woocommerce' ),
			self::HONG_KONG => __( 'Hong Kong', 'woocommerce' ),
			self::INDONESIA => __( 'Indonesia', 'woocommerce' ),
			self::INDIA => __( 'India', 'woocommerce' ),
			self::JAPAN => __( 'Japan', 'woocommerce' ),
			self::KYRGYZSTAN => __( 'Kyrgyzstan', 'woocommerce' ),
			self::CAMBODIA => __( 'Cambodia', 'woocommerce' ),
			self::SOUTH_KOREA => __( 'South Korea', 'woocommerce' ),
			self::SRI_LANKA => __( 'Sri Lanka', 'woocommerce' ),
			self::LAOS => __( 'Laos', 'woocommerce' ),
			self::MONGOLIA => __( 'Mongolia', 'woocommerce' ),
			self::MALAYSIA => __( 'Malaysia', 'woocommerce' ),
			self::NEPAL => __( 'Nepal', 'woocommerce' ),
			self::THAILAND => __( 'Thailand', 'woocommerce' ),
			self::TAIWAN => __( 'Taiwan', 'woocommerce' ),
			self::VIETNAM => __( 'Vietnam', 'woocommerce' ),
			self::PHILIPPINES => __( 'Philippines', 'woocommerce' ),

			//Europese landen
			self::ALBANIA => __( 'Albania', 'woocommerce' ),
			self::ARMENIA => __( 'Armenia', 'woocommerce' ),
			self::AUSTRIA => __( 'Austria', 'woocommerce' ),
			self::BELGIUM =>  __( 'Belgium', 'woocommerce' ),
			self::BULGARIA => __( 'Bulgaria', 'woocommerce' ),
			self::BELARUS => __( 'Belarus', 'woocommerce' ),
			self::SWITZERLAND => __( 'Switzerland', 'woocommerce' ),
			self::CYPRUS => __( 'Cyprus', 'woocommerce' ),
			self::CZECH_REPUBLIC => __( 'Czech Republic', 'woocommerce' ),
			self::GERMANY => __( 'Germany', 'woocommerce' ),
			self::DENMARK =>  __( 'Denmark', 'woocommerce' ),
			self::SPAIN => __( 'Spain', 'woocommerce' ),
			self::ESTONIA => __( 'Estonia', 'woocommerce' ),
			self::FINLAND => __( 'Finland', 'woocommerce' ),
			self::FRANCE => __( 'France', 'woocommerce' ),
			self::UNITED_KINGDOM => __( 'United Kingdom (UK)', 'woocommerce' ),
			self::GEORGIA => __( 'Georgia', 'woocommerce' ),
			self::GREECE => __( 'Greece', 'woocommerce' ),
			self::HUNGARY => __( 'Hungary', 'woocommerce' ),
			self::CROATIA => __( 'Croatia', 'woocommerce' ),
			self::IRELAND => __( 'Ireland', 'woocommerce' ),
			self::ICELAND => __( 'Iceland', 'woocommerce' ),
			self::ITALY => __( 'Italy', 'woocommerce' ),
			self::LIECHTENSTEIN => __( 'Liechtenstein', 'woocommerce' ),
			self::LITHUANIA => __( 'Lithuania', 'woocommerce' ),
			self::LUXEMBOURG => __( 'Luxembourg', 'woocommerce' ),
			self::LATVIA => __( 'Latvia', 'woocommerce' ),
			self::MOLDOVA => __( 'Moldova', 'woocommerce' ),
			self::MACEDONIA => __( 'North Macedonia', 'woocommerce' ),
			self::MALTA => __( 'Malta', 'woocommerce' ),
			self::MONTENEGRO => __( 'Montenegro', 'woocommerce' ),
			self::NETHERLANDS => __( 'Netherlands', 'woocommerce' ),
			self::NORWAY => __( 'Norway', 'woocommerce' ),
			self::POLAND => __( 'Poland', 'woocommerce' ),
			self::PORTUGAL => __( 'Portugal', 'woocommerce' ),
			self::ROMANIA => __( 'Romania', 'woocommerce' ),
			self::RUSSIA => __( 'Russia', 'woocommerce' ),
			self::SERBIA => __( 'Serbia', 'woocommerce' ),
			self::SLOVAKIA => __( 'Slovakia', 'woocommerce' ),
			self::SLOVENIA => __( 'Slovenia', 'woocommerce' ),
			self::SWEDEN => __( 'Sweden', 'woocommerce' ),
			self::TURKEY =>__( 'Turkey', 'woocommerce' ),
			self::UKRAINE => __( 'Ukraine', 'woocommerce' ),
			self::KOSOVO => __( 'Kosovo', 'siw' ), // https://github.com/woocommerce/woocommerce/pull/36662

			//Latijns-Amerikaanse landen
			self::ARUBA => __( 'Aruba', 'woocommerce' ),
			self::ARGENTINA => __( 'Argentina', 'woocommerce' ),
			self::BOLIVIA => __( 'Bolivia', 'woocommerce' ),
			self::BRAZIL => __( 'Brazil', 'woocommerce' ),
			self::COLOMBIA => __( 'Colombia', 'woocommerce' ),
			self::COSTA_RICA => __( 'Costa Rica', 'woocommerce' ),
			self::ECUADOR => __( 'Ecuador', 'woocommerce' ),
			self::GUATEMALA => __( 'Guatemala', 'woocommerce' ),
			self::HAITI => __( 'Haiti', 'woocommerce' ),
			self::MEXICO => __( 'Mexico', 'woocommerce' ),
			self::PERU => __( 'Peru', 'woocommerce' ),

			//Noord-Amerikaanse landen
			self::CANADA => __( 'Canada', 'woocommerce' ),
			self::GREENLAND => __( 'Greenland', 'woocommerce' ),
			self::UNITED_STATES =>  __( 'United States (US)', 'woocommerce' ),
		};
		// phpcs:enable WordPress.WP.I18n.TextDomainMismatch
	}

	#[\Override]
	public function plato_code(): string {
		return match ( $this ) {
			// Afrikaanse landen
			self::BURUNDI => 'BDI',
			self::BOTSWANA => 'BWA',
			self::GHANA => 'GHA',
			self::KENYA => 'KEN',
			self::MADAGASCAR => 'MDG',
			self::MOROCCO => 'MAR',
			self::MOZAMBIQUE => 'MOZ',
			self::SENEGAL => 'SEN',
			self::TOGO => 'TGO',
			self::TUNISIA => 'TUN',
			self::TANZANIA => 'TZA',
			self::UGANDA => 'UGA',
			self::SOUTH_AFRICA => 'ZAF',
			self::ZAMBIA => 'ZMB',
			self::ZIMBABWE => 'ZWE',

			// Aziatische landen
			self::CHINA => 'CHN',
			self::HONG_KONG => 'HKG',
			self::INDONESIA => 'IDN',
			self::INDIA => 'IND',
			self::JAPAN => 'JPN',
			self::KYRGYZSTAN => 'KGZ',
			self::CAMBODIA => 'KHM',
			self::SOUTH_KOREA => 'KOR',
			self::SRI_LANKA => 'LKA',
			self::LAOS => 'LAO',
			self::MONGOLIA => 'MNG',
			self::MALAYSIA => 'MYS',
			self::NEPAL => 'NPL',
			self::THAILAND => 'THA',
			self::TAIWAN => 'TWN',
			self::VIETNAM => 'VNM',
			self::PHILIPPINES => 'PHL',

			// Europese landen
			self::ALBANIA => 'ALB',
			self::ARMENIA => 'ARM',
			self::AUSTRIA => 'AUT',
			self::BELGIUM => 'BEL',
			self::BULGARIA => 'BGR',
			self::BELARUS => 'BLR',
			self::SWITZERLAND => 'CHE',
			self::CYPRUS => 'CYP',
			self::CZECH_REPUBLIC => 'CZE',
			self::GERMANY => 'DEU',
			self::DENMARK => 'DNK',
			self::SPAIN => 'ESP',
			self::ESTONIA => 'EST',
			self::FINLAND => 'FIN',
			self::FRANCE => 'FRA',
			self::UNITED_KINGDOM => 'GBR',
			self::GEORGIA => 'GEO',
			self::GREECE => 'GRC',
			self::HUNGARY => 'HUN',
			self::CROATIA => 'HRV',
			self::IRELAND => 'IRL',
			self::ICELAND => 'ISL',
			self::ITALY => 'ITA',
			self::LIECHTENSTEIN => 'LIE',
			self::LITHUANIA => 'LTU',
			self::LUXEMBOURG => 'LUX',
			self::LATVIA => 'LVA',
			self::MOLDOVA => 'MDA',
			self::MACEDONIA => 'MKD',
			self::MALTA => 'MLT',
			self::MONTENEGRO => 'MNE',
			self::NETHERLANDS => 'NLD',
			self::NORWAY => 'NOR',
			self::POLAND => 'POL',
			self::PORTUGAL => 'PRT',
			self::ROMANIA => 'ROU',
			self::RUSSIA => 'RUS',
			self::SERBIA => 'SRB',
			self::SLOVAKIA => 'SVK',
			self::SLOVENIA => 'SVN',
			self::SWEDEN => 'SWE',
			self::TURKEY => 'TUR',
			self::UKRAINE => 'UKR',
			self::KOSOVO => 'XKS',

			//Latijns-Amerikaanse landen
			self::ARUBA => 'ABW',
			self::ARGENTINA => 'ARG',
			self::BOLIVIA => 'BOL',
			self::BRAZIL => 'BRA',
			self::COLOMBIA => 'COL',
			self::COSTA_RICA => 'CRI',
			self::ECUADOR => 'ECU',
			self::GUATEMALA => 'GTM',
			self::HAITI => 'HTE',
			self::MEXICO => 'MEX',
			self::PERU => 'PER',

			// Noord-Amerikaanse landen
			self::CANADA => 'CAN',
			self::GREENLAND => 'GRL',
			self::UNITED_STATES => 'USA',
		};
	}

	public function iso_code(): string {
		return match ( $this ) {
			self::BURUNDI => 'bd',
			self::BOTSWANA => 'bw',
			self::GHANA => 'gh',
			self::KENYA => 'ke',
			self::MADAGASCAR => 'mg',
			self::MOROCCO => 'ma',
			self::MOZAMBIQUE => 'mz',
			self::SENEGAL => 'sn',
			self::TOGO => 'tg',
			self::TUNISIA => 'tn',
			self::TANZANIA => 'tz',
			self::UGANDA => 'ug',
			self::SOUTH_AFRICA => 'za',
			self::ZAMBIA => 'zm',
			self::ZIMBABWE => 'zw',

			self::CHINA => 'cn',
			self::HONG_KONG => 'hk',
			self::INDONESIA => 'id',
			self::INDIA => 'in',
			self::JAPAN => 'jp',
			self::KYRGYZSTAN => 'kg',
			self::CAMBODIA => 'kh',
			self::SOUTH_KOREA => 'kr',
			self::SRI_LANKA => 'lk',
			self::LAOS => 'la',
			self::MONGOLIA => 'mn',
			self::MALAYSIA => 'my',
			self::NEPAL => 'np',
			self::THAILAND => 'th',
			self::TAIWAN => 'tw',
			self::VIETNAM => 'vn',
			self::PHILIPPINES => 'ph',

			//Europese landen
			self::ALBANIA => 'al',
			self::ARMENIA => 'am',
			self::AUSTRIA => 'at',
			self::BELGIUM => 'be',
			self::BULGARIA => 'bg',
			self::BELARUS => 'by',
			self::SWITZERLAND => 'ch',
			self::CYPRUS => 'cy',
			self::CZECH_REPUBLIC => 'cz',
			self::GERMANY => 'de',
			self::DENMARK => 'dk',
			self::SPAIN => 'es',
			self::ESTONIA => 'ee',
			self::FINLAND => 'fi',
			self::FRANCE => 'fr',
			self::UNITED_KINGDOM => 'gb',
			self::GEORGIA => 'ge',
			self::GREECE => 'gr',
			self::HUNGARY => 'hu',
			self::CROATIA => 'hr',
			self::IRELAND => 'ie',
			self::ICELAND => 'is',
			self::ITALY => 'it',
			self::LIECHTENSTEIN => 'li',
			self::LITHUANIA => 'lt',
			self::LUXEMBOURG => 'lu',
			self::LATVIA => 'lv',
			self::MOLDOVA => 'md',
			self::MACEDONIA => 'mk',
			self::MALTA => 'mt',
			self::MONTENEGRO => 'me',
			self::NETHERLANDS => 'nl',
			self::NORWAY => 'no',
			self::POLAND => 'pl',
			self::PORTUGAL => 'pt',
			self::ROMANIA => 'ro',
			self::RUSSIA => 'ru',
			self::SERBIA => 'rs',
			self::SLOVAKIA => 'sk',
			self::SLOVENIA => 'si',
			self::SWEDEN => 'se',
			self::TURKEY => 'tr',
			self::UKRAINE => 'ua',
			self::KOSOVO => 'xk',

			//Latijns-Amerikaanse landen
			self::ARUBA => 'aw',
			self::ARGENTINA => 'ar',
			self::BOLIVIA => 'bo',
			self::BRAZIL => 'br',
			self::COLOMBIA => 'co',
			self::COSTA_RICA => 'cr',
			self::ECUADOR => 'ec',
			self::GUATEMALA => 'gt',
			self::HAITI => 'ht',
			self::MEXICO => 'mx',
			self::PERU => 'pe',

			// Noord-Amerikaanse landen
			self::CANADA => 'ca',
			self::GREENLAND => 'gl',
			self::UNITED_STATES =>  'us',
		};
	}

	public function continent(): Continent {
		return match ( $this ) {
			self::BURUNDI,
			self::BOTSWANA,
			self::GHANA,
			self::KENYA,
			self::MOROCCO,
			self::MOZAMBIQUE,
			self::SENEGAL,
			self::TOGO,
			self::TUNISIA,
			self::TANZANIA,
			self::UGANDA,
			self::SOUTH_AFRICA,
			self::ZAMBIA,
			self::ZIMBABWE => Continent::AFRICA,
			self::CHINA,
			self::HONG_KONG,
			self::INDONESIA,
			self::INDIA,
			self::JAPAN,
			self::KYRGYZSTAN,
			self::CAMBODIA,
			self::SOUTH_KOREA,
			self::SRI_LANKA,
			self::LAOS,
			self::MONGOLIA,
			self::MALAYSIA,
			self::NEPAL,
			self::THAILAND,
			self::TAIWAN,
			self::VIETNAM,
			self::PHILIPPINES => Continent::ASIA,
			self::ALBANIA,
			self::ARMENIA,
			self::AUSTRIA,
			self::BELGIUM,
			self::BULGARIA,
			self::BELARUS,
			self::SWITZERLAND,
			self::CYPRUS,
			self::CZECH_REPUBLIC,
			self::GERMANY,
			self::DENMARK,
			self::SPAIN,
			self::ESTONIA,
			self::FINLAND,
			self::FRANCE,
			self::UNITED_KINGDOM,
			self::GEORGIA,
			self::GREECE,
			self::HUNGARY,
			self::CROATIA,
			self::IRELAND,
			self::ICELAND,
			self::ITALY,
			self::LIECHTENSTEIN,
			self::LITHUANIA,
			self::LUXEMBOURG,
			self::LATVIA,
			self::MOLDOVA,
			self::MACEDONIA,
			self::MALTA,
			self::MONTENEGRO,
			self::NETHERLANDS,
			self::NORWAY,
			self::POLAND,
			self::PORTUGAL,
			self::ROMANIA,
			self::RUSSIA,
			self::SERBIA,
			self::SLOVAKIA,
			self::SLOVENIA,
			self::SWEDEN,
			self::TURKEY,
			self::UKRAINE,
			self::KOSOVO => Continent::EUROPE,
			self::ARUBA,
			self::ARGENTINA,
			self::BOLIVIA,
			self::BRAZIL,
			self::COLOMBIA,
			self::COSTA_RICA,
			self::ECUADOR,
			self::HAITI,
			self::MEXICO,
			self::PERU => Continent::LATIN_AMERICA,
			self::CANADA,
			self::GREENLAND,
			self::UNITED_STATES => Continent::NORTH_AMERICA
		};
	}

	public function esc(): bool {
		return match ( $this ) {
			self::AUSTRIA,
			self::BELGIUM,
			self::BULGARIA,
			self::CYPRUS,
			self::GERMANY,
			self::DENMARK,
			self::SPAIN,
			self::ESTONIA,
			self::FINLAND,
			self::FRANCE,
			self::UNITED_KINGDOM,
			self::GREECE,
			self::HUNGARY,
			self::IRELAND,
			self::ICELAND,
			self::ITALY,
			self::LIECHTENSTEIN,
			self::LITHUANIA,
			self::LUXEMBOURG,
			self::LATVIA,
			self::MACEDONIA,
			self::MALTA,
			self::NORWAY,
			self::POLAND,
			self::PORTUGAL,
			self::ROMANIA,
			self::SLOVAKIA,
			self::SLOVENIA,
			self::SWEDEN,
			self::TURKEY,
			self::ARUBA => true,
			default => false,
		};
	}

	public function workcamps(): bool {
		return match ( $this ) {
			//Afrikaanse landen
			self::BOTSWANA,
			self::GHANA,
			self::KENYA,
			self::MOROCCO,
			self::MOZAMBIQUE,
			self::TOGO,
			self::TANZANIA,
			self::UGANDA,
			self::SOUTH_AFRICA,
			self::ZAMBIA,
			self::ZIMBABWE,

			//Aziatische landen
			self::CHINA,
			self::HONG_KONG,
			self::INDONESIA,
			self::INDIA,
			self::JAPAN,
			self::CAMBODIA,
			self::SOUTH_KOREA,
			self::SRI_LANKA,
			self::LAOS,
			self::MONGOLIA,
			self::MALAYSIA,
			self::NEPAL,
			self::THAILAND,
			self::TAIWAN,
			self::VIETNAM,
			self::PHILIPPINES,

			//Europese landen
			self::ALBANIA,
			self::ARMENIA,
			self::AUSTRIA,
			self::BELGIUM,
			self::BULGARIA,
			self::SWITZERLAND,
			self::CYPRUS,
			self::CZECH_REPUBLIC,
			self::GERMANY,
			self::DENMARK,
			self::SPAIN,
			self::ESTONIA,
			self::FINLAND,
			self::FRANCE,
			self::UNITED_KINGDOM,
			self::GEORGIA,
			self::GREECE,
			self::HUNGARY,
			self::CROATIA,
			self::IRELAND,
			self::ICELAND,
			self::ITALY,
			self::LIECHTENSTEIN,
			self::LITHUANIA,
			self::LUXEMBOURG,
			self::LATVIA,
			self::MOLDOVA,
			self::MALTA,
			self::MONTENEGRO,
			self::NETHERLANDS,
			self::NORWAY,
			self::POLAND,
			self::PORTUGAL,
			self::ROMANIA,
			self::SERBIA,
			self::SLOVAKIA,
			self::SLOVENIA,
			self::SWEDEN,
			self::TURKEY,
			self::KOSOVO,

			//Latijns-Amerikaanse landen
			self::ARGENTINA,
			self::BOLIVIA,
			self::BRAZIL,
			self::COLOMBIA,
			self::COSTA_RICA,
			self::ECUADOR,
			self::MEXICO,
			self::PERU => true,

			//Noord-Amerikaanse landen
			self::CANADA,
			self::GREENLAND,
			self::UNITED_STATES => true,
			default => false,

		};
	}

	public function world_basic(): bool {
		return match ( $this ) {
			//Afrikaanse landen
			self::BOTSWANA,
			self::GHANA,
			self::KENYA,
			self::TOGO,
			self::TANZANIA,
			self::UGANDA,
			self::SOUTH_AFRICA,

			//Aziatische landen
			self::INDONESIA,
			self::INDIA,
			self::SRI_LANKA,
			self::NEPAL,
			self::THAILAND,
			self::TAIWAN,
			self::VIETNAM,

			//Latijns-Amerikaanse landen
			self::ARGENTINA,
			self::BOLIVIA,
			self::BRAZIL,
			self::COSTA_RICA,
			self::ECUADOR,
			self::MEXICO,
			self::PERU => true,

			default => false,
		};
	}

	public static function filtered_list( Country_Context $context, bool $sort_by_label = true ): array {
		$list = array_map(
			fn( Labels $e ) => $e->label(),
			array_column( self::filter( $context ), null, 'value' )
		);

		if ( $sort_by_label ) {
			asort( $list );
		}
		return $list;
	}

	public static function filter( Country_Context $context ) {
		return array_filter(
			self::cases(),
			fn ( Country $network ): bool => $network->is_valid_for_context( $context )
		);
	}

	protected function is_valid_for_context( Country_Context $context ): bool {
		return (
			( Country_Context::ESC === $context && $this->esc() )
			||
			( Country_Context::WORKCAMPS === $context && $this->workcamps() )
			||
			( Country_Context::WORLD_BASIC === $context && $this->world_basic() )
		);
	}
}
