<?php declare(strict_types=1); // phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded

namespace SIW\Data\Plato;

use SIW\Data\Country as Country_Entity;

enum Country: string {

	use Entity;

	// Afrikaanse landen
	case BURUNDI = 'BDI';
	case BOTSWANA = 'BWA';
	case GHANA = 'GHA';
	case KENYA = 'KEN';
	case MADAGASCAR = 'MDG';
	case MOROCCO = 'MAR';
	case MOZAMBIQUE = 'MOZ';
	case SENEGAL = 'SEN';
	case TOGO = 'TGO';
	case TUNISIA = 'TUN';
	case TANZANIA = 'TZA';
	case UGANDA = 'UGA';
	case SOUTH_AFRICA = 'ZAF';
	case ZAMBIA = 'ZMB';
	case ZIMBABWE = 'ZWE';

	// Aziatische landen
	case CHINA = 'CHN';
	case HONG_KONG = 'HKG';
	case INDONESIA = 'IDN';
	case INDIA = 'IND';
	case JAPAN = 'JPN';
	case KYRGYZSTAN = 'KGZ';
	case CAMBODIA = 'KHM';
	case SOUTH_KOREA = 'KOR';
	case SRI_LANKA = 'LKA';
	case LAOS = 'LAO';
	case MONGOLIA = 'MNG';
	case MALAYSIA = 'MYS';
	case NEPAL = 'NPL';
	case THAILAND = 'THA';
	case TAIWAN = 'TWN';
	case VIETNAM = 'VNM';
	case PHILIPPINES = 'PHL';

	// Europese landen
	case ALBANIA = 'ALB';
	case ARMENIA = 'ARM';
	case AUSTRIA = 'AUT';
	case BELGIUM = 'BEL';
	case BULGARIA = 'BGR';
	case BELARUS = 'BLR';
	case SWITZERLAND = 'CHE';
	case CYPRUS = 'CYP';
	case CZECH_REPUBLIC = 'CZE';
	case GERMANY = 'DEU';
	case DENMARK = 'DNK';
	case SPAIN = 'ESP';
	case ESTONIA = 'EST';
	case FINLAND = 'FIN';
	case FRANCE = 'FRA';
	case UNITED_KINGDOM = 'GBR';
	case GEORGIA = 'GEO';
	case GREECE = 'GRC';
	case HUNGARY = 'HUN';
	case CROATIA = 'HRV';
	case IRELAND = 'IRL';
	case ICELAND = 'ISL';
	case ITALY = 'ITA';
	case LIECHTENSTEIN = 'LIE';
	case LITHUANIA = 'LTU';
	case LUXEMBOURG = 'LUX';
	case LATVIA = 'LVA';
	case MOLDOVA = 'MDA';
	case MACEDONIA = 'MKD';
	case MALTA = 'MLT';
	case MONTENEGRO = 'MNE';
	case NETHERLANDS = 'NLD';
	case NORWAY = 'NOR';
	case POLAND = 'POL';
	case PORTUGAL = 'PRT';
	case ROMANIA = 'ROU';
	case RUSSIA = 'RUS';
	case SERBIA = 'SRB';
	case SLOVAKIA = 'SVK';
	case SLOVENIA = 'SVN';
	case SWEDEN = 'SWE';
	case TURKEY = 'TUR';
	case UKRAINE = 'UKR';
	case KOSOVO = 'XKS';

	//Latijns-Amerikaanse landen
	case ARUBA = 'ABW';
	case ARGENTINA = 'ARG';
	case BOLIVIA = 'BOL';
	case BRAZIL = 'BRA';
	case COLOMBIA = 'COL';
	case COSTA_RICA = 'CRI';
	case ECUADOR = 'ECU';
	case GUATEMALA = 'GTM';
	case HAITI = 'HTE';
	case MEXICO = 'MEX';
	case PERU = 'PER';

	// Noord-Amerikaanse landen
	case CANADA = 'CAN';
	case GREENLAND = 'GRL';
	case UNITED_STATES = 'USA';

	public function to_entity(): Country_Entity {
		return match ( $this ) {
			// Afrikaanse landen
			self::BURUNDI => Country_Entity::BURUNDI,
			self::BOTSWANA => Country_Entity::BOTSWANA,
			self::GHANA => Country_Entity::GHANA,
			self::KENYA => Country_Entity::KENYA,
			self::MADAGASCAR => Country_Entity::MADAGASCAR,
			self::MOROCCO => Country_Entity::MOROCCO,
			self::MOZAMBIQUE => Country_Entity::MOZAMBIQUE,
			self::SENEGAL => Country_Entity::SENEGAL,
			self::TOGO => Country_Entity::TOGO,
			self::TUNISIA => Country_Entity::TUNISIA,
			self::TANZANIA => Country_Entity::TANZANIA,
			self::UGANDA => Country_Entity::UGANDA,
			self::SOUTH_AFRICA => Country_Entity::SOUTH_AFRICA,
			self::ZAMBIA => Country_Entity::ZAMBIA,
			self::ZIMBABWE => Country_Entity::ZIMBABWE,

			// Aziatische landen
			self::CHINA => Country_Entity::CHINA,
			self::HONG_KONG => Country_Entity::HONG_KONG,
			self::INDONESIA => Country_Entity::INDONESIA,
			self::INDIA => Country_Entity::INDIA,
			self::JAPAN => Country_Entity::JAPAN,
			self::KYRGYZSTAN => Country_Entity::KYRGYZSTAN,
			self::CAMBODIA => Country_Entity::CAMBODIA,
			self::SOUTH_KOREA => Country_Entity::SOUTH_KOREA,
			self::SRI_LANKA => Country_Entity::SRI_LANKA,
			self::LAOS => Country_Entity::LAOS,
			self::MONGOLIA => Country_Entity::MONGOLIA,
			self::MALAYSIA => Country_Entity::MALAYSIA,
			self::NEPAL => Country_Entity::NEPAL,
			self::THAILAND => Country_Entity::THAILAND,
			self::TAIWAN => Country_Entity::TAIWAN,
			self::VIETNAM => Country_Entity::VIETNAM,
			self::PHILIPPINES => Country_Entity::PHILIPPINES,

			// Europese landen
			self::ALBANIA => Country_Entity::ALBANIA,
			self::ARMENIA => Country_Entity::ARMENIA,
			self::AUSTRIA => Country_Entity::AUSTRIA,
			self::BELGIUM => Country_Entity::BELARUS,
			self::BULGARIA => Country_Entity::BULGARIA,
			self::BELARUS => Country_Entity::BELARUS,
			self::SWITZERLAND => Country_Entity::SWITZERLAND,
			self::CYPRUS => Country_Entity::CYPRUS,
			self::CZECH_REPUBLIC => Country_Entity::CZECH_REPUBLIC,
			self::GERMANY => Country_Entity::GERMANY,
			self::DENMARK => Country_Entity::DENMARK,
			self::SPAIN => Country_Entity::SPAIN,
			self::ESTONIA => Country_Entity::ESTONIA,
			self::FINLAND => Country_Entity::FINLAND,
			self::FRANCE => Country_Entity::FRANCE,
			self::UNITED_KINGDOM => Country_Entity::UNITED_KINGDOM,
			self::GEORGIA => Country_Entity::GEORGIA,
			self::GREECE => Country_Entity::GREECE,
			self::HUNGARY => Country_Entity::HUNGARY,
			self::CROATIA => Country_Entity::CROATIA,
			self::IRELAND => Country_Entity::IRELAND,
			self::ICELAND => Country_Entity::ICELAND,
			self::ITALY => Country_Entity::ITALY,
			self::LIECHTENSTEIN => Country_Entity::LIECHTENSTEIN,
			self::LITHUANIA => Country_Entity::LITHUANIA,
			self::LUXEMBOURG => Country_Entity::LUXEMBOURG,
			self::LATVIA => Country_Entity::LATVIA,
			self::MOLDOVA => Country_Entity::MOLDOVA,
			self::MACEDONIA => Country_Entity::MACEDONIA,
			self::MALTA => Country_Entity::MALTA,
			self::MONTENEGRO => Country_Entity::MONTENEGRO,
			self::NETHERLANDS => Country_Entity::NETHERLANDS,
			self::NORWAY => Country_Entity::NORWAY,
			self::POLAND => Country_Entity::POLAND,
			self::PORTUGAL => Country_Entity::PORTUGAL,
			self::ROMANIA => Country_Entity::ROMANIA,
			self::RUSSIA => Country_Entity::RUSSIA,
			self::SERBIA => Country_Entity::SERBIA,
			self::SLOVAKIA => Country_Entity::SLOVAKIA,
			self::SLOVENIA => Country_Entity::SLOVENIA,
			self::SWEDEN => Country_Entity::SWEDEN,
			self::TURKEY => Country_Entity::TURKEY,
			self::UKRAINE => Country_Entity::UKRAINE,
			self::KOSOVO => Country_Entity::KOSOVO,

			//Latijns-Amerikaanse landen
			self::ARUBA => Country_Entity::ARUBA,
			self::ARGENTINA => Country_Entity::ARGENTINA,
			self::BOLIVIA => Country_Entity::BOLIVIA,
			self::BRAZIL => Country_Entity::BRAZIL,
			self::COLOMBIA => Country_Entity::COLOMBIA,
			self::COSTA_RICA => Country_Entity::COSTA_RICA,
			self::ECUADOR => Country_Entity::ECUADOR,
			self::GUATEMALA => Country_Entity::GUATEMALA,
			self::HAITI => Country_Entity::HAITI,
			self::MEXICO => Country_Entity::MEXICO,
			self::PERU => Country_Entity::PERU,

			// Noord-Amerikaanse landen
			self::CANADA => Country_Entity::CANADA,
			self::GREENLAND => Country_Entity::GREENLAND,
			self::UNITED_STATES => Country_Entity::UNITED_STATES,
		};
	}
}
