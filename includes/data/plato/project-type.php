<?php declare(strict_types=1);

namespace SIW\Data\Plato;

use SIW\Interfaces\Enums\Labels;

enum Project_Type: string implements Labels {
	case STV = 'STV';
	case MTV = 'MTV';
	case LTV = 'LTV';
	case TEEN = 'TEEN';
	case FAM = 'FAM';
	case VIRT = 'VIRT';
	case ESC = 'ESC';
	case PER = 'PER';
	case TRA = 'TRA';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::STV  => __( 'STV', 'siw' ),
			self::MTV  => __( 'MTV', 'siw' ),
			self::LTV  => __( 'LTV', 'siw' ),
			self::TEEN => __( 'Tienerproject', 'siw' ),
			self::FAM  => __( 'Familieproject', 'siw' ),
			self::VIRT => __( 'Virtueel project', 'siw' ),
			self::ESC  => __( 'ESC project', 'siw' ),
			self::PER  => __( 'Permanent project', 'siw' ),
			self::TRA  => __( 'Training', 'siw' ),
		};
	}
}
