<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Plato\Import_Dutch_Workcamps as Plato_Import_Dutch_Workcamps;

/**
 * Importeren Nederlande projecten uit Plato
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Import_Plato_Dutch_Projects extends Import_Plato_Projects  {

	/** {@inheritDoc} */
	public function get_id() : string {
		return 'import_plato_dutch_projects';
	}

	/** {@inheritDoc} */
	public function get_name() : string {
		return __( 'Importeren Nederlandse projecten uit Plato', 'siw' );
	}

	/** {@inheritDoc} */
	public function select_data() : array {
		$import = new Plato_Import_Dutch_Workcamps;
		return $import->run();
	}
}
