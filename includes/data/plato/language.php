<?php declare(strict_types=1); // phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded

namespace SIW\Data\Plato;

use SIW\Data\Language as Language_Entity;

enum Language: string {

	use Entity;

	case ARA = 'ARA';
	case CAT = 'CAT';
	case CHN = 'CHN';
	case DNK = 'DNK';
	case GER = 'GER';
	case ENG = 'ENG';
	case EST = 'EST';
	case FIN = 'FIN';
	case FRA = 'FRA';
	case GRE = 'GRE';
	case HEB = 'HEB';
	case ITA = 'ITA';
	case JAP = 'JAP';
	case KOR = 'KOR';
	case HOL = 'HOL';
	case UKR = 'UKR';
	case POL = 'POL';
	case POR = 'POR';
	case RUS = 'RUS';
	case SLK = 'SLK';
	case ESP = 'ESP';
	case CZE = 'CZE';
	case TUR = 'TUR';
	case SWE = 'SWE';

	public function to_entity(): Language_Entity {
		return match ( $this ) {
			self::ARA => Language_Entity::ARABIC,
			self::CAT => Language_Entity::CATALAN,
			self::CHN => Language_Entity::CHINESE,
			self::DNK => Language_Entity::DANISH,
			self::GER => Language_Entity::GERMAN,
			self::ENG => Language_Entity::ENGLISH,
			self::EST => Language_Entity::ESTONIAN,
			self::FIN => Language_Entity::FINNISH,
			self::FRA => Language_Entity::FRENCH,
			self::GRE => Language_Entity::GREEK,
			self::HEB => Language_Entity::HEBREW,
			self::ITA => Language_Entity::ITALIAN,
			self::JAP => Language_Entity::JAPANESE,
			self::KOR => Language_Entity::KOREAN,
			self::HOL => Language_Entity::DUTCH,
			self::UKR => Language_Entity::UKRAINIAN,
			self::POL => Language_Entity::POLISH,
			self::POR => Language_Entity::PORTUGUESE,
			self::RUS => Language_Entity::RUSSIAN,
			self::SLK => Language_Entity::SLOWAK,
			self::ESP => Language_Entity::SPANISH,
			self::CZE => Language_Entity::CZECH,
			self::TUR => Language_Entity::TURKISH,
			self::SWE => Language_Entity::SWEDISH,
		};
	}
}
