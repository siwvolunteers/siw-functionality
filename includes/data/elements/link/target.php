<?php declare(strict_types=1);

namespace SIW\Data\Elements\Link;

enum Target: string {
	case BLANK = '_blank';
	case SELF = '_self';
	case PARENT = '_parent';
	case TOP = '_top';
}
