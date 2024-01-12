<?php declare(strict_types=1);

namespace SIW\Util;

use luizbills\CSS_Generator\Generator;

/**
 * Hulpfuncties t.b.v. css
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class CSS {

	/** Breakpoint voor mobiel (max-width) */
	public const MOBILE_BREAKPOINT = 768;

	/** Breakpoint voor tablet (max-width) */
	public const TABLET_BREAKPOINT = 1024;

	/** CSS klasse om content op mobiel te verbergen */
	public const HIDE_ON_MOBILE_CLASS = 'hide-on-mobile';

	/** CSS klasse om content op tablet te verbergen */
	public const HIDE_ON_TABLET_CLASS = 'hide-on-tablet';

	/** CSS klasse om content op desktop te verbergen */
	public const HIDE_ON_DESKTOP_CLASS = 'hide-on-desktop';

	/** Accentkleur */
	public const ACCENT_COLOR = '#f67820';

	/** Contrastkleur (tekst) */
	public const CONTRAST_COLOR = '#222';

	/** Contrastkleur licht (tekst) */
	public const CONTRAST_COLOR_LIGHT = '#555';

	/** Basekleur (achtergrondkleur) */
	public const BASE_COLOR = '#fefefe';

	/** Extra kleuren o.a. voor de continenten */
	public const PURPLE_COLOR = '#623981';
	public const BLUE_COLOR = '#67bdd3';
	public const RED_COLOR = '#e74052';
	public const GREEN_COLOR = '#7fc31b';
	public const YELLOW_COLOR = '#f4f416';

	public static function get_css_generator(): ?Generator {
		return new Generator();
	}

	/** Geeft thema-kleuren terug */
	public static function get_colors(): array {
		return [
			[
				'name'  => 'Accent',
				'slug'  => 'siw-accent',
				'color' => self::ACCENT_COLOR,
			],
			[
				'name'  => 'Contrast',
				'slug'  => 'siw-contrast',
				'color' => self::CONTRAST_COLOR,
			],
			[
				'name'  => 'Contrast 2',
				'slug'  => 'siw-contrast-light',
				'color' => self::CONTRAST_COLOR_LIGHT,
			],
			[
				'name'  => 'Base',
				'slug'  => 'siw-base',
				'color' => self::BASE_COLOR,
			],
			[
				'name'  => 'Paars',
				'slug'  => 'siw-purple',
				'color' => self::PURPLE_COLOR,
			],
			[
				'name'  => 'Blauw',
				'slug'  => 'siw-blue',
				'color' => self::BLUE_COLOR,
			],
			[
				'name'  => 'Rood',
				'slug'  => 'siw-red',
				'color' => self::RED_COLOR,
			],
			[
				'name'  => 'Groen',
				'slug'  => 'siw-green',
				'color' => self::GREEN_COLOR,
			],
			[
				'name'  => 'Geel',
				'slug'  => 'siw-yellow',
				'color' => self::YELLOW_COLOR,
			],
		];
	}
}
