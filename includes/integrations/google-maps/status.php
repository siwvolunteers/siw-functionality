<?php declare(strict_types=1);

namespace SIW\Integrations\Google_Maps;

enum Status: string {
	case OK = 'OK';
	case ZERO_RESULTS = 'ZERO_RESULTS';
	case OVER_DAILY_LIMIT = 'OVER_DAILY_LIMIT';
	case OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';
	case REQUEST_DENIED = 'REQUEST_DENIED';
	case INVALID_REQUEST = 'INVALID_REQUEST';
	case UNKNOWN_ERROR = 'UNKNOWN_ERROR';
}


