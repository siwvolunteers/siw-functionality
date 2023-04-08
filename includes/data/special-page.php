<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels as I_Enum_Labels;

/**
 * Special pagina's waarnaar verwezen kan worden
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
enum Special_Page:string implements I_Enum_Labels {

	case CONTACT = 'contact';
	case CHILD_POLICY = 'child_policy';
	case NEWSLETTER_CONFIRMATION = 'newsletter_confirmation';

	public function label(): string {
		return match ($this) {
			self::CONTACT                 => __( 'Contact', 'siw' ),
			self::CHILD_POLICY            => __( 'Kinderbeleid', 'siw' ),
			self::NEWSLETTER_CONFIRMATION => __( 'Bevestiging nieuwsbrief', 'siw' ),
		};
	}
}
