<?php declare(strict_types=1);

namespace SIW\Data\Plato;

use SIW\Data\Data;

/**
 * Class om vrije plaatsen van een Plato-project te modelleren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Project_Free_Places extends Data {

	/** Project-id */
	protected string $project_id;

	/** Projectcode */
	protected string $code;

	/** Startdatum */
	protected string $start_date;

	/** Einddatum */
	protected string $end_date;

	/** Aantal vrijwilligers */
	protected int $numvol;

	/** Aantal vrije plaatsen voor mannen */
	protected int $free_m;

	/** Aantal vrij plaatsen voor vrouwen */
	protected int $free_f;

	/** Aantal vrije plaatsen voor tieners */
	protected int $free_teen;

	/** Aantal gereserveerde plaatsen */
	protected int $reserved;

	/** Niet meer uit dit land */
	protected string $no_more_from;

	/** Opmerkingen */
	protected string $remarks;

	/** Datum laatste update */
	protected string $last_update;

	/** URL van infosheet */
	protected string $file_identifier_infosheet;

	/** Geeft project id terug */
	public function get_project_id(): string {
		return $this->project_id;}

	/** Geeft de code van het project terug */
	public function get_code(): string {
		return $this->code;}

	/** Geeft de startdatum van het project terug */
	public function get_start_date(): string {
		return $this->start_date;}

	/** Geeft de einddatum van het project terug */
	public function get_end_date(): string {
		return $this->end_date;}

	/** Geeft de code van het project terug */
	public function get_numvol(): int {
		return $this->numvol;}

	/** Geeft het aantal vrij plaatsen voor mannen terug */
	public function get_free_m(): int {
		return $this->free_m;}

	/** Geeft het aantal vrij plaatsen voor vrouwen terug */
	public function get_free_f(): int {
		return $this->free_f;}

	/** Geeft het aantal vrij plaatsen voor tieners terug */
	public function get_free_teen(): int {
		return $this->free_teen;}

	/** Geeft het aantal gereserveerde plaatsen terug */
	public function get_reserved(): int {
		return $this->reserved;}

	/** Geeft terug voor vrijwilligers uit welke landen er geen plaats meer is */
	public function get_no_more_from(): string {
		return $this->no_more_from;}

	/** Geeft opmerkingen terug */
	public function get_remarks(): string {
		return $this->remarks;}

	/** Geeft datum van laatste update terug */
	public function get_last_update(): string {
		return $this->last_update;}

	/** Geeft fileIdentifier van de infosheet terug */
	public function get_file_identifier_infosheet(): string {
		return $this->file_identifier_infosheet;}
}
