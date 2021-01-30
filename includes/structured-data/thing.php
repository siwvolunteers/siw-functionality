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
		if ( is_string( $value ) ) {
			$this->set_string_property( $property, $value );
		}
		elseif ( is_subclass_of( $value, self::class ) ) {
			$this->set_type_property( $property, $value );
		}
		elseif ( is_a( $value, \DateTime::class ) ) {
			$value->setTimezone( wp_timezone() );
			$this->set_string_property( $property, $value->format( \DateTimeInterface::ISO8601 ) );
		}
		elseif ( is_subclass_of( $value, Enum::class ) ) {
			$this->set_string_property( $property, $value->value );
		}
		return $this;
	}

	/** Zet string property */
	private function set_string_property( string $property, string $string_value ) {
		$this->data[ $property ] = esc_attr( $string_value );
	}

	/** Zet type property */
	private function set_type_property( string $property, self $type_value ) {
		$this->data[ $property ] = $type_value->get_data();
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
