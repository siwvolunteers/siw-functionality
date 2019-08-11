<?php

/**
 * Proces om Nederlandse Groepsprojecten bij te werken
 * 
 * @package   SIW\Batch
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @uses      SIW_Plato_Import_Dutch_Workcamps
 */
class SIW_Batch_Job_Update_Dutch_Workcamps extends SIW_Batch_Job_Update_Workcamps {

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
		$import = new SIW_Plato_Import_Dutch_Workcamps;
		$data = $import->run();
		
		return $data;
	}
}