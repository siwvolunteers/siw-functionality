<?php declare(strict_types=1);

namespace SIW\Plato\Import;

use SIW\Plato\Database\Free_Places\Query;
use SIW\Plato\Import;

class Free_Places extends Import {

	protected function get_endpoint(): string {
		return 'GetAllFreePlaces';
	}

	protected function process_xml() {
		$query = new Query();
		foreach ( $this->xml_response->project as $project ) {
			$item = [
				'id'                        => (int) $project->id,
				'project_id'                => (string) $project->project_id,
				'code'                      => (string) $project->code,
				'start_date'                => (string) $project->start_date,
				'end_date'                  => (string) $project->end_date,
				'numvol'                    => (int) $project->numvol,
				'free_m'                    => (int) $project->free_m,
				'free_f'                    => (int) $project->free_f,
				'free_teen'                 => (int) $project->free_teen,
				'reserved'                  => (int) $project->reserved,
				'no_more_from'              => (string) $project->no_more_from,
				'remarks'                   => (string) $project->remarks,
				'last_update'               => (string) $project->last_update,
				'file_identifier_infosheet' => get_query_arg( 'fileIdentifier', (string) $project->file_identifier_infosheet ),

			];
			if ( $query->add_item( $item ) ) {
				$this->data[] = (string) $project->project_id;
			}
		}
	}
}
