<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Data\Database_Table;
use SIW\Helpers\Database;

/**
 * Importeer FPL uit Plato
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Import_FPL extends Import {

	/** {@inheritDoc} */
	protected string $endpoint = 'GetAllFreePlaces';

	/** Verwerk xml van Plato */
	protected function process_xml() {

		$db = new Database( Database_Table::PLATO_PROJECT_FREE_PLACES );

		// Tabel leegmaken
		$db->truncate();

		// Kolommen ophalen
		$columns = $db->get_columns();

		foreach ( $this->xml_response->project as $project ) {

			$data = [];
			foreach ( $columns as $column ) {

				// Uitzondering voor url van de infosheet
				if ( 'file_identifier_infosheet' === $column['name'] ) {
					$url_infosheet = (string) $project->url_infosheet;
					if ( ! empty( $url_infosheet ) ) {
						$value = get_query_arg( 'fileIdentifier', $url_infosheet );
					} else {
						$value = '';
					}
				} elseif ( 'project_id' === $column['name'] ) {
					$value = str_replace( '-', '', (string) $project->{$column['name']} );
				} else {
					$value = (string) $project->{$column['name']};
				}
				$data[ $column['name'] ] = $value;
			}

			if ( $db->insert( $data ) ) {
				$this->data[] = str_replace( '-', '', (string) $project->project_id );
			}
		}
	}
}
