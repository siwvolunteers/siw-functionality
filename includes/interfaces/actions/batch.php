<?php declare(strict_types=1);

namespace SIW\Interfaces\Actions;

/**
 * Interface voor batchacties
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Batch extends Action {

	/** Selecteer data om te verwerken */
	public function select_data() : array;

	/** Verwerk item */
	public function process( $item );

	/** Moet actie toegevoegd worden aan scheduler */
	public function must_be_scheduled() : bool;

	/** Moet actie uitgevoerd worden bij update van plugin */
	public function must_be_run_on_update() : bool;
}
