<?php

/**
 * Importeer Groepsprojecten uit Plato
 * 
 * @package   SIW\Plato
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Plato_Import_Workcamps extends SIW_Plato_Import {

	/**
	 * {@inheritDoc}
	 */
	protected $endpoint = 'GetAllProjects';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'importeren groepsprojecten';

	/**
	 * Eigenschappen per project
	 *
	 * @var array
	 */
	protected $properties = [
		'project_id',
		'code',
		'project_type',
		'work',
		'start_date',
		'end_date',
		'name',
		'location',
		'country',
		'region',
		'languages',
		'participation_fee',
		'participation_fee_currency',
		'min_age',
		'max_age',
		'disabled_vols',
		'numvol',
		'vegetarian',
		'family',
		'description',
		'descr_partner',
		'descr_work',
		'descr_accomodation_and_food',
		'descr_location_and_leisure',
		'descr_requirements',
		'airport',
		'train_bus_station',
		'numvol_m',
		'numvol_f',
		'max_vols_per_country',
		'max_teenagers',
		'max_national_vols',
		'lat_project',
		'lng_project',
		'notes',
		'lat_station',
		'lng_station',
		'bi_tri_multi',
		'ho_description',
		'project_summary',
		'accessibility',
		'last_update',
	];
	
	/**
	 * Verwerk xml van Plato
	 */
	protected function process_xml() {
		$projects = $this->xml_response->xpath( '//project' );
		foreach ( $projects as $project ) {
			$project_data = [];
			foreach ( $this->properties as $property ) {
				$project_data[ $property ] = (string) $project->$property;
			}
			$this->data[] = $project_data;
		}
		return;
	}

}
