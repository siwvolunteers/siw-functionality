<?php

namespace SIW\Batch;

use SIW\Plato\Import_Dutch_Workcamps;

/**
 * Proces om Nederlandse Groepsprojecten bij te werken
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Update_Dutch_Workcamps extends Update_Workcamps {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_dutch_workcamps';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken Nederlandse Groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'plato';

	/**
	 * Haal Groepsprojecten op uit Plato
	 *
	 * @return array
	 */
	 protected function select_data() {
		$import = new Import_Dutch_Workcamps;
		return $import->run();
	}
}
