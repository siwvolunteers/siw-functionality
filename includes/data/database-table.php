<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;

/**
 * Database-tabellen
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
enum Database_Table: string implements Labels {

	case PLATO_PROJECTS = 'plato_projects';
	case PLATO_PROJECT_FREE_PLACES = 'plato_project_free_places';
	case PLATO_PROJECT_IMAGES      = 'plato_project_images';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::PLATO_PROJECTS            => 'Plato projecten',
			self::PLATO_PROJECT_FREE_PLACES => 'Plato project vrije plaatsen',
			self::PLATO_PROJECT_IMAGES      => 'Plato projectafbeeldingen',
		};
	}
}
