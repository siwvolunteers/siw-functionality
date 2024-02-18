<?php declare(strict_types=1); // phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Work_Type: string implements Labels {

	use Enum_List;

	case AGRICULTURE = 'landbouw';
	case ANIMALS = 'dieren';
	case ARCHEOLOGY = 'archeologie';
	case ART = 'kunst';
	case CHILDREN = 'kinderen';
	case CONSTRUCTION = 'constructie';
	case CULTURE = 'cultuur';
	case DISABILITIES = 'gehandicapten';
	case EDUCATION = 'onderwijs';
	case ELDERLY = 'ouderen';
	case FESTIVAL = 'festival';
	case HERITAGE = 'erfgoed';
	case LANGUAGE = 'taalcursus';
	case MANUAL_WORK = 'handarbeid';
	case NATURE = 'natuur';
	case REFUGEES = 'vluchtelingen';
	case RENOVATION = 'restauratie';
	case SOCIAL = 'sociaal';
	case SPORT = 'sport';
	case STUDY_THEME = 'thema';
	case YOGA = 'yoga';

	public function label(): string {
		return match ( $this ) {
			self::AGRICULTURE  => __( 'Landbouw', 'siw' ),
			self::ANIMALS      => __( 'Dieren', 'siw' ),
			self::ARCHEOLOGY   => __( 'Archeologie', 'siw' ),
			self::ART          => __( 'Kunst', 'siw' ),
			self::CHILDREN     => __( 'Kinderen', 'siw' ),
			self::CONSTRUCTION => __( 'Constructie', 'siw' ),
			self::CULTURE      => __( 'Cultuur', 'siw' ),
			self::DISABILITIES => __( 'Gehandicapten', 'siw' ),
			self::EDUCATION    => __( 'Onderwijs', 'siw' ),
			self::ELDERLY      => __( 'Ouderen', 'siw' ),
			self::FESTIVAL     => __( 'Festival', 'siw' ),
			self::HERITAGE     => __( 'Erfgoed', 'siw' ),
			self::LANGUAGE     => __( 'Taalcursus', 'siw' ),
			self::MANUAL_WORK  => __( 'Handarbeid', 'siw' ),
			self::NATURE       => __( 'Natuur', 'siw' ),
			self::REFUGEES     => __( 'Vluchtelingen', 'siw' ),
			self::RENOVATION   => __( 'Restauratie', 'siw' ),
			self::SOCIAL       => __( 'Sociaal', 'siw' ),
			self::SPORT        => __( 'Sport', 'siw' ),
			self::STUDY_THEME  => __( 'Thema', 'siw' ),
			self::YOGA         => __( 'Yoga', 'siw' ),
		};
	}

	public function needs_review(): bool {
		return match ( $this ) {
			self::CHILDREN,
			self::EDUCATION => true,
			default => false
		};
	}
}
