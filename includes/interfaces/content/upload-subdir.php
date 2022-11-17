<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interface voor post types met een aparte subdirectory voor attachments
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Upload_Subdir {

	/** Geeft subdirectory terug*/
	public function get_upload_subdir(): string;
}
