<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Interface voor PageBuilder extensie met row style fields en group
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
interface Row_Style_Group extends Row_Style_Fields {

	/**
	 * Voegt optiegroep voor rij toe
	 *
	 * @param array $groups
	 * @return array
	 */
	public function add_style_group( array $groups ) : array;

}