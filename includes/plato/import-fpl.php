<?php

namespace SIW\Plato;

/**
 * Importeer FPL uit Plato
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Import_FPL extends Import {

	/**
	 * {@inheritDoc}
	 */
	protected $endpoint = 'GetAllFreePlaces';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'importeren FPL';

	/**
	 * Name van background process
	 *
	 * @var string
	 */
	protected $process_name = 'update_free_places';

	/**
	 * Eigenschappen per project
	 *
	 * @var array
	 */
	protected $properties = [
		'project_id',
		'code',
		'free_m',
		'free_f',
		'no_more_from',
	];

	/**
	 * Verwerk xml van Plato
	 */
	protected function process_xml() {

		$this->data = [];
		foreach ( $this->xml_response->project as $project ) {
			$project_data = [];
			foreach ( $this->properties as $property ) {
				$project_data[ $property ] = (string) $project->$property;
			}
			$this->data[] = $project_data;		
		}
	}
}