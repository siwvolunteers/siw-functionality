<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Colors;
use SIW\Interfaces\Enums\Labels;

enum Color: string implements Labels, Colors {
	case ACCENT = 'siw-accent';
	case CONTRAST = 'siw-contrast';
	case CONTRAST_LIGHT = 'siw-contrast-light';
	case BASE = 'siw-base';
	case PURPLE = 'siw-purple';
	case BLUE = 'siw-blue';
	case RED = 'siw-red';
	case GREEN = 'siw-green';
	case YELLOW = 'siw-yellow';

	#[\Override]
	public function label(): string {
		return match ( $this ) {
			self::ACCENT => 'Accent',
			self::CONTRAST => 'Contrast',
			self::CONTRAST_LIGHT => 'Contrast licht',
			self::BASE => 'Basis',
			self::PURPLE => 'Paars',
			self::BLUE => 'Blauw',
			self::RED => 'Rood',
			self::GREEN => 'Groen',
			self::YELLOW => 'Geel',
		};
	}

	#[\Override]
	public function color(): string {
		return match ( $this ) {
			self::ACCENT => '#f67820',
			self::CONTRAST => '#222',
			self::CONTRAST_LIGHT => '#555',
			self::BASE => '#fefefe',
			self::PURPLE => '#623981',
			self::BLUE => '#67bdd3',
			self::RED => '#e74052',
			self::GREEN => '#7fc31b',
			self::YELLOW => '#f4f416',
		};
	}
}
