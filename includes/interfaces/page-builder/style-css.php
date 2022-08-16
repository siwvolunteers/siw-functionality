<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Generieke interface voor PageBuilder extensie met style css
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Style_CSS extends Extension {

	/** Zet attributes */
	public function set_style_css( array $style_css, array $style_args ): array;
}
