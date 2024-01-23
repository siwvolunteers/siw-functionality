<?php declare(strict_types=1);

namespace SIW\Data;

enum Post_Type_Support: string {

	// Core
	case TITLE = 'title';
	case EDITOR = 'editor';
	case AUTHOR = 'author';
	case THUMBNAIL = 'thumbnail';
	case EXCERPT = 'excerpt';
	case TRACKBACKS = 'trackbacks';
	case CUSTOM_FIELDS = 'custom-fields';
	case COMMENTS = 'comments';
	case REVISIONS = 'revisions';
	case PAGE_ATTRIBUTES = 'page-attributes';
	case POST_FORMATS = 'post-formats';

	// SIW-specifiek
	case CAROUSEL = 'siw-carousel';
	case SOCIAL_SHARE = 'siw-social-share';
}
