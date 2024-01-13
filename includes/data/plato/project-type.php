<?php declare(strict_types=1);

namespace SIW\Data\Plato;

enum Project_Type: string {
	case STV = 'STV';
	case MTV = 'MTV';
	case LTV = 'LTV';
	case TEEN = 'TEEN';
	case FAM = 'FAM';
	case VIRT = 'VIRT';
	case ESC = 'ESC';
	case PER = 'PER';
	case TRA = 'TRA';
}
