<?php declare(strict_types=1);

namespace SIW\Data\Mailjet;

enum Endpoint: string {
	case MANAGE_LISTS           = 'contactslist';
	case SUBSCRIBE_USER_TO_LIST = 'contactslist/{{ list_id }}/managecontact';
	case MANAGE_PROPERTIES        = 'contactmetadata';
}
