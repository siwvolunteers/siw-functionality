<?php declare(strict_types=1);

namespace SIW;

use SIW\Attributes\Filter;

/**
 * Extra attributes voor assets (Script/Style)
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Asset_Attributes extends Base {

	// TODO: PHP8.1 enums van maken
	const CROSSORIGIN = 'crossorigin';
	const INTEGRITY = 'integrity';

	const ATTRIBUTES = [
		self::CROSSORIGIN,
		self::INTEGRITY,
	];

	#[Filter( 'script_loader_tag' )]
	public function maybe_add_script_attributes( string $tag, string $handle ): string {
		foreach ( self::ATTRIBUTES as $attribute ) {
			$attribute_value = wp_scripts()->get_data( $handle, $attribute );
			if ( $attribute_value ) {
				$tag = $this->add_attribute( $tag, $attribute, $attribute_value );
			}
		}
		return $tag;
	}

	#[Filter( 'style_loader_tag' )]
	public function maybe_add_style_attributes( string $tag, string $handle ): string {
		foreach ( self::ATTRIBUTES as $attribute ) {
			$attribute_value = wp_styles()->get_data( $handle, $attribute );
			if ( $attribute_value ) {
				$tag = $this->add_attribute( $tag, $attribute, $attribute_value );
			}
		}
		return $tag;
	}

	protected function add_attribute( string $tag, string $attribute, string|bool $attribute_value ): string {
		$processor = new \WP_HTML_Tag_Processor( $tag );

		if ( $processor->next_tag() ) {
			$processor->set_attribute( $attribute, $attribute_value );
		}
		return $processor->get_updated_html();
	}

}
