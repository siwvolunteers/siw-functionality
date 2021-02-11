<?php declare(strict_types=1);

namespace SIW\Data\Plato;

use SIW\Data\Data;

/**
 * Class om Plato Project te modelleren
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Project extends Data {

	protected string $project_id;
	protected string $code;
	protected string $project_type;
	protected string $work;
	protected string $start_date;
	protected string $end_date;
	protected string $name;
	protected string $location;
	protected string $country;
	protected string $region;
	protected string $languages;
	protected float $participation_fee;
	protected string $participation_fee_currency;
	protected int $min_age;
	protected int $max_age;
	protected bool $disabled_vols;
	protected int $numvol;
	protected bool $vegetarian;
	protected bool $family;
	protected string $description;
	protected string $descr_partner;
	protected string $descr_work;
	protected string $descr_accomodation_and_food;
	protected string $descr_location_and_leisure;
	protected string $descr_requirements;
	protected string $descr_appointement;
	protected string $airport;
	protected string $train_bus_station;
	protected int $numvol_m;
	protected int $numvol_f;
	protected int $max_vols_per_country;
	protected int $max_teenagers;
	protected int $max_national_vols;
	protected float $lat_project;
	protected float $lng_project;
	protected string $notes;
	protected float $lat_station;
	protected float $lng_station;
	protected bool $bi_tri_multi;
	protected string $ho_description;
	protected string $project_summary;
	protected bool $accessibility;
	protected string $last_update;
	protected string $sdg_prj;
	protected array $image_file_identifiers;


	/** Checksum om te kijken of project veranderd is */
	public function get_checksum() : string {
		return hash( 'sha1', json_encode( get_object_vars( $this ) ) );
	}

	/** Geeft project_id terug */
	public function get_project_id() : string { return $this->project_id; }

	/** Geeft project_id terug */
	public function get_code() : string { return $this->code; }

	/** Geeft project_id terug */
	public function get_project_type() : string { return $this->project_type; }
	
	/** Geeft soot werk terug */
	public function get_work() : string { return $this->work; }

	/** Geeft startdatum terug */
	public function get_start_date() : string { return $this->start_date; }

	/** Geeft einddatum terug */
	public function get_end_date() : string { return $this->end_date; }

	/** Geeft projectnaam terug */
	public function get_name() : string { return $this->name; }

	/** Geeft locatie terug */
	public function get_location() : string { return $this->location; }

	/** Geeft land terug */
	public function get_country() : string { return $this->country; }

	/** Geeft regio terug */
	public function get_region() : string { return $this->region; }

	/** Geeft projecttalen terug */
	public function get_languages() : string { return $this->languages; }

	/** Geeft local fee terug */
	public function get_participation_fee() : float { return $this->participation_fee; }

	/** Geeft valuta local fee terug */
	public function get_participation_fee_currency() : string { return $this->participation_fee_currency; }

	/** Geeft minimumleeftijd terug */
	public function get_min_age() : int { return $this->min_age; }

	/** Geeft maximumleeftijd terug */
	public function get_max_age() : int { return $this->max_age; }

	/** Geeft aan of project geschikt is voor vrijwilligers met een handicap */
	public function get_disabled_vols() : bool { return $this->disabled_vols; }

	/** Geeft het aantal vrijwilligers terug */
	public function get_numvol() : int { return $this->numvol; }

	/** Geeft aan of het project vegetarisch eten heeft  */
	public function get_vegetarian() : bool { return $this->vegetarian; }

	/** Geeft aan of het een familieproject is */
	public function get_family() : bool { return $this->family; }

	/** Geeft beschrijving terug */
	public function get_description() : string { return $this->description; }

	/** Geeft beschrijving van de partner terug */
	public function get_descr_partner() : string { return $this->descr_partner; }

	/** Geeft beschrijving van het werk terug */
	public function get_descr_work() : string { return $this->descr_work; }

	/** Geeft beschrijving van de accommodatie en het eten terug */
	public function get_descr_accomodation_and_food() : string { return $this->descr_accomodation_and_food; }

	/** Geeft beschrijving van de locatie en de vrije tijd terug */
	public function get_descr_location_and_leisure() : string { return $this->descr_location_and_leisure; }

	/** Geeft beschrijving van de vereisten terug */
	public function get_descr_requirements() : string { return $this->descr_requirements; }

	/** Geeft beschrijving van de partner terug */
	public function get_descr_appointement() : string { return $this->descr_appointement; }

	/** Geeft dichtsbijzijnde vliegveld terug */
	public function get_airport() : string { return $this->airport; }

	/** Geeft dichtsbijzijnde trein/busstation terug */
	public function get_train_bus_station() : string { return $this->train_bus_station; }

	/** Geeft het aantal mannelijke vrijwilligers terug */
	public function get_numvol_m() : int { return $this->numvol_m; }

	/** Geeft het aantal vrouwelijke vrijwilligers terug */
	public function get_numvol_f() : int { return $this->numvol_f; }

	/** Geeft het maximale aantal vrijwilligers per land terug */
	public function get_max_vols_per_country() : int { return $this->max_vols_per_country; }

	/** Geeft het maximale aantal tieners terug */
	public function get_max_teenagers() : int { return $this->max_teenagers; }

	/** Geeft het maximale aantal vrijwilligers uit het projectland terug */
	public function get_max_national_vols() : int { return $this->max_national_vols; }

	/** Geeft breedtegraad van het project terug */
	public function get_lat_project() : float { return $this->lat_project; }

	/** Geeft lengtegraad van het project terug */
	public function get_lng_project() : float { return $this->lng_project; }

	/** Geeft opmerkingen terug */
	public function get_notes() : string { return $this->notes; }

	/** Geeft breedtegraad van het station terug */
	public function get_lat_station() : float { return $this->lat_station; }

	/** Geeft lengtegraat van het station terug */
	public function get_lng_station() : float { return $this->lng_station; }

	/** Geeft aan of het een bi/trilateraal project is */
	public function get_bi_tri_multi() : bool { return $this->bi_tri_multi; }

	/** Geeft beschrijving voor social media terug */
	public function get_ho_description() : string { return $this->ho_description; }

	/** Geeft samenvatting van het project terug */
	public function get_project_summary() : string { return $this->project_summary; }

	/** Geeft aan of het een toegankelijk project is */
	public function get_accessibility() : bool { return $this->accessibility; }

	/** Geeft datum van laatste update terug */
	public function get_last_update() : string { return $this->last_update; }

	/** Geeft sustainable development goals van het project terug */
	public function get_sdg_prj() : string { return $this->sdg_prj; }

	/** Geeft file identifiers van de projectafbeeldingen terug */
	public function get_image_file_identifiers() : array { return $this->image_file_identifiers; }

}