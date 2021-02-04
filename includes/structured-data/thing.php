<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use Spatie\Enum\Enum;

/**
 * Basisklasse voor Structured Data
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://schema.org/Thing
 */
abstract class Thing {

	/** Data  */
	protected array $data;

	/** Init */
	private function __construct() {}

	/** Creëer entiteit */
	public static function create() {
		$self = new static();
		$self->set_property( '@type', $self->get_type() );
		return $self;
	}

	/** Geeft type van entiteit terug*/
	abstract protected function get_type() : string;

	/** Zet naam */
	public function set_name( string $name )  {
		return $this->set_property( 'name', $name );
	}

	/** Zet beschrijving */
	public function set_description( string $description ) {
		return $this->set_property( 'description', $description );
	}

	/** Zet url */
	public function set_url( string $url ) {
		return $this->set_property( 'url', $url );
	}

	/** Zet afbeelding */
	public function set_image( string $image ) {
		return $this->set_property( 'image', $image );
	}

	/** Zet sameAs (url) */
	public function set_same_as( string $same_as ) {
		return $this->set_property( 'sameAs', $same_as );
	}

	/** Zet eigenschap */
	protected function set_property( string $property, $value ) {
		$this->data[ $property ] = $this->get_string_value( $value );
		return $this;
	}

	/** Voeg eigenschap toe */
	protected function add_property( string $property, $value ) {

		//Huidige waarde casten naar een array indien nodig
		if ( isset( $this->data[ $property ] ) && ! is_array( $this->data[ $property ] ) ) {
			$this->data[ $property ] = (array) $this->data[ $property ];
		}

		$this->data[ $property ][] = $this->get_string_value( $value );
		return $this;
	}

	/** Geeft string waarde van input terug */
	protected function get_string_value( $value ) {
		if ( is_string( $value ) ) {
			return wp_kses_post( $value );
		}
		elseif ( is_subclass_of( $value, Thing::class) ) {
			return $this->get_thing_string_value( $value );
		}
		elseif ( is_a( $value, \DateTime::class ) ) {
			return $this->get_date_time_string_value( $value );
		}
		elseif ( is_subclass_of( $value, Enum::class ) ) {
			return $this->get_enum_string_value( $value );
		}
	}

	/** Zet type property */
	private function get_thing_string_value( self $value ) {
		return $value->get_data();
	}

	private function get_date_time_string_value( \Datetime $value ) {
		$value->setTimezone( wp_timezone() );
		return $value->format( \DateTimeInterface::ISO8601 );
	}

	/** Zet type property */
	private function get_enum_string_value( Enum $value ) {
		return $value->value;
	}
	
	/** Geeft data van type terug */
	protected function get_data() : array {
		return $this->data;
	}

	/** Geef entiteit als JSON-LD array terug */
	public function to_array() : array {
		$this->set_property( '@context', 'http://schema.org' ); //TODO: is dit wel de beste plaats om dit toe te voegen?
		return $this->data;
	}

	/** Geef entiteit als JSON-LD script-tag terug*/
	public function to_script() : string {
		return '<script type="application/ld+json">' . json_encode( $this->to_array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
	}
}
