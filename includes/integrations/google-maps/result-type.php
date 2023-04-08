<?php declare(strict_types=1);

namespace SIW\Integrations\Google_Maps;

enum Result_Type: string {
	case STREET_ADDRESS = 'street_address';
	case ROUTE = 'route';
	case INTERSECTION = 'intersection';
	case POLITICAL = 'political';
	case COUNTRY = 'country';
	case ADMINISTRATIVE_AREA_LEVEL_1 = 'administrative_area_level_1';
	case ADMINISTRATIVE_AREA_LEVEL_2 = 'administrative_area_level_2';
	case ADMINISTRATIVE_AREA_LEVEL_3 = 'administrative_area_level_3';
	case ADMINISTRATIVE_AREA_LEVEL_4 = 'administrative_area_level_4';
	case ADMINISTRATIVE_AREA_LEVEL_5 = 'administrative_area_level_5';
	case COLLOQUIAL_AREA = 'colloquial_area';
	case LOCALITY = 'locality';
	case SUBLOCALITY = 'sublocality';
	case NEIGHBORHOOD = 'neighborhood';
	case PREMISE = 'premise';
	case SUBPREMISE = 'subpremise';
	case PLUS_CODE = 'plus_code';
	case POSTAL_CODE = 'postal_code';
	case NATURAL_FEATURE = 'natural_feature';
	case AIRPORT = 'airport';
	case PARK = 'park';
	case POINT_OF_INTEREST = 'point_of_interest';
}
