<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Tag_Attribute;

class Tag_Attributes extends Base {

	#[Add_Filter( 'script_loader_tag' )]
	public function maybe_add_script_attributes( string $tag, string $handle ): string {
		foreach ( Tag_Attribute::cases() as $attribute ) {
			$attribute_value = wp_scripts()->get_data( $handle, $attribute->value );
			if ( $attribute_value ) {
				$tag = $this->add_attribute( $tag, $attribute->value, $attribute_value );
			}
		}
		return $tag;
	}

	#[Add_Filter( 'style_loader_tag' )]
	public function maybe_add_style_attributes( string $tag, string $handle ): string {
		foreach ( Tag_Attribute::cases() as $attribute ) {
			$attribute_value = wp_styles()->get_data( $handle, $attribute->value );
			if ( $attribute_value ) {
				$tag = $this->add_attribute( $tag, $attribute->value, $attribute_value );
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
