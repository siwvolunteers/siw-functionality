<?php declare(strict_types=1);

namespace SIW\Integrations\Mailjet;

enum Data_Type: string {
	case STRING = 'str';
	case INTEGER = 'int';
	case FLOAT = 'float';
	case BOOLEAN = 'bool';
	case DATETIME = 'datetime';
}
