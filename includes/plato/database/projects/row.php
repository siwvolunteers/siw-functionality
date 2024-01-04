<?php declare(strict_types=1);

namespace SIW\Plato\Database\Projects;

class Row extends \BerlinDB\Database\Row {

	readonly public int $id;
	readonly public string $project_id;
	readonly public string $code;
	readonly public string $project_type;
	readonly public string $work;
	readonly public string $start_date;
	readonly public string $end_date;
	readonly public string $name;
	readonly public string $location;
	readonly public string $country;
	readonly public string $region;
	readonly public string $languages;
	readonly public float $participation_fee;
	readonly public string $participation_fee_currency;
	readonly public int $min_age;
	readonly public int $max_age;
	readonly public bool $disabled_vols;
	readonly public int $numvol;
	readonly public bool $vegetarian;
	readonly public bool $family;
	readonly public string $description;
	readonly public string $descr_partner;
	readonly public string $descr_work;
	readonly public string $descr_accomodation_and_food;
	readonly public string $descr_location_and_leisure;
	readonly public string $descr_requirements;
	readonly public string $descr_appointement;
	readonly public string $airport;
	readonly public string $train_bus_station;
	readonly public int $numvol_m;
	readonly public int $numvol_f;
	readonly public int $max_vols_per_country;
	readonly public int $max_teenagers;
	readonly public int $max_national_vols;
	readonly public float $lat_project;
	readonly public float $lng_project;
	readonly public string $notes;
	readonly public float $lat_station;
	readonly public float $lng_station;
	readonly public bool $bi_tri_multi;
	readonly public string $ho_description;
	readonly public string $project_summary;
	readonly public bool $accessibility;
	readonly public string $last_update;
	readonly public string $sdg_prj;

	public function __construct( $item ) {
		$item->id = (int) $item->id;

		$item->project_id = (string) $item->project_id;
		$item->code = (string) $item->code;
		$item->project_type = (string) $item->project_type;
		$item->work = (string) $item->work;
		$item->start_date = (string) $item->start_date;
		$item->end_date = (string) $item->end_date;
		$item->name = (string) $item->name;
		$item->location = (string) $item->location;
		$item->country = (string) $item->country;
		$item->region = (string) $item->region;
		$item->languages = (string) $item->languages;
		$item->participation_fee = (float) $item->participation_fee;
		$item->participation_fee_currency = (string) $item->participation_fee_currency;
		$item->min_age = (int) $item->min_age;
		$item->max_age = (int) $item->max_age;
		$item->disabled_vols = (bool) $item->disabled_vols;
		$item->numvol = (int) $item->numvol;
		$item->vegetarian = (bool) $item->vegetarian;
		$item->family = (bool) $item->family;
		$item->description = (string) $item->description;
		$item->descr_partner = (string) $item->descr_partner;
		$item->descr_work = (string) $item->descr_work;
		$item->descr_accomodation_and_food = (string) $item->descr_accomodation_and_food;
		$item->descr_location_and_leisure = (string) $item->descr_location_and_leisure;
		$item->descr_requirements = (string) $item->descr_requirements;
		$item->descr_appointement = (string) $item->descr_appointement;
		$item->airport = (string) $item->airport;
		$item->train_bus_station = (string) $item->train_bus_station;
		$item->numvol_m = (int) $item->numvol_m;
		$item->numvol_f = (int) $item->numvol_f;
		$item->max_vols_per_country = (int) $item->max_vols_per_country;
		$item->max_teenagers = (int) $item->max_teenagers;
		$item->max_national_vols = (int) $item->max_national_vols;
		$item->lat_project = (float) $item->lat_project;
		$item->lng_project = (float) $item->lng_project;
		$item->notes = (string) $item->notes;
		$item->lat_station = (float) $item->lat_station;
		$item->lng_station = (float) $item->lng_station;
		$item->bi_tri_multi = (bool) $item->bi_tri_multi;
		$item->ho_description = (string) $item->ho_description;
		$item->project_summary = (string) $item->project_summary;
		$item->accessibility = (bool) $item->accessibility;
		$item->last_update = (string) $item->last_update;
		$item->sdg_prj = (string) $item->sdg_prj;

		parent::__construct( $item );
	}
}
