<?php declare(strict_types=1); // phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded

namespace SIW\Data\Plato;

use SIW\Data\Work_Type as Work_Type_Entity;

enum Work_Type: string {

	use Entity;

	case AGRI = 'AGRI';
	case ANIM = 'ANIM';
	case ARCH = 'ARCH';
	case ART  = 'ART';
	case CHIL = 'CHIL';
	case KIDS = 'KIDS';
	case CONS = 'CONS';
	case CULT = 'CULT';
	case DISA = 'DISA';
	case EDU  = 'EDU';
	case EDUC = 'EDUC';
	case ELDE = 'ELDE';
	case FEST = 'FEST';
	case HERI = 'HERI';
	case LANG = 'LANG';
	case MANU = 'MANU';
	case ENVI = 'ENVI';
	case REFU = 'REFU';
	case RENO = 'RENO';
	case SOCI = 'SOCI';
	case SPOR = 'SPOR';
	case STUD = 'STUD';
	case YOGA = 'YOGA';

	public function to_entity(): Work_Type_Entity {
		return match ( $this ) {
			self::AGRI => Work_Type_Entity::AGRICULTURE,
			self::ANIM => Work_Type_Entity::ANIMALS,
			self::ARCH => Work_Type_Entity::ARCHEOLOGY,
			self::ART  => Work_Type_Entity::ART,
			self::KIDS,
			self::CHIL => Work_Type_Entity::CHILDREN,
			self::CONS => Work_Type_Entity::CONSTRUCTION,
			self::CULT => Work_Type_Entity::CULTURE,
			self::DISA => Work_Type_Entity::DISABILITIES,
			self::EDU,
			self::EDUC  => Work_Type_Entity::EDUCATION,
			self::ELDE => Work_Type_Entity::ELDERLY,
			self::FEST => Work_Type_Entity::FESTIVAL,
			self::HERI => Work_Type_Entity::HERITAGE,
			self::LANG => Work_Type_Entity::LANGUAGE,
			self::MANU => Work_Type_Entity::MANUAL_WORK,
			self::ENVI => Work_Type_Entity::NATURE,
			self::REFU => Work_Type_Entity::REFUGEES,
			self::RENO => Work_Type_Entity::RENOVATION,
			self::SOCI => Work_Type_Entity::SOCIAL,
			self::SPOR => Work_Type_Entity::SPORT,
			self::STUD => Work_Type_Entity::STUDY_THEME,
			self::YOGA => Work_Type_Entity::YOGA,
		};
	}
}
