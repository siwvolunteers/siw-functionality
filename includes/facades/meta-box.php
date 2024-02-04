<?php declare(strict_types=1);

namespace SIW\Facades;

use MetaBox\Support\Arr;

class Meta_Box {

	public static function get_meta_box( string $meta_box_id ): ?\RW_Meta_Box {

		if ( ! function_exists( 'rwmb_get_registry' ) ) {
						wp_trigger_error( __METHOD__, wp_sprintf( 'Functie %s bestaat niet', 'rwmb_set_meta' ) );
			return null;
		}

		$meta_box_registry = rwmb_get_registry( 'meta_box' );
		$meta_box = $meta_box_registry->get( $meta_box_id );
		return is_a( $meta_box, \RW_Meta_Box::class ) ? $meta_box : null;
	}

	public static function get_meta( string $key, array $args = [], int|string $post_id = null ) {

		if ( ! function_exists( 'rwmb_meta' ) ) {
			wp_trigger_error( __METHOD__, wp_sprintf( 'Functie %s bestaat niet', 'rwmb_meta' ) );
			return null;
		}

		$keys = explode( '.', $key );
		$value = rwmb_meta( $keys[0], $args, $post_id );

		unset( $keys[0] );

		if ( empty( $keys ) ) {
			return $value;
		}

		$key = implode( '.', $keys );
		$value = Arr::get( $value, $key );

		return $value;
	}

	public static function set_meta( int $post_id, string $key, mixed $value, array $args = [] ): void {

		if ( ! function_exists( 'rwmb_set_meta' ) ) {
			wp_trigger_error( __METHOD__, wp_sprintf( 'Functie %s bestaat niet', 'rwmb_set_meta' ) );
			return;
		}

		$keys = explode( '.', $key );

		if ( count( $keys ) === 1 ) {
			rwmb_set_meta( $post_id, $key, $value, $args );
			return;
		}

		$meta_key = $keys[0];
		unset( $keys[0] );

		$current_value = rwmb_meta( $meta_key, $args, $post_id );

		$key = implode( '.', $keys );
		Arr::set( $current_value, $key, $value );

		rwmb_set_meta( $post_id, $meta_key, $current_value, $args );
	}

	public static function get_option( string $key, mixed $default_value = null ): mixed {

		if ( ! function_exists( 'rwmb_meta' ) ) {
			wp_trigger_error( __METHOD__, wp_sprintf( 'Functie %s bestaat niet', 'rwmb_meta' ) );
			return null;
		}

		// Foutmelding bij aanroepen vóór init
		if ( 0 === did_action( 'init' ) && WP_DEBUG ) {
			wp_trigger_error( __METHOD__, 'Deze function werd te vroeg aangeroepen', E_USER_ERROR );
		}

		// Probeer waarde uit cache te halen
		$value = wp_cache_get( $key, __METHOD__ );
		if ( false !== $value ) {
			return $value;
		}

		$keys = explode( '.', $key );
		$options = get_option( SIW_OPTIONS_KEY );

		if ( empty( $keys ) ) {
			return $value;
		}

		$value = Arr::get( $options, $key );

		if ( empty( $value ) ) {
			return $default_value;
		}
		wp_cache_set( $key, $value, __METHOD__ );

		return $value;
	}
}
