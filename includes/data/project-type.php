<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels as I_Enum_Labels;

/**
 * Project types
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
enum Project_Type: string implements I_Enum_Labels {

	case WORKCAMPS = 'workcamps';
	case ESC = 'esc';
	case SCHOOL_PROJECTS = 'school_projects';
	case WORLD_BASIC = 'world_basic';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::WORKCAMPS       => __( 'Groepsvrijwilligerswerk', 'siw' ),
			self::ESC             => __( 'ESC (European Solidarity Corps)', 'siw' ),
			self::SCHOOL_PROJECTS => __( 'Scholenprojecten', 'siw' ),
			self::WORLD_BASIC     => __( 'Wereld basis', 'siw' ),
		};
	}
}
