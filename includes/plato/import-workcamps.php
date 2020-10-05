<?php declare(strict_types=1);

namespace SIW\Plato;

/**
 * Importeer Groepsprojecten uit Plato
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Import_Workcamps extends Import {

	/**
	 * {@inheritDoc}
	 */
	protected string $endpoint = 'GetAllProjects';

	/**
	 * {@inheritDoc}
	 */
	protected string $name = 'importeren groepsprojecten';

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
		'sdg_prj',
	];
	
	/**
	 * Verwerk xml van Plato
	 * 
	 * @todo partnerorganisatie opslaan (uit parent)
	 */
	protected function process_xml() {
		$projects = $this->xml_response->xpath( '//project' );
		foreach ( $projects as $project ) {
			$project_data = [];
			foreach ( $this->properties as $property ) {
				$project_data[ $property ] = (string) $project->$property;
			}

			//Zoek urls van projectafbeeldingen
			$image_urls = $project->xpath( "*[starts-with(local-name(),'url_prj_photo')]" ); 
			$project_data['images'] = [];
			foreach ( $image_urls as $image_url ) {
				$url_query = parse_url( (string) $image_url, PHP_URL_QUERY );
				parse_str( $url_query, $query );
				$project_data['images'][] = $query['fileIdentifier'];
			}
			$this->data[] = $project_data;
		}
		return;
	}
}
