<?php

namespace SIW\Compatibility;

/**
* Aanpassingen voor Meta Box
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://metabox.io/
 * @since     3.0.0
 */
class Meta_Box {

	/**
	 * Init
	 */
	public static function init() {
		if ( ! class_exists( '\MBAIO\Loader' ) ) {
			return;
		}
		$self = new self();
		add_filter( 'mb_aio_extensions', [ $self, 'select_extensions'] );
		add_filter( 'mb_aio_show_settings', '__return_false' );
		add_action( 'admin_init', [ $self, 'remove_dashboard_widget' ] );
		add_filter( 'rwmb_normalize_time_field', [ $self, 'set_default_time_options'] );
		add_filter( 'rwmb_normalize_date_field', [ $self, 'set_default_date_options'] );
		add_filter( 'rwmb_normalize_switch_field', [ $self, 'set_default_switch_options'] );
		add_filter( 'rwmb_group_sanitize', [ $self, 'sanitize_group' ], 10, 4 );
	}

	/**
	 * Selecteert de gebruikte extensies
	 *
	 * @param array $extensions
	 * @return array
	 */
	public function select_extensions( array $extensions ) {
		$extensions = [
			'mb-admin-columns',
			'mb-settings-page',
			'meta-box-columns',
			'meta-box-conditional-logic',
			'meta-box-geolocation',
			'meta-box-group',
			'meta-box-include-exclude',
			'meta-box-tabs',
			'meta-box-text-limiter',
			//'mb-frontend-submission',
		];
		return $extensions;
	}

	/**
	 * Verwijdert dashboard widget
	 */
	public function remove_dashboard_widget() {
		remove_meta_box( 'meta_box_dashboard_widget', 'dashboard', 'normal' );
	}

	/**
	 * Zet standaardeigenschappen van tijdvelden
	 *
	 * @param array $field
	 * @return array
	 */
	public function set_default_time_options( array $field ) {
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
		$field = wp_parse_args_recursive( $defaults, $field );
		return $field;
	}

	/**
	 * Zet standaardeigenschappen van datumvelden
	 *
	 * @param array $field
	 * @return array
	 */
	public function set_default_date_options( array $field ) {
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
		$field = wp_parse_args_recursive( $defaults, $field );
		return $field;
	}

	/**
	 * Zet standaardeigenschappen van switchvelden
	 *
	 * @param array $field
	 * @return array
	 */
	public function set_default_switch_options( array $field ) {
		$defaults = [
			'style'     => 'square',
		];
		$field = wp_parse_args_recursive( $defaults, $field );
		return $field;
	}

	/**
	 * Sanitize velden in MB Group
	 *
	 * @param array $values
	 * @param array $group
	 * @param array $old_value
	 * @param string $object_id
	 * @return array
	 */
	public function sanitize_group( $values, $group, $old_value = null, $object_id = null ) {
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
