<?php declare(strict_types=1);

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
	protected string $endpoint = 'GetAllFreePlaces';

	/**
	 * {@inheritDoc}
	 */
	protected string $name = 'importeren FPL';

	/**
	 * {@inheritDoc}
	 */
	protected string $process_name = 'update_free_places';

	/**
	 * Eigenschappen per project
	 *
	 * @var array
	 */
	protected array $properties = [
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
