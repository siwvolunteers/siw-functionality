<?php declare(strict_types=1);

/**
 * Functies m.b.t. Plato
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Plato\Project;
use SIW\Data\Plato\Project_Free_Places;
use SIW\Database_Table;
use SIW\Helpers\Database;

/** Haal Plato FPL op o.b.v. project id */
function siw_get_plato_project_free_places( string $project_id ): ?Project_Free_Places {

	$db = new Database( Database_Table::PLATO_PROJECT_FREE_PLACES() );
	$data = $db->get_row( [ 'project_id' => $project_id ] );

	if ( null === $data ) {
		return null;
	}
	return new Project_Free_Places( $data );
}

/** Haal Plato project op o.b.v. project id */
function siw_get_plato_project( string $project_id ): ?Project {

	// Ophalen projectinformatie
	$projects_db = new Database( Database_Table::PLATO_PROJECTS() );
	$data = $projects_db->get_row( [ 'project_id' => $project_id ] );
	if ( null === $data ) {
		return null;
	}

	// Ophalen projectafbeeldingen
	$images_db = new Database( Database_Table::PLATO_PROJECT_IMAGES() );
	$data['image_file_identifiers'] = $images_db->get_col( 'file_identifier', [ 'project_id' => $project_id ] );

	return new Project( $data );
}
