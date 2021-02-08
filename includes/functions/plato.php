<?php declare(strict_types=1);

namespace SIW\Plato;

/**
 * Functies m.b.t. Plato
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Plato\Project_Free_Places;
use SIW\Database_Table;
use SIW\Helpers\Database;

/** Haal Plato FPL op o.b.v. project id */
function get_project_free_places( string $project_id ) : ?Project_Free_Places {

	$db = new Database( Database_Table::PLATO_PROJECT_FREE_PLACES() );
	$data = $db->get_row( [ 'project_id' => $project_id ] );

	if ( null == $data ) {
		return null;
	}
	return new Project_Free_Places( $data );
}
