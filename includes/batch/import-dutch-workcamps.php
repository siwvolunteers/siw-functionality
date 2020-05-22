<?php

namespace SIW\Batch;

use SIW\Plato\Import_Dutch_Workcamps as Plato_Import_Dutch_Workcamps;

/**
 * Proces om Nederlandse Groepsprojecten bij te werken
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Import_Dutch_Workcamps extends Import_Workcamps {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'import_dutch_workcamps';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'importeren Nederlandse Groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'plato';

	/**
	 * Haal Nederlandse Groepsprojecten op uit Plato
	 *
	 * @return array
	 */
	 protected function select_data() {
		$import = new Plato_Import_Dutch_Workcamps;
		return $import->run();
	}
}
