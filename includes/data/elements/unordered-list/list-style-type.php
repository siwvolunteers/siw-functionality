<?php declare(strict_types=1);

namespace SIW\Data\Elements\Unordered_List;

enum List_Style_Type: string {
	case NONE = 'none';
	case DISC = 'disc';
	case CIRCLE = 'circle';
	case SQUARE = 'square';
	case CHECK = 'check';
}
