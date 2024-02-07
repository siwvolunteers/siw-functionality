<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;

/**
 * @see       https://metabox.io/
 */
class Meta_Box extends Base implements I_Plugin {

	#[Add_Filter( 'mb_aio_show_settings' )]
	private const SHOW_SETTINGS = false;

	#[Add_Filter( 'rwmb_excerpt_value' )]
	private const EXCERPT_VALUE = '__return_empty_string';

	#[\Override]
	public static function get_plugin_basename(): string {
		return 'meta-box-aio/meta-box-aio.php';
	}

	#[Add_Filter( 'mb_aio_extensions' )]
	public function select_extensions(): array {
		$extensions = [
			'mb-admin-columns',
			'mb-settings-page',
			'meta-box-columns',
			'meta-box-conditional-logic',
			'meta-box-geolocation',
			'meta-box-group',
			'meta-box-include-exclude',
			'meta-box-tabs',
		];
		return array_filter( $extensions );
	}

	#[Add_Filter( 'rwmb_field_class' )]
	public function set_field_class( string $class_name, string $type ): string {
		if ( in_array( $type, [ 'date', 'time' ], true ) ) {
			$class_name = \RWMB_Input_Field::class;
		}
		return $class_name;
	}

	#[Add_Filter( 'rwmb_normalize_time_field' )]
	#[Add_Filter( 'rwmb_normalize_date_field' )]
	public function set_date_time_sanitize_callback( array $field ): array {
		$defaults = [
			'sanitize_callback' => 'sanitize_text_field',
		];
		return wp_parse_args_recursive( $defaults, $field );
	}

	#[Add_Filter( 'rwmb_normalize_switch_field' )]
	public function set_default_switch_options( array $field ): array {
		$defaults = [
			'style' => 'square',
		];
		return wp_parse_args_recursive( $defaults, $field );
	}

	#[Add_Filter( 'rwmb_normalize_wysiwyg_field' )]
	public function set_default_wysiwyg_options( array $field ): array {
		$defaults = [
			'raw'     => true,
			'options' => [
				'teeny'         => true,
				'dfw'           => false,
				'media_buttons' => false,
				'textarea_rows' => 5,
			],
		];
		return wp_parse_args_recursive( $field, $defaults );
	}

	#[Add_Filter( 'rwmb_group_sanitize' )]
	public function sanitize_group( array $values, array $group, $old_value = null, $object_id = null ): array {
		foreach ( $group['fields'] as $field ) {
			$key = $field['id'];
			$old = $old_value[ $key ] ?? null;
			$new = $values[ $key ] ?? null;

			if ( null === $new ) {
				$sanitized[ $key ] = null;
				continue;
			}

			if ( $field['clone'] ) {
				$new = \RWMB_Clone::value( $new, $old, $object_id, $field );
			} elseif ( in_array( $field['type'], [ 'date', 'datetime' ], true ) && is_array( $new ) ) {
				if ( isset( $new['timestamp'] ) ) {
					$new['timestamp'] = floor( abs( (float) $new['timestamp'] ) );
				}
				if ( isset( $new['formatted'] ) ) {
					$new['formatted'] = sanitize_text_field( $new['formatted'] );
				}
			} else {
				$new = \RWMB_Field::call( $field, 'value', $new, $old, $object_id );
				$new = \RWMB_Field::filter( 'sanitize', $new, $field, $old, $object_id );
			}
			$sanitized[ $key ] = \RWMB_Field::filter( 'value', $new, $field, $old, $object_id );
		}
		return $sanitized;
	}

	#[Add_Filter( 'rwmb_get_value' )]
	public function render_shortcodes( $value, array $field, array $args, $object_id ) {
		if ( 'wysiwyg' === $field['type'] ) {
			$value = do_shortcode( $value );
		}
		return $value;
	}

	#[Add_Filter( 'rwmb_excerpt_field_meta' )]
	public function get_excerpt(): string {
		$post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
		return get_post_field( 'post_excerpt', $post_id );
	}
}
