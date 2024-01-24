<?php declare(strict_types=1);

namespace SIW\Data\Mailjet;

enum Operation: string {
	case MANAGE_LISTS           = 'contactslist';
	case SUBSCRIBE_USER_TO_LIST = 'contactslist/{{ list_id }}/managecontact';
	case CREATE_PROPERTY        = 'contactmetadata';
	case RETRIEVE_PROPERTIES    = 'contactmetadata';
}
