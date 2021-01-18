<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Interface voor PageBuilder extensie met cell style fields en group
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
interface Cell_Style_Group extends Cell_Style_Fields {

	/**
	 * Voegt optiegroep voor cell toe
	 */
	public function add_style_group( array $groups ) : array;

}