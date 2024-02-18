<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use SIW\Interfaces\Structured_Data\Enumeration;

/**
 * @see       https://schema.org/Thing
 */
abstract class Thing {

	protected array $data;

	private function __construct() {}

	public static function create(): static {
		$self = new static();
		$self->set_property( '@type', $self->get_type() );
		return $self;
	}

	abstract protected function get_type(): string;

	public function set_name( string $name ): static {
		return $this->set_property( 'name', $name );
	}

	public function set_description( string $description ): static {
		return $this->set_property( 'description', $description );
	}

	public function set_url( string $url ): static {
		return $this->set_property( 'url', $url );
	}

	public function set_image( string $image ): static {
		return $this->set_property( 'image', $image );
	}

	public function set_same_as( string $same_as ): static {
		return $this->set_property( 'sameAs', $same_as );
	}

	public function add_same_as( string $same_as ): static {
		return $this->add_property( 'sameAs', $same_as );
	}

	protected function set_property( string $property, $value ): static {
		$this->data[ $property ] = $this->parse_value( $value );
		return $this;
	}

	protected function add_property( string $property, $value ): static {

		if ( isset( $this->data[ $property ] ) && ! is_array( $this->data[ $property ] ) ) {
			$this->data[ $property ] = (array) $this->data[ $property ];
		}

		$this->data[ $property ][] = $this->parse_value( $value );
		return $this;
	}

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

	private function get_thing_value( self $value ): array {
		return $value->get_data();
	}

	private function get_date_time_value( \Datetime $value ): string {
		$value->setTimezone( wp_timezone() );
		return $value->format( \DateTimeInterface::ATOM );
	}

	private function get_enum_value( \BackedEnum $enum_value ): string {
		return is_a( $enum_value, Enumeration::class ) ? "https://schema.org/{$enum_value->value}" : $enum_value->value;
	}

	protected function get_data(): array {
		return $this->data;
	}

	public function to_array(): array {
		$this->set_property( '@context', 'http://schema.org' );
		return $this->data;
	}

	public function to_script(): string {
		return '<script type="application/ld+json">' . wp_json_encode( $this->to_array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
	}
}
