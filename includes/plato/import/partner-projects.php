<?php declare(strict_types=1);

namespace SIW\Plato\Import;

use SIW\Plato\Database\Projects\Query;
use SIW\Plato\Import;

class Partner_Projects extends Import {

	protected function get_endpoint(): string {
		return 'GetPartnerProjects';
	}

	public function __construct( string $partner_technical_key ) {
		parent::__construct();
		$this->add_query_arg( 'partnerOrganizationTechnicalKey', $partner_technical_key );
	}

	protected function process_xml() {
		$query = new Query();
		$projects = $this->xml_response->xpath( '//project' );
		foreach ( $projects as $project ) {
			$item = [
				'project_id'                  => (string) $project->project_id,
				'code'                        => (string) $project->code,
				'project_type'                => (string) $project->project_type,
				'work'                        => (string) $project->work,
				'start_date'                  => (string) $project->start_date,
				'end_date'                    => (string) $project->end_date,
				'name'                        => (string) $project->name,
				'location'                    => (string) $project->location,
				'country'                     => (string) $project->country,
				'region'                      => (string) $project->region,
				'languages'                   => (string) $project->languages,
				'participation_fee'           => (float) $project->participation_fee,
				'participation_fee_currency'  => (string) $project->participation_fee_currency,
				'min_age'                     => (int) $project->min_age,
				'max_age'                     => (int) $project->max_age,
				'disabled_vols'               => (bool) $project->disabled_vols,
				'numvol'                      => (int) $project->numvol,
				'vegetarian'                  => (bool) $project->vegetarian,
				'family'                      => (bool) $project->family,
				'description'                 => (string) $project->description,
				'descr_partner'               => (string) $project->descr_partner,
				'descr_work'                  => (string) $project->descr_work,
				'descr_accomodation_and_food' => (string) $project->descr_accomodation_and_food,
				'descr_location_and_leisure'  => (string) $project->descr_location_and_leisure,
				'descr_requirements'          => (string) $project->descr_requirements,
				'descr_appointement'          => (string) $project->descr_appointement,
				'airport'                     => (string) $project->airport,
				'train_bus_station'           => (string) $project->train_bus_station,
				'numvol_m'                    => (int) $project->numvol_m,
				'numvol_f'                    => (int) $project->numvol_f,
				'max_vols_per_country'        => (int) $project->max_vols_per_country,
				'max_teenagers'               => (int) $project->max_teenagers,
				'max_national_vols'           => (int) $project->max_national_vols,
				'lat_project'                 => (float) $project->lat_project,
				'lng_project'                 => (float) $project->lng_project,
				'notes'                       => (string) $project->notes,
				'lat_station'                 => (float) $project->lat_station,
				'lng_station'                 => (float) $project->lng_station,
				'bi_tri_multi'                => (bool) $project->bi_tri_multi,
				'ho_description'              => (string) $project->ho_description,
				'project_summary'             => (string) $project->project_summary,
				'accessibility'               => (bool) $project->accessibility,
				'last_update'                 => (string) $project->last_update,
				'sdg_prj'                     => (string) $project->sdg_prj,
				'url_prj_photo1'              => get_query_arg( 'fileIdentifier', (string) $project->url_prj_photo1 ),
				'url_prj_photo2'              => get_query_arg( 'fileIdentifier', (string) $project->url_prj_photo2 ),
				'url_prj_photo3'              => get_query_arg( 'fileIdentifier', (string) $project->url_prj_photo3 ),
				'url_prj_photo4'              => get_query_arg( 'fileIdentifier', (string) $project->url_prj_photo4 ),
				'url_prj_photo5'              => get_query_arg( 'fileIdentifier', (string) $project->url_prj_photo5 ),
				'url_prj_photo6'              => get_query_arg( 'fileIdentifier', (string) $project->url_prj_photo6 ),
				'url_prj_photo7'              => get_query_arg( 'fileIdentifier', (string) $project->url_prj_photo7 ),
				'url_prj_photo8'              => get_query_arg( 'fileIdentifier', (string) $project->url_prj_photo8 ),
				'url_prj_photo9'              => get_query_arg( 'fileIdentifier', (string) $project->url_prj_photo9 ),
				'cancelled'                   => (bool) $project->cancelled,

			];
			if ( $query->add_item( $item ) ) {
				$this->data[] = (string) $project->project_id; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			}
		}
	}
}
