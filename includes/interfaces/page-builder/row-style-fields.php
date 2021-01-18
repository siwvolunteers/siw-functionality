<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Interface voor PageBuilder extensie met row style
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
interface Row_Style_Fields {

	/**
	 * Voegt opties voor rij toe
	 */
	public function add_style_fields( array $fields ) : array;

	/**
	 * Voegt attributes voor rij toe
	 */
	public function set_style_attributes( array $style_attributes, array $style_args ) : array;

}