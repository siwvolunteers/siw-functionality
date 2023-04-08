<?php declare(strict_types=1);

namespace SIW\Integrations\Google_Maps;

enum Location_Type: string {
	case ROOFTOP = 'ROOFTOP';
	case RANGE_INTERPOLATED = 'RANGE_INTERPOLATED';
	case GEOMETRIC_CENTER = 'GEOMETRIC_CENTER';
	case APPROXIMATE = 'APPROXIMATE';
}
