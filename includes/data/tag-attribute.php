<?php declare(strict_types=1);

namespace SIW\Data;

enum Tag_Attribute: string {
	case CROSSORIGIN = 'crossorigin';
	case INTEGRITY = 'integrity';
	case TYPE = 'type';
	case COOKIE_CATEGORY = 'data-category';
}
