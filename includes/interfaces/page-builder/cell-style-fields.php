<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Interface voor PageBuilder extensie met cell style
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Cell_Style_Fields {

	/** Voegt opties voor cell toe */
	public function add_style_fields( array $fields ) : array;

	/** Voegt attributes voor cell toe */
	public function set_style_attributes( array $style_attributes, array $style_args ) : array;

}
