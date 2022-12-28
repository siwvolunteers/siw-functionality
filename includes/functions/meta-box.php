<?php declare(strict_types=1);

use Pharaonic\DotArray\DotArray;

/**
 * Wrapper functions om MetaBox functies
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */


/** Haalt gegevens van MetaBox op */
function siw_get_meta_box( string $meta_box_id ): ?\RW_Meta_Box {

	if ( ! function_exists( 'rwmb_get_registry' ) ) {
		return null;
	}

	$meta_box_registry = rwmb_get_registry( 'meta_box' );
	$meta_box = $meta_box_registry->get( $meta_box_id );
	return is_a( $meta_box, \RW_Meta_Box::class ) ? $meta_box : null;
}

/** Wrapper om rwmb_meta */
function siw_meta( string $key, array $args = [], int $post_id = null ) {

	if ( ! function_exists( 'rwmb_meta' ) ) {
		return null;
	}

	$keys = explode( '.', $key );
	$value = rwmb_meta( $keys[0], $args, $post_id );

	unset( $keys[0] );
	if ( ! empty( $keys ) ) {
		$dot = new DotArray( $value );
		$key = implode( '.', $keys );
		$value = $dot->get( $key );
	}

	return $value;
}

/** Wrapper om rwmb_set_meta */
function siw_set_meta( int $post_id, string $key, mixed $value, array $args = [] ): void {

	if ( ! function_exists( 'rwmb_set_meta' ) ) {
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

	$dot = new DotArray( $current_value );
	$key = implode( '.', $keys );
	$dot->set( $key, $value );

	rwmb_set_meta( $post_id, $meta_key, $dot->all(), $args );
}
