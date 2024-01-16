<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\Config;
use SIW\Data\Country;
use SIW\Data\Plato\Project_Type;
use SIW\Data\Sustainable_Development_Goal;
use SIW\Data\Work_Type;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Class om een project als WooCommerce product te modelleren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class WC_Product_Project extends \WC_Product_Simple {

	/** Product type */
	public const PRODUCT_TYPE = 'project';

	/** {@inheritDoc} */
	protected $extra_data = [
		'project_id'                     => null,
		'checksum'                       => null,
		'latitude'                       => null,
		'longitude'                      => null,
		'start_date'                     => null,
		'end_date'                       => null,
		'participation_fee'              => null,
		'participation_fee_currency'     => null,
		'min_age'                        => null,
		'max_age'                        => null,
		'full'                           => false,
		'deleted_from_plato'             => false,
		'hidden'                         => false,
		'approval_result'                => null,
		'has_plato_image'                => false,
		'project_description'            => [],
		'custom_price'                   => '',
		'excluded_from_student_discount' => false,
	];

	/** {@inheritDoc } */
	public function get_type() {
		return self::PRODUCT_TYPE;
	}

	/*
	|--------------------------------------------------------------------------
	| Overschreven functies
	|--------------------------------------------------------------------------
	*/

	/** {@inheritDoc} */
	public function is_virtual(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function get_virtual( $context = 'view' ): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function is_sold_individually(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function get_sold_individually( $context = 'view' ): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function is_purchasable(): bool {
		return $this->is_visible();
	}

	/** {@inheritDoc} */
	public function is_in_stock(): bool {
		return $this->is_visible();
	}

	/** {@inheritDoc} */
	public function get_price( $context = 'view' ) {
		return $this->is_on_sale() ? $this->get_sale_price() : $this->get_regular_price();
	}

	/** {@inheritDoc} */
	public function get_sale_price( $context = 'view' ) {
		// Eventueel overschrijven als er weer kortingsacties nodig zijn
		return null;
	}

	/** {@inheritDoc} */
	public function get_regular_price( $context = 'view' ) {

		if ( '' !== $this->get_custom_price() ) {
			return $this->get_custom_price();
		}

		if ( $this->is_dutch_project() ) {
			return (string) Config::get_dutch_project_fee();
		}

		if ( $this->is_esc_project() ) {
			return (string) Config::get_esc_project_fee();
		}

		return (string) Config::get_stv_project_fee();
	}

	/** {@inheritDoc} */
	public function get_date_on_sale_from( $context = 'view' ): ?\WC_DateTime {
		// Eventueel overschrijven als er weer kortingsacties nodig zijn
		return null;
	}

	/** {@inheritDoc} */
	public function get_date_on_sale_to( $context = 'view' ): ?\WC_DateTime {
		// Eventueel overschrijven als er weer kortingsacties nodig zijn
		return null;
	}

	/** {@inheritDoc} */
	public function single_add_to_cart_text(): string {
		return __( 'Aanmelden', 'siw' );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters voor attributes
	|--------------------------------------------------------------------------
	*/

	/** Geeft land van project terug */
	public function get_country(): ?Country {
		$attributes = $this->get_attributes();
		if ( ! isset( $attributes[ Taxonomy_Attribute::COUNTRY->value ] ) ) {
			return null;
		}
		return siw_get_country( $attributes[ Taxonomy_Attribute::COUNTRY->value ]->get_slugs()[0] );
	}

	/** Geeft aan of dit een Nederlandse project is */
	public function is_dutch_project(): bool {
		$attributes = $this->get_attributes();
		if ( ! isset( $attributes[ Taxonomy_Attribute::COUNTRY->value ] ) ) {
			return false;
		}

		return 'nederland' === $attributes[ Taxonomy_Attribute::COUNTRY->value ]->get_slugs()[0];
	}

	/**
	 * Geeft soorten werk van project terug
	 *
	 * @return Work_Type[]
	 */
	public function get_work_types(): array {
		$attributes = $this->get_attributes();
		if ( ! isset( $attributes[ Taxonomy_Attribute::WORK_TYPE->value ] ) ) {
			return [];
		}

		return array_map(
			fn( string $work_type_slug ): ?Work_Type => siw_get_work_type( $work_type_slug ),
			$attributes[ Taxonomy_Attribute::WORK_TYPE->value ]->get_slugs()
		);
	}

	/**
	 * Geeft SDG's van het project terug
	 *
	 * @return Sustainable_Development_Goal[]
	 */
	public function get_sustainable_development_goals(): array {
		$attributes = $this->get_attributes();
		if ( ! isset( $attributes[ Taxonomy_Attribute::SDG->value ] ) ) {
			return [];
		}

		return array_map(
			fn( string $sdg_slug ): ?Sustainable_Development_Goal => Sustainable_Development_Goal::tryFrom( (int) $sdg_slug ) ?? '',
			$attributes[ Taxonomy_Attribute::SDG->value ]->get_slugs()
		);
	}

	public function get_project_type(): ?Project_Type {
		$attributes = $this->get_attributes();
		if ( ! isset( $attributes[ Taxonomy_Attribute::PROJECT_TYPE->value ] ) ) {
			return null;
		}
		return Project_Type::tryFrom( $attributes[ Taxonomy_Attribute::PROJECT_TYPE->value ]->get_slugs()[0] );
	}

	public function is_esc_project(): bool {
		return Project_Type::ESC === $this->get_project_type();
	}

	/*
	|--------------------------------------------------------------------------
	| Getters en setters voor extra props
	|--------------------------------------------------------------------------
	*/

	/** Zet plato project id */
	public function set_project_id( string $project_id ) {
		$this->set_prop( 'project_id', $project_id );
	}

	/** Geeft het plato project id terug */
	public function get_project_id(): string {
		return $this->get_prop( 'project_id' );
	}

	/** Zet checksum van het project */
	public function set_checksum( string $checksum ) {
		$this->set_prop( 'checksum', $checksum );
	}

	/** Geeft checksum van het project terug */
	public function get_checksum(): string {
		return $this->get_prop( 'checksum' );
	}

	/** Zet breedtegraad van het project */
	public function set_latitude( $latitude ) {
		$this->set_prop( 'latitude', (float) $latitude );
	}

	/** Geef breedtegraad van het project terug */
	public function get_latitude(): ?float {
		return ! empty( $this->get_prop( 'latitude' ) ) ? (float) $this->get_prop( 'latitude' ) : null;
	}

	/** Zet lengtegraad van het project */
	public function set_longitude( $longitude ) {
		$this->set_prop( 'longitude', (float) $longitude );
	}

	/** Geef lengtegraad van het project terug */
	public function get_longitude(): ?float {
		return ! empty( $this->get_prop( 'longitude' ) ) ? (float) $this->get_prop( 'longitude' ) : null;
	}

	/** Zet de startdatum van het project */
	public function set_start_date( string $start_date ) {
		$this->set_prop( 'start_date', $start_date );
	}

	/** Geeft de startdatum van het project terug */
	public function get_start_date(): string {
		return $this->get_prop( 'start_date' );
	}

	/** Zet de einddatum van het project */
	public function set_end_date( string $end_date ) {
		$this->set_prop( 'end_date', $end_date );
	}

	/** Geeft de einddatum van het project terug */
	public function get_end_date(): string {
		return $this->get_prop( 'end_date' );
	}

	/** Zet de minimumleeftijd van het project */
	public function set_min_age( $min_age ) {
		$this->set_prop( 'min_age', (int) $min_age );
	}

	/** Geeft de minimumleeftijd van het project terug */
	public function get_min_age(): int {
		return (int) $this->get_prop( 'min_age' );
	}

	/** Zet de maximumleeftijd van het project */
	public function set_max_age( $max_age ) {
		$this->set_prop( 'max_age', (int) $max_age );
	}

	/** Geeft de maximumleeftijd van het project terug */
	public function get_max_age(): int {
		return (int) $this->get_prop( 'max_age' );
	}

	/** Zet de lokale bijdrage van het project */
	public function set_participation_fee( $participation_fee ) {
		$this->set_prop( 'participation_fee', (float) $participation_fee );
	}

	/** Geeft de lokale bijdrage van het project terug */
	public function get_participation_fee(): ?float {
		return ! empty( $this->get_prop( 'participation_fee' ) ) ? (float) $this->get_prop( 'participation_fee' ) : null;
	}

	/** Zet de valuta(code) van de lokale bijdrage */
	public function set_participation_fee_currency( $participation_fee_currency ) {
		$this->set_prop( 'participation_fee_currency', $participation_fee_currency );
	}

	/** Geef de valuta(code) van de lokale bijdrage terug */
	public function get_participation_fee_currency(): ?string {
		return $this->get_prop( 'participation_fee_currency' );
	}

	/** Geeft aan of die project een lokale bijdrage heeft */
	public function has_participation_fee(): bool {
		return null !== $this->get_participation_fee() && ! empty( $this->get_participation_fee_currency() );
	}

	/** Zet of of project vol is */
	public function set_full( bool $full ) {
		$this->set_prop( 'full', $full );
	}

	/** Geeft aan of het project vol is */
	public function get_full(): bool {
		return $this->get_prop( 'full' );
	}

	/** Geeft aan of het project vol is */
	public function is_full(): bool {
		return $this->get_full();
	}

	/** Zet of het project uit Plato verwijderd is */
	public function set_deleted_from_plato( bool $deleted_from_plato ) {
		$this->set_prop( 'deleted_from_plato', $deleted_from_plato );
	}

	/** Geeft aan of het project uit Plato verwijderd is */
	public function get_deleted_from_plato(): bool {
		return $this->get_prop( 'deleted_from_plato' );
	}

	/** Geeft aan of het project uit Plato verwijderd is */
	public function is_deleted_from_plato(): bool {
		return $this->get_deleted_from_plato();
	}

	/** Zet of dit project verborgen moet worden*/
	public function set_hidden( bool $hidden ) {
		$this->set_prop( 'hidden', $hidden );
	}

	/** Geeft aan of dit project verborgen is */
	public function get_hidden(): bool {
		return $this->get_prop( 'hidden' );
	}

	/** Geeft aan of dit project verborgen is */
	public function is_hidden(): bool {
		return $this->get_hidden();
	}

	/** Zet of dit project een afbeelding uit Plato heeft*/
	public function set_has_plato_image( bool $has_plato_image ) {
		$this->set_prop( 'has_plato_image', $has_plato_image );
	}

	/** Geeft aan of dit project een afbeelding uit Plato heeft* */
	public function get_has_plato_image(): bool {
		return $this->get_prop( 'has_plato_image' );
	}

	/** Geeft aan of dit project een afbeelding uit Plato heeft* */
	public function has_plato_image(): bool {
		return $this->get_has_plato_image();
	}

	/** Zet de beschrijving van het project */
	public function set_project_description( $project_description ) {
		$this->set_prop( 'project_description', $project_description );
	}

	/** Geeft de beschrijving van het project terug */
	public function get_project_description(): array {
		return (array) $this->get_prop( 'project_description' );
	}

	/** Zet het beoordelingresultaat van dit project */
	public function set_approval_result( string $approval_result ) {
		$this->set_prop( 'approval_result', $approval_result );
	}

	/** Geeft het beoordelingsresultaat van dit project terug */
	public function get_approval_result(): ?string {
		return $this->get_prop( 'approval_result' );
	}

	/** Zet de afwijkende prijs van dit project */
	public function set_custom_price( $custom_price ) {
		$this->set_prop( 'custom_price', wc_format_decimal( $custom_price ) );
	}

	/** Geeft de afwijkende prijs van dit project terug */
	public function get_custom_price(): string {
		return $this->get_prop( 'custom_price' );
	}

	public function set_excluded_from_student_discount( bool $excluded_from_student_discount ) {
		$this->set_prop( 'excluded_from_student_discount', $excluded_from_student_discount );
	}

	public function get_excluded_from_student_discount(): bool {
		return $this->get_prop( 'excluded_from_student_discount' );
	}

	public function is_excluded_from_student_discount(): bool {
		return $this->get_excluded_from_student_discount();
	}
}
