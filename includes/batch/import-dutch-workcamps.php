<?php declare(strict_types=1);

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
	 * Optie waarin geïmporteerde ids opgeslagen worden
	 * 
	 * @var int
	 */
	const IMPORTED_DUTCH_PROJECT_IDS_OPTION = 'siw_imported_dutch_project_ids';

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
	protected function select_data() : array {
		$import = new Plato_Import_Dutch_Workcamps;
		$data = $import->run();

		//Geïmporteerde ids opslaan zodat uit Plato verwijderde projecten herkend kunnen worden
		update_option( self::IMPORTED_DUTCH_PROJECT_IDS_OPTION, wp_list_pluck( $data, 'project_id' ) );
		return $data;
	}
}
