<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use SIW\Interfaces\Structured_Data\Enumeration;

/**
 * Basisklasse voor Structured Data
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/Thing
 */
abstract class Thing {

	/** Data */
	protected array $data;

	/** Init */
	private function __construct() {}

	/** Creëer entiteit */
	public static function create(): static {
		$self = new static();
		$self->set_property( '@type', $self->get_type() );
		return $self;
	}

	/** Geeft type van entiteit terug*/
	abstract protected function get_type(): string;

	/** Zet naam */
	public function set_name( string $name ): static {
		return $this->set_property( 'name', $name );
	}

	/** Zet beschrijving */
	public function set_description( string $description ): static {
		return $this->set_property( 'description', $description );
	}

	/** Zet url */
	public function set_url( string $url ): static {
		return $this->set_property( 'url', $url );
	}

	/** Zet afbeelding */
	public function set_image( string $image ): static {
		return $this->set_property( 'image', $image );
	}

	/** Zet sameAs (url) */
	public function set_same_as( string $same_as ): static {
		return $this->set_property( 'sameAs', $same_as );
	}

	/** Zet eigenschap */
	protected function set_property( string $property, $value ): static {
		$this->data[ $property ] = $this->parse_value( $value );
		return $this;
	}

	/** Voeg eigenschap toe */
	protected function add_property( string $property, $value ): static {

		// Huidige waarde casten naar een array indien nodig
		if ( isset( $this->data[ $property ] ) && ! is_array( $this->data[ $property ] ) ) {
			$this->data[ $property ] = (array) $this->data[ $property ];
		}

		$this->data[ $property ][] = $this->parse_value( $value );
		return $this;
	}

	/** Parset waarde */
	protected function parse_value( $value ): string|array {
		if ( is_string( $value ) ) {
			return wp_kses_post( $value );
		} elseif ( is_subclass_of( $value, self::class ) ) {
			return $this->get_thing_value( $value );
		} elseif ( is_a( $value, \DateTime::class ) ) {
			return $this->get_date_time_value( $value );
		} elseif ( is_a( $value, \BackedEnum::class ) ) {
			return $this->get_enum_value( $value );
		}
	}

	/** Geeft waarde van `Thing` terug */
	private function get_thing_value( self $value ): array {
		return $value->get_data();
	}

	/** Geeft waarde van DateTime object terug (inclusief tijdzone) */
	private function get_date_time_value( \Datetime $value ): string {
		$value->setTimezone( wp_timezone() );
		return $value->format( \DateTimeInterface::ATOM );
	}

	/** Geeft waarde van Enum terug */
	private function get_enum_value( \BackedEnum $enum_value ): string {
		return is_a( $enum_value, Enumeration::class ) ? "https://schema.org/{$enum_value->value}" : $enum_value->value;
	}

	/** Geeft data van type terug */
	protected function get_data(): array {
		return $this->data;
	}

	/** Geef entiteit als JSON-LD array terug */
	public function to_array(): array {
		$this->set_property( '@context', 'http://schema.org' );
		return $this->data;
	}

	/** Geef entiteit als JSON-LD script-tag terug*/
	public function to_script(): string {
		return '<script type="application/ld+json">' . wp_json_encode( $this->to_array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
	}
}
