<?php declare(strict_types=1);

namespace SIW\Data\Features;

enum Cookie_Category: string {
	case NECESSARY = 'necessary';
	case ANALYTICAL = 'analytical';
	case MARKETING = 'marketing';

	public function enabled(): bool {
		return match ( $this ) {
			self::NECESSARY,
			self::ANALYTICAL => true,
			self::MARKETING => false,
		};
	}

	public function readonly(): bool {
		return match ( $this ) {
			self::NECESSARY,
			self::ANALYTICAL => true,
			self::MARKETING => false,
		};
	}
}
