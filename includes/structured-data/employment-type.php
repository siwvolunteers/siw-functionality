<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use \Spatie\Enum\Enum;

/**
 * Soort baan
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see https://developers.google.com/search/docs/data-types/job-posting
 *
 * @method static self EMPLOYMENT_TYPE_UNSPECIFIED()
 * @method static self FULL_TIME()
 * @method static self PART_TIME()
 * @method static self CONTRACTOR()
 * @method static self CONTRACT_TO_HIRE()
 * @method static self TEMPORARY()
 * @method static self INTERN()
 * @method static self VOLUNTEER()
 * @method static self PER_DIEM()
 * @method static self FLY_IN_FLY_OUT()
 * @method static self OTHER_EMPLOYMENT_TYPE()
 */
class Employment_Type extends Enum {

}
