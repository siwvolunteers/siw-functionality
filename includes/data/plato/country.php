<?php declare(strict_types=1); // phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded

namespace SIW\Data\Plato;

use SIW\Data\Country as Country_Entity;

enum Country: string {

	use Entity;

	// Afrikaanse landen
	case BDI = 'BDI';
	case BWA = 'BWA';
	case GHA = 'GHA';
	case KEN = 'KEN';
	case MDG = 'MDG';
	case MAR = 'MAR';
	case MOZ = 'MOZ';
	case SEN = 'SEN';
	case TGP = 'TGO';
	case TUN = 'TUN';
	case TZA = 'TZA';
	case UGA = 'UGA';
	case ZAF = 'ZAF';
	case ZMB = 'ZMB';
	case ZWE = 'ZWE';

	// Aziatische landen
	case CHN = 'CHN';
	case HKG = 'HKG';
	case IDN = 'IDN';
	case IND = 'IND';
	case JPN = 'JPN';
	case KGZ = 'KGZ';
	case KHM = 'KHM';
	case KOR = 'KOR';
	case LKA = 'LKA';
	case LAO = 'LAO';
	case MNG = 'MNG';
	case MYS = 'MYS';
	case NPL = 'NPL';
	case THA = 'THA';
	case TWN = 'TWN';
	case VNM = 'VNM';
	case PHL = 'PHL';

	// Europese landen
	case ALB = 'ALB';
	case ARM = 'ARM';
	case AUT = 'AUT';
	case BEL = 'BEL';
	case BGR = 'BGR';
	case BLR = 'BLR';
	case CHE = 'CHE';
	case CYP = 'CYP';
	case CZE = 'CZE';
	case DEU = 'DEU';
	case DNK = 'DNK';
	case ESP = 'ESP';
	case EST = 'EST';
	case FIN = 'FIN';
	case FRA = 'FRA';
	case GBR = 'GBR';
	case GEO = 'GEO';
	case GRC = 'GRC';
	case HUN = 'HUN';
	case HRV = 'HRV';
	case IRL = 'IRL';
	case ISL = 'ISL';
	case ITA = 'ITA';
	case LIE = 'LIE';
	case LTU = 'LTU';
	case LUX = 'LUX';
	case LVA = 'LVA';
	case MDA = 'MDA';
	case MKD = 'MKD';
	case MLT = 'MLT';
	case MNE = 'MNE';
	case NLD = 'NLD';
	case NOR = 'NOR';
	case POL = 'POL';
	case PRT = 'PRT';
	case ROU = 'ROU';
	case RUS = 'RUS';
	case SRB = 'SRB';
	case SVK = 'SVK';
	case SVN = 'SVN';
	case SWE = 'SWE';
	case TUR = 'TUR';
	case UKR = 'UKR';
	case XKS = 'XKS';

	//Latijns-Amerikaanse landen
	case ABW = 'ABW';
	case ARG = 'ARG';
	case BOL = 'BOL';
	case BRA = 'BRA';
	case COL = 'COL';
	case CRI = 'CRI';
	case ECU = 'ECU';
	case GTM = 'GTM';
	case HTE = 'HTE';
	case MEX = 'MEX';
	case PER = 'PER';

	// Noord-Amerikaanse landen
	case CAN = 'CAN';
	case GRL = 'GRL';
	case USA = 'USA';

	public function to_entity(): Country_Entity {
		return match ( $this ) {
			// Afrikaanse landen
			self::BDI => Country_Entity::BURUNDI,
			self::BWA => Country_Entity::BOTSWANA,
			self::GHA => Country_Entity::GHANA,
			self::KEN => Country_Entity::KENYA,
			self::MDG => Country_Entity::MADAGASCAR,
			self::MAR => Country_Entity::MOROCCO,
			self::MOZ => Country_Entity::MOZAMBIQUE,
			self::SEN => Country_Entity::SENEGAL,
			self::TGP => Country_Entity::TOGO,
			self::TUN => Country_Entity::TUNISIA,
			self::TZA => Country_Entity::TANZANIA,
			self::UGA => Country_Entity::UGANDA,
			self::ZAF => Country_Entity::SOUTH_AFRICA,
			self::ZMB => Country_Entity::ZAMBIA,
			self::ZWE => Country_Entity::ZIMBABWE,

			// Aziatische landen
			self::CHN => Country_Entity::CHINA,
			self::HKG => Country_Entity::HONG_KONG,
			self::IDN => Country_Entity::INDONESIA,
			self::IND => Country_Entity::INDIA,
			self::JPN => Country_Entity::JAPAN,
			self::KGZ => Country_Entity::KYRGYZSTAN,
			self::KHM => Country_Entity::CAMBODIA,
			self::KOR => Country_Entity::SOUTH_KOREA,
			self::LKA => Country_Entity::SRI_LANKA,
			self::LAO => Country_Entity::LAOS,
			self::MNG => Country_Entity::MONGOLIA,
			self::MYS => Country_Entity::MALAYSIA,
			self::NPL => Country_Entity::NEPAL,
			self::THA => Country_Entity::THAILAND,
			self::TWN => Country_Entity::TAIWAN,
			self::VNM => Country_Entity::VIETNAM,
			self::PHL => Country_Entity::PHILIPPINES,

			// Europese landen
			self::ALB => Country_Entity::ALBANIA,
			self::ARM => Country_Entity::ARMENIA,
			self::AUT => Country_Entity::AUSTRIA,
			self::BEL => Country_Entity::BELARUS,
			self::BGR => Country_Entity::BULGARIA,
			self::BLR => Country_Entity::BELARUS,
			self::CHE => Country_Entity::SWITZERLAND,
			self::CYP => Country_Entity::CYPRUS,
			self::CZE => Country_Entity::CZECH_REPUBLIC,
			self::DEU => Country_Entity::GERMANY,
			self::DNK => Country_Entity::DENMARK,
			self::ESP => Country_Entity::SPAIN,
			self::EST => Country_Entity::ESTONIA,
			self::FIN => Country_Entity::FINLAND,
			self::FRA => Country_Entity::FRANCE,
			self::GBR => Country_Entity::UNITED_KINGDOM,
			self::GEO => Country_Entity::GEORGIA,
			self::GRC => Country_Entity::GREECE,
			self::HUN => Country_Entity::HUNGARY,
			self::HRV => Country_Entity::CROATIA,
			self::IRL => Country_Entity::IRELAND,
			self::ISL => Country_Entity::ICELAND,
			self::ITA => Country_Entity::ITALY,
			self::LIE => Country_Entity::LIECHTENSTEIN,
			self::LTU => Country_Entity::LITHUANIA,
			self::LUX => Country_Entity::LUXEMBOURG,
			self::LVA => Country_Entity::LATVIA,
			self::MDA => Country_Entity::MOLDOVA,
			self::MKD => Country_Entity::MACEDONIA,
			self::MLT => Country_Entity::MALTA,
			self::MNE => Country_Entity::MONTENEGRO,
			self::NLD => Country_Entity::NETHERLANDS,
			self::NOR => Country_Entity::NORWAY,
			self::POL => Country_Entity::POLAND,
			self::PRT => Country_Entity::PORTUGAL,
			self::ROU => Country_Entity::ROMANIA,
			self::RUS => Country_Entity::RUSSIA,
			self::SRB => Country_Entity::SERBIA,
			self::SVK => Country_Entity::SLOVAKIA,
			self::SVN => Country_Entity::SLOVENIA,
			self::SWE => Country_Entity::SWEDEN,
			self::TUR => Country_Entity::TURKEY,
			self::UKR => Country_Entity::UKRAINE,
			self::XKS => Country_Entity::KOSOVO,

			//Latijns-Amerikaanse landen
			self::ABW => Country_Entity::ARUBA,
			self::ARG => Country_Entity::ARGENTINA,
			self::BOL => Country_Entity::BOLIVIA,
			self::BRA => Country_Entity::BRAZIL,
			self::COL => Country_Entity::COLOMBIA,
			self::CRI => Country_Entity::COSTA_RICA,
			self::ECU => Country_Entity::ECUADOR,
			self::GTM => Country_Entity::GUATEMALA,
			self::HTE => Country_Entity::HAITI,
			self::MEX => Country_Entity::MEXICO,
			self::PER => Country_Entity::PERU,

			// Noord-Amerikaanse landen
			self::CAN => Country_Entity::CANADA,
			self::GRL => Country_Entity::GREENLAND,
			self::USA => Country_Entity::UNITED_STATES,
		};
	}
}
