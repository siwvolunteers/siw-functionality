<?php declare(strict_types=1);

namespace SIW\Data;

enum Visibility_Class: string {
	case HIDE_ON_MOBILE = 'hide-on-mobile';
	case HIDE_ON_TABLET = 'hide-on-tablet';
	case HIDE_ON_DESKTOP = 'hide-on-desktop';
}
