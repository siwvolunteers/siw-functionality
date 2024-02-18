<?php declare(strict_types=1); // phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded

namespace SIW\Data\Plato;

use SIW\Data\Language as Language_Entity;
use SIW\Interfaces\Enums\Labels;

enum Language: string {

	use Entity;

	case ARABIC     = 'ARA';
	case CATALAN    = 'CAT';
	case CHINESE    = 'CHN';
	case DANISH     = 'DNK';
	case GERMAN     = 'GER';
	case ENGLISH    = 'ENG';
	case ESTONIAN   = 'EST';
	case FINNISH    = 'FIN';
	case FRENCH     = 'FRA';
	case GREEK      = 'GRE';
	case HEBREW     = 'HEB';
	case ITALIAN    = 'ITA';
	case JAPANESE   = 'JAP';
	case KOREAN     = 'KOR';
	case DUTCH      = 'HOL';
	case UKRAINIAN  = 'UKR';
	case POLISH     = 'POL';
	case PORTUGUESE = 'POR';
	case RUSSIAN    = 'RUS';
	case SLOWAK     = 'SLK';
	case SPANISH    = 'ESP';
	case CZECH      = 'CZE';
	case TURKISH    = 'TUR';
	case SWEDISH    = 'SWE';

	public function to_entity(): Labels {
		return match ( $this ) {
			self::ARABIC     => Language_Entity::ARABIC,
			self::CATALAN    => Language_Entity::CATALAN,
			self::CHINESE    => Language_Entity::CHINESE,
			self::DANISH     => Language_Entity::DANISH,
			self::GERMAN     => Language_Entity::GERMAN,
			self::ENGLISH    => Language_Entity::ENGLISH,
			self::ESTONIAN   => Language_Entity::ESTONIAN,
			self::FINNISH    => Language_Entity::FINNISH,
			self::FRENCH     => Language_Entity::FRENCH,
			self::GREEK      => Language_Entity::GREEK,
			self::HEBREW     => Language_Entity::HEBREW,
			self::ITALIAN    => Language_Entity::ITALIAN,
			self::JAPANESE   => Language_Entity::JAPANESE,
			self::KOREAN     => Language_Entity::KOREAN,
			self::DUTCH      => Language_Entity::DUTCH,
			self::UKRAINIAN  => Language_Entity::UKRAINIAN,
			self::POLISH     => Language_Entity::POLISH,
			self::PORTUGUESE => Language_Entity::PORTUGUESE,
			self::RUSSIAN    => Language_Entity::RUSSIAN,
			self::SLOWAK     => Language_Entity::SLOWAK,
			self::SPANISH    => Language_Entity::SPANISH,
			self::CZECH      => Language_Entity::CZECH,
			self::TURKISH    => Language_Entity::TURKISH,
			self::SWEDISH    => Language_Entity::SWEDISH,
		};
	}
}
