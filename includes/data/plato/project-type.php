<?php declare(strict_types=1);

namespace SIW\Data\Plato;

/**
 * Plato project types
 *
 * @copyright 2021-2023 SIW Internationale Vrijwilligersprojecten
 */
enum Project_Type: string {

	case STV = 'STV';
	case MTV = 'MTV';
	case LTV = 'LTV';
	case TEEN = 'TEEN';
	case FAM = 'FAM';
	case VIRT = 'VIRT';
	case EVS = 'EVS';
	case PER = 'PER';
	case TRA = 'TRA';
}
