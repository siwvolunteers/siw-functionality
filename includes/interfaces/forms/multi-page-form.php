<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

/**
 * Interface voor definiëren van een formulier met meerdere pagina's
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Multi_Page_Form {

	/** Geeft lijst met pagina's van formulier terug */
	public function get_pages() : array;
}