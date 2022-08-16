<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Generieke interface voor PageBuilder extensie met style attributes
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Style_Attributes extends Extension {

	/** Zet attributes */
	public function set_style_attributes( array $style_attributes, array $style_args ) : array;
}
