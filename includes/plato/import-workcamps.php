<?php declare(strict_types=1);

namespace SIW\Plato;

use SIW\Database_Table;
use SIW\Helpers\Database;

/**
 * Importeer Groepsprojecten uit Plato
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Import_Workcamps extends Import {

	/** {@inheritDoc} */
	protected string $endpoint = 'GetAllProjects';

	/** {@inheritDoc} */
	protected string $name = 'importeren groepsprojecten';

	/** {@inheritDoc} */
	protected string $xsd_file = SIW_PLUGIN_DIR . 'xsd/plato/projects.xsd';

	/** Geef aan dat dit geen Nederlandse projecten zijn */
	protected bool $dutch_project = false;

	/** Verwerk xml van Plato */
	protected function process_xml() {

		$projects_db = new Database( Database_Table::PLATO_PROJECTS() );
		$images_db = new Database( Database_Table::PLATO_PROJECT_IMAGES() );

		// Tabel leegmaken
		$projects_db->delete( [ 'dutch_project' => $this->dutch_project ] );

		// Kolommen ophalen
		$columns = $projects_db->get_columns();

		$projects = $this->xml_response->xpath( '//project' );
		foreach ( $projects as $project ) {

			$project_data = [];
			foreach ( $columns as $column ) {
				if ( 'dutch_project' === $column['name'] ) {
					$project_data[ $column['name'] ] = $this->dutch_project;
				} else {
					$project_data[ $column['name'] ] = (string) $project->{$column['name']};
				}
			}
			if ( ! $projects_db->insert( $project_data ) ) {
				continue;
			}
			$this->data[] = (string) $project->project_id;

			$image_urls = $project->xpath( "*[starts-with(local-name(),'url_prj_photo')]" );
			foreach ( $image_urls as $index => $image_url ) {
				$image_data = [
					'project_id'      => (string) $project->project_id,
					'image_id'        => $index,
					'file_identifier' => get_query_arg( 'fileIdentifier', (string) $image_url ),
				];
				$images_db->insert( $image_data );
			}
		}
	}
}
