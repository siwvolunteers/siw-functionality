<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
* Aanpassingen voor Meta Box
 * 
 * @copyright 2019-2020 SIW Internationale Vrijwilligersprojecten
 * @see       https://metabox.io/
 * @since     3.0.0
 */
class Meta_Box {

	/** Init */
	public static function init() {
		if ( ! is_plugin_active( 'meta-box-aio/meta-box-aio.php' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'mb_aio_extensions', [ $self, 'select_extensions'] );
		add_filter( 'mb_aio_show_settings', '__return_false' );
		add_filter( 'rwmb_normalize_time_field', [ $self, 'set_default_time_options'] );
		add_filter( 'rwmb_normalize_date_field', [ $self, 'set_default_date_options'] );
		add_filter( 'rwmb_normalize_switch_field', [ $self, 'set_default_switch_options'] );
		add_filter( 'rwmb_normalize_wysiwyg_field', [ $self, 'set_default_wysiwyg_options'] );
		add_filter( 'rwmb_group_sanitize', [ $self, 'sanitize_group' ], 10, 4 );
	}

	/** Selecteert de gebruikte extensies */
	public function select_extensions() {
		$extensions = [
			'mb-admin-columns',
			'mb-frontend-submission',
			'mb-settings-page',
			'meta-box-columns',
			'meta-box-conditional-logic',
			'meta-box-geolocation',
			'meta-box-group',
			'meta-box-include-exclude',
			'meta-box-tabs',
			'meta-box-text-limiter',
		];
		return $extensions;
	}

	/** Zet standaardeigenschappen van tijdvelden
	 * @todo kan weg na introductie HTML5 velden
	 */
	public function set_default_time_options( array $field ) : array {
		$defaults = [
			'pattern'    => '([01]?[0-9]|2[0-3]):[0-5][0-9]',
			'inline'     => false,
			'js_options' => [
				'stepMinute'      => 15,
				'controlType'     => 'select',
				'showButtonPanel' => false,
				'oneLine'         => true,
			],
		];
		return wp_parse_args_recursive( $defaults, $field );
	}

	/** Zet standaardeigenschappen van datumvelden
	 * @todo kan weg na introductie HTML5 velden
	 */
	public function set_default_date_options( array $field ) : array {
		$defaults = [
			'label_description' => 'jjjj-mm-dd',
			'placeholder'       => 'jjjj-mm-dd',
			'js_options'        => [
				'dateFormat'      => 'yy-mm-dd',
				'showButtonPanel' => false,
			],
			'pattern'    =>'(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))',
			'attributes' => [
				'autocomplete' => 'off',
			],
		];
		return wp_parse_args_recursive( $defaults, $field );
	}

	/** Zet standaardeigenschappen van switchvelden */
	public function set_default_switch_options( array $field ) : array {
		$defaults = [
			'style' => 'square',
		];
		return wp_parse_args_recursive( $defaults, $field );
	}

	/** Zet standaardeigenschappen van wysiwyg */
	public function set_default_wysiwyg_options( array $field ) : array {
		$defaults = [
			'raw'      => true,
			'options'  => [
				'teeny'         => true,
				'dfw'           => false,
				'media_buttons' => false,
				'textarea_rows' => 5,
			],
		];
		return wp_parse_args_recursive( $field, $defaults );
	}

	/** Sanitize velden in MB Group */
	public function sanitize_group( array $values, array $group, $old_value = null, $object_id = null ) : array {
		foreach ( $group['fields'] as $field ) {
			$key = $field['id'];
			$old = isset( $old_value[ $key ] ) ? $old_value[ $key ] : null;
			$new = isset( $values[ $key ] ) ? $values[ $key ] : null;

			if ( $field['clone'] ) {
				$new = \RWMB_Clone::value( $new, $old, $object_id, $field );
			}
			elseif ( in_array( $field['type'], ['date', 'datetime'] ) && is_array( $new ) ) {
				if ( isset( $new['timestamp'] ) ) {
					$new['timestamp'] = floor( abs( (float) $new['timestamp'] ) );
				}
				if ( isset( $new['formatted'] ) ) {
					$new['formatted'] = sanitize_text_field( $new['formatted'] );
				}
			}
			else {
				$new = \RWMB_Field::call( $field, 'value', $new, $old, $object_id );
				$new = \RWMB_Field::filter( 'sanitize', $new, $field, $old, $object_id );
			}
			$sanitized[ $key ] = \RWMB_Field::filter( 'value', $new, $field, $old, $object_id );
		}
		return $sanitized;
	}
}
