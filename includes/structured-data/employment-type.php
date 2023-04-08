<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * Soort baan
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 * @see https://developers.google.com/search/docs/data-types/job-posting
 */
enum Employment_Type {
	case EMPLOYMENT_TYPE_UNSPECIFIED;
	case FULL_TIME;
	case PART_TIME;
	case CONTRACTOR;
	case CONTRACT_TO_HIRE;
	case TEMPORARY;
	case INTERN;
	case VOLUNTEER;
	case PER_DIEM;
	case FLY_IN_FLY_OUT;
	case OTHER_EMPLOYMENT_TYPE;
}
