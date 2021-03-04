<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

/**
 * Interface voor Form Processor
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Form_Processor {

	/** Geeft id van processor terug */
	public function get_id() : string;

	/** Geeft naam van processor terug */
	public function get_name() : string;

	/** Geeft beschrijving van processor terug */
	public function get_description() : string;
}
