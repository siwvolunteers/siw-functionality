<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Colors;
use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Sustainable_Development_Goal: int implements Labels, Colors {
	use Enum_List;

	case GOAL_1 = 1;
	case GOAL_2 = 2;
	case GOAL_3 = 3;
	case GOAL_4 = 4;
	case GOAL_5 = 5;
	case GOAL_6 = 6;
	case GOAL_7 = 7;
	case GOAL_8 = 8;
	case GOAL_9 = 9;
	case GOAL_10 = 10;
	case GOAL_11 = 11;
	case GOAL_12 = 12;
	case GOAL_13 = 13;
	case GOAL_14 = 14;
	case GOAL_15 = 15;
	case GOAL_16 = 16;
	case GOAL_17 = 17;

	public function label(): string {
		return match ( $this ) {
			self::GOAL_1 => __( 'Geen armoede', 'siw' ),
			self::GOAL_2 => __( 'Geen honger', 'siw' ),
			self::GOAL_3 => __( 'Goede gezondheid', 'siw' ),
			self::GOAL_4 => __( 'Kwaliteitsonderwijs', 'siw' ),
			self::GOAL_5 => __( 'Geslachtsgelijkheid', 'siw' ),
			self::GOAL_6 => __( 'Schoon water en sanitaire voorzieningen', 'siw' ),
			self::GOAL_7 => __( 'Duurzame energie', 'siw' ),
			self::GOAL_8 => __( 'Goede banen werk en economische groei', 'siw' ),
			self::GOAL_9 => __( 'Innovatie en infastructuur', 'siw' ),
			self::GOAL_10 => __( 'Verminderde ongelijkheid', 'siw' ),
			self::GOAL_11 => __( 'Duurzame steden en gemeenschappen', 'siw' ),
			self::GOAL_12 => __( 'Verantwoorde consumptie', 'siw' ),
			self::GOAL_13 => __( 'Klimaatactie', 'siw' ),
			self::GOAL_14 => __( 'Leven onder het water', 'siw' ),
			self::GOAL_15 => __( 'Leven op het land', 'siw' ),
			self::GOAL_16 => __( 'Vrede en recht', 'siw' ),
			self::GOAL_17 => __( 'Partnerschappen voor de doelstellingen', 'siw' ),
		};
	}

	/** {@inheritDoc} */
	public function color(): string {
		return match ( $this ) {
			self::GOAL_1 => '#e5243b',
			self::GOAL_2 => '#dda63a',
			self::GOAL_3 => '#4c9f38',
			self::GOAL_4 => '#c5192d',
			self::GOAL_5 => '#ff3a21',
			self::GOAL_6 => '#26bde2',
			self::GOAL_7 => '#fcc30b',
			self::GOAL_8 => '#a21942',
			self::GOAL_9 => '#fd6925',
			self::GOAL_10 => '#dd1367',
			self::GOAL_11 => '#fd9d24',
			self::GOAL_12 => '#fd8b2e',
			self::GOAL_13 => '#3f7e44',
			self::GOAL_14 => '#0a97d9',
			self::GOAL_15 => '#56c02b',
			self::GOAL_16 => '#00689d',
			self::GOAL_17 => '#19486a',
		};
	}

	public function full_name(): string {
		return sprintf( '%d. %s', $this->value, $this->label() );
	}

	public function icon_class(): string {
		return sprintf( 'sdg-%d', $this->value );
	}
}
