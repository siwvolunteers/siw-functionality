<?php declare(strict_types=1);

namespace SIW\Data\Elements\Link;

enum Rel: string {
	case ALTERNATE = 'alternate';
	case AUTHOR = 'author';
	case BOOKMARK = 'bookmark';
	case EXTERNAL = 'external';
	case HELP = 'help';
	case LICENSE = 'license';
	case NEXT = 'next';
	case NOFOLLOW = 'nofollow';
	case NOREFERRER = 'noreferrer';
	case NOOPENER = 'noopener';
	case PREV = 'prev';
	case SEARCH = 'search';
	case TAG = 'tag';

	case SPONSORED = 'sponsored';
	case UGC = 'ugc';
}
