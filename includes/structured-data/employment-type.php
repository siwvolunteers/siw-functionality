<?php declare(strict_types=1);

namespace SIW\Structured_Data;

/**
 * @see https://developers.google.com/search/docs/data-types/job-posting
 */

enum Employment_Type: string {
	case UNSPECIFIED = 'EMPLOYMENT_TYPE_UNSPECIFIED';
	case FULL_TIME = 'FULL_TIME';
	case PART_TIME = 'PART_TIME';
	case CONTRACTOR = 'CONTRACTOR';
	case CONTRACT_TO_HIRE = 'CONTRACT_TO_HIRE';
	case TEMPORARY = 'TEMPORARY';
	case INTERN = 'INTERN';
	case VOLUNTEER = 'VOLUNTEER';
	case PER_DIEM = 'PER_DIEM';
	case FLY_IN_FLY_OUT = 'FLY_IN_FLY_OUT';
	case OTHER_EMPLOYMENT_TYPE = 'OTHER';
}
