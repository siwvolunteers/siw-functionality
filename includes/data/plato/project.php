<?php declare(strict_types=1);

namespace SIW\Data\Plato;

use SIW\Data\Data;

/**
 * Class om Plato Project te modelleren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Project extends Data {

	/** Project id */
	protected string $project_id;

	/** Projectcode */
	protected string $code;

	/** Projecttype */
	protected string $project_type;

	/** Soort werk */
	protected string $work;

	/** Startdatum */
	protected string $start_date;

	/** Einddatum */
	protected string $end_date;

	/** Projectnaam */
	protected string $name;

	/** Projectlocatie */
	protected string $location;

	/** Land */
	protected string $country;

	/** Regio */
	protected string $region;

	/** Projecttalen */
	protected string $languages;

	/** Lokale bijdrage */
	protected float $participation_fee;

	/** Valuta lokale bijdrage */
	protected string $participation_fee_currency;

	/** Minimumleeftijd */
	protected int $min_age;

	/** Maximumleeftijd */
	protected int $max_age;

	/** Is er plek voor gehandicapte deelnemers */
	protected bool $disabled_vols;

	/** Aantal deelnemers */
	protected int $numvol;

	/** Is er vegetarisch eten */
	protected bool $vegetarian;

	/** Is het een familieproject */
	protected bool $family;

	/** Projectomschrijving */
	protected string $description;

	/** Omschrijving van de partner */
	protected string $descr_partner;

	/** Omschrijving van het werk */
	protected string $descr_work;

	/** Omschrijving van accommodatie en eten */
	protected string $descr_accomodation_and_food;

	/** Omschrijving van locatie en vrije tijd */
	protected string $descr_location_and_leisure;

	/** Vereisten */
	protected string $descr_requirements;

	/** Afspreekplek */
	protected string $descr_appointement;

	/** Dichtstbijzijnde vliegveld */
	protected string $airport;

	/** Dichtstbijzijnde trein- of busstation */
	protected string $train_bus_station;

	/** Aantal plekken voor mannen */
	protected int $numvol_m;

	/** Aantal plekken voor vrouwen */
	protected int $numvol_f;

	/** Maximaal aantal vrijwilligers per nationaliteit */
	protected int $max_vols_per_country;

	/** Maximum aantal tieners */
	protected int $max_teenagers;

	/** Maximaal aantal lokale vrijwilligers */
	protected int $max_national_vols;

	/** Breedtegraad van projectlocatie */
	protected float $lat_project;

	/** Lengtegraad van projectlocatie */
	protected float $lng_project;

	/** Opmerkingen */
	protected string $notes;

	/** Breedtegraad dichtstbijzijnde station */
	protected float $lat_station;

	/** Lengtegraad dichtstbijzijnde station */
	protected float $lng_station;

	/** Is het een bi/tri-lateraal project */
	protected bool $bi_tri_multi;

	/** Omschrijving voor social media */
	protected string $ho_description;

	/** Samenvatting */
	protected string $project_summary;

	/** Is het project toegankelijk */
	protected bool $accessibility;

	/** Laatste update */
	protected string $last_update;

	/** Sustainable development goals */
	protected string $sdg_prj;

	/** Projectafbeeldingen */
	protected array $image_file_identifiers;


	/** Checksum om te kijken of project veranderd is */
	public function get_checksum(): string {
		return hash( 'sha1', wp_json_encode( get_object_vars( $this ) ) );
	}

	/** Geeft project_id terug */
	public function get_project_id(): string {
		return $this->project_id; }

	/** Geeft project_id terug */
	public function get_code(): string {
		return $this->code; }

	/** Geeft project_id terug */
	public function get_project_type(): string {
		return $this->project_type; }

	/** Geeft soot werk terug */
	public function get_work(): string {
		return $this->work; }

	/** Geeft startdatum terug */
	public function get_start_date(): string {
		return $this->start_date; }

	/** Geeft einddatum terug */
	public function get_end_date(): string {
		return $this->end_date; }

	/** Geeft projectnaam terug */
	public function get_name(): string {
		return $this->name; }

	/** Geeft locatie terug */
	public function get_location(): string {
		return $this->location; }

	/** Geeft land terug */
	public function get_country(): string {
		return $this->country; }

	/** Geeft regio terug */
	public function get_region(): string {
		return $this->region; }

	/** Geeft projecttalen terug */
	public function get_languages(): string {
		return $this->languages; }

	/** Geeft local fee terug */
	public function get_participation_fee(): float {
		return $this->participation_fee; }

	/** Geeft valuta local fee terug */
	public function get_participation_fee_currency(): string {
		return $this->participation_fee_currency; }

	/** Geeft minimumleeftijd terug */
	public function get_min_age(): int {
		return $this->min_age; }

	/** Geeft maximumleeftijd terug */
	public function get_max_age(): int {
		return $this->max_age; }

	/** Geeft aan of project geschikt is voor vrijwilligers met een handicap */
	public function get_disabled_vols(): bool {
		return $this->disabled_vols; }

	/** Geeft het aantal vrijwilligers terug */
	public function get_numvol(): int {
		return $this->numvol; }

	/** Geeft aan of het project vegetarisch eten heeft  */
	public function get_vegetarian(): bool {
		return $this->vegetarian; }

	/** Geeft aan of het een familieproject is */
	public function get_family(): bool {
		return $this->family; }

	/** Geeft beschrijving terug */
	public function get_description(): string {
		return $this->description; }

	/** Geeft beschrijving van de partner terug */
	public function get_descr_partner(): string {
		return $this->descr_partner; }

	/** Geeft beschrijving van het werk terug */
	public function get_descr_work(): string {
		return $this->descr_work; }

	/** Geeft beschrijving van de accommodatie en het eten terug */
	public function get_descr_accomodation_and_food(): string {
		return $this->descr_accomodation_and_food; }

	/** Geeft beschrijving van de locatie en de vrije tijd terug */
	public function get_descr_location_and_leisure(): string {
		return $this->descr_location_and_leisure; }

	/** Geeft beschrijving van de vereisten terug */
	public function get_descr_requirements(): string {
		return $this->descr_requirements; }

	/** Geeft beschrijving van de partner terug */
	public function get_descr_appointement(): string {
		return $this->descr_appointement; }

	/** Geeft dichtsbijzijnde vliegveld terug */
	public function get_airport(): string {
		return $this->airport; }

	/** Geeft dichtsbijzijnde trein/busstation terug */
	public function get_train_bus_station(): string {
		return $this->train_bus_station; }

	/** Geeft het aantal mannelijke vrijwilligers terug */
	public function get_numvol_m(): int {
		return $this->numvol_m; }

	/** Geeft het aantal vrouwelijke vrijwilligers terug */
	public function get_numvol_f(): int {
		return $this->numvol_f; }

	/** Geeft het maximale aantal vrijwilligers per land terug */
	public function get_max_vols_per_country(): int {
		return $this->max_vols_per_country; }

	/** Geeft het maximale aantal tieners terug */
	public function get_max_teenagers(): int {
		return $this->max_teenagers; }

	/** Geeft het maximale aantal vrijwilligers uit het projectland terug */
	public function get_max_national_vols(): int {
		return $this->max_national_vols; }

	/** Geeft breedtegraad van het project terug */
	public function get_lat_project(): float {
		return $this->lat_project; }

	/** Geeft lengtegraad van het project terug */
	public function get_lng_project(): float {
		return $this->lng_project; }

	/** Geeft opmerkingen terug */
	public function get_notes(): string {
		return $this->notes; }

	/** Geeft breedtegraad van het station terug */
	public function get_lat_station(): float {
		return $this->lat_station; }

	/** Geeft lengtegraat van het station terug */
	public function get_lng_station(): float {
		return $this->lng_station; }

	/** Geeft aan of het een bi/trilateraal project is */
	public function get_bi_tri_multi(): bool {
		return $this->bi_tri_multi; }

	/** Geeft beschrijving voor social media terug */
	public function get_ho_description(): string {
		return $this->ho_description; }

	/** Geeft samenvatting van het project terug */
	public function get_project_summary(): string {
		return $this->project_summary; }

	/** Geeft aan of het een toegankelijk project is */
	public function get_accessibility(): bool {
		return $this->accessibility; }

	/** Geeft datum van laatste update terug */
	public function get_last_update():string {
		return $this->last_update; }

	/** Geeft sustainable development goals van het project terug */
	public function get_sdg_prj(): string {
		return $this->sdg_prj; }

	/** Geeft file identifiers van de projectafbeeldingen terug */
	public function get_image_file_identifiers(): array {
		return $this->image_file_identifiers; }

}
