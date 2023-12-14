<?php declare(strict_types=1);

namespace SIW\Elements;

enum List_Style_Type: string {
	case NONE = 'none';
	case DISC = 'disc';
	case CIRCLE = 'circle';
	case SQUARE = 'square';
	case CHECK = 'check';
}
