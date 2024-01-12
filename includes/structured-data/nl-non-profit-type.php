<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use SIW\Interfaces\Structured_Data\Non_Profit_Type;

/**
 * @see https://schema.org/NLNonprofitType
 */
enum NL_Non_Profit_Type: string implements Non_Profit_Type {
	case ANBI = 'NonprofitANBI';
	case SBBI = 'NonprofitSBBI';
}
