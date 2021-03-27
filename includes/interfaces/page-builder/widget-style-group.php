<?php declare(strict_types=1);

namespace SIW\Interfaces\Page_Builder;

/**
 * Interface voor PageBuilder extensie met widget style fields en group
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Widget_Style_Group extends Widget_Style_Fields {

	/** Voegt optiegroep voor widget toe */
	public function add_style_group( array $groups ) : array;

}