<?php declare(strict_types=1);

namespace SIW\Integrations\Google_Maps;

enum Endpoint: string {
	case GEOCODING = 'geocode';
	case PLACE_DETAILS = 'place/details';
}
