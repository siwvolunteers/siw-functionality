<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

/**
 * Interface voor formulier via MetaBox
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Form {
	
	/** Volledige breedte */
	const FULL_WIDTH = 12;

	/** Halve breedte */
	const HALF_WIDTH = 6;

	/** Geeft ID van formulier terug */
	public function get_form_id(): string;

	/** Geeft naam van formulier terug */
	public function get_form_name(): string;

	/** Geeft velden van formulier toe */
	public function get_form_fields(): array;

}
