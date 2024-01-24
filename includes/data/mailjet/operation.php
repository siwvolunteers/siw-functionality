<?php declare(strict_types=1);

namespace SIW\Data\Mailjet;

enum Operation: string {
	case MANAGE_LISTS           = 'contactslist';
	case CREATE_PROPERTY        = 'contactmetadata';
	case RETRIEVE_PROPERTIES    = 'contactmetadata';
}
