<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use SIW\Interfaces\Structured_Data\Enumeration as I_Enumeration;
use SIW\Interfaces\Structured_Data\Non_Profit_Type as I_Non_Profit_Type;

/**
 * Status van evenement
 *
 * @copyright 2021 SIW-2023 Internationale Vrijwilligersprojecten
 * @see https://schema.org/NLNonprofitType
 */
enum NL_Non_Profit_Type implements I_Enumeration, I_Non_Profit_Type {
	case NonprofitANBI;
	case NonprofitSBBI;
}
