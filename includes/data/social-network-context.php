<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;

/**
 * Context voor sociale netwerken
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
enum Social_Network_Context:string implements Labels {
	case SHARE = 'share';
	case FOLLOW = 'follow';

	/** {@inheritDoc} */
	public function label(): string {
		return match ($this) {
			self::SHARE  =>  __( 'Delen', 'siw' ),
			self::FOLLOW =>  __( 'Volgen', 'siw' ),
		};
	}
}
