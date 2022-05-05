<?php declare(strict_types=1);

namespace SIW\Util;

/**
 * Meta Box util functies
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Meta_Box {

	/** Zet meta box veld om naar rest api arg */
	public static function convert_field_to_rest_api_arg( array $field ): array {

		if ( in_array( $field['type'], [ 'button', 'file', 'heading' ], true ) ) {
			// Dit type velden kan geen REST API arg zijn
			return [];
		}

		$arg = [
			'description' => $field['name'] ?? null,
			'required'    => $field['required'] ?? true,
			'minimum'     => $field['min'] ?? null,
			'maximum'     => $field['max'] ?? null,
			'maxLength'   => $field['maxlength'] ?? null,
			'pattern'     => $field['pattern'] ?? null,
		];

		// Zet type -> match (php8)
		switch ( $field['type'] ) {
			case 'text':
			case 'textarea':
			case 'email':
			case 'tel':
			case 'radio':
			case 'select':
			case 'select_advanced':
			case 'button_group':
				$arg['type'] = 'string';
				break;
			case 'checkbox_list':
				$arg['type'] = 'array';
				break;
			case 'checkbox':
			case 'switch':
				$arg['type'] = 'boolean';
				break;
			case 'number':
				$arg['type'] = 'number';
				break;
			default:
				// TODO: warning
		}

		// zet enum -> match (php8)
		switch ( $field['type'] ) {
			case 'radio':
			case 'select':
			case 'button_group':
				$arg['enum'] = array_keys( $field['options'] );
				$arg['type'] = wp_is_numeric_array( $field['options'] ) ? 'integer' : 'string';
				break;
		}

		// Zet items
		switch ( $field['type'] ) {
			case 'checkbox_list':
				$arg['items'] = [
					'type' => wp_is_numeric_array( $field['options'] ) ? 'integer' : 'string',
					'enum' => array_keys( $field['options'] ),
				];
				break;
		}

		// Zet dataformat -> match (php8)
		switch ( $field['type'] ) {
			case 'email':
				$arg['format'] = 'email';
				break;
			case 'url':
				$arg['format'] = 'uri';
				break;
		}

		// Zet sanitize/validate callbacks
		switch ( $field['type'] ) {
			case 'text':
				$arg['validate_callback'] = 'rest_validate_request_arg';
				$arg['sanitize_callback'] = 'sanitize_text_field';
				break;
			case 'textarea':
				$arg['validate_callback'] = 'rest_validate_request_arg';
				$arg['sanitize_callback'] = 'sanitize_textarea_field';
				break;
		}

		// Lege/false waarden verwijderen
		$arg = array_filter( $arg );

		return $arg;
	}

	/** Geeft weergave waarde terug */
	public static function get_display_value( array $field, $raw_value ) {
		switch ( $field['type'] ) {
			case 'radio':
			case 'select':
			case 'button_group':
				$value = $field['options'][ $raw_value ] ?? '';
				break;
			case 'checkbox_list':
				$value = implode( ', ', array_map( fn( string $value ): string => $field['options'][ $value ], $raw_value ) );
				break;
			case 'checkbox':
			case 'switch':
				$value = boolval( $raw_value ) ? __( 'Ja', 'siw' ) : __( 'Nee', 'siw' ); // TODO: on_label en off_label gebruiken voor switch
				break;
			default:
				$value = $raw_value;
		}
		return $value;
	}

}
