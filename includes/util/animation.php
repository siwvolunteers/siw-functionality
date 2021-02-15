<?php declare(strict_types=1);

namespace SIW\Util;

/**
 * Hulpfuncties t.b.v. animaties
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Animation {

	/** Genereert data-attributes */
	public static function generate_data_attributes( string $type, int $duration, int $delay, string $easing ) : array {
		$data_attributes = [
			'data-sal'          => $type,
			'data-sal-duration' => $duration,
			'data-sal-delay'    => $delay,
			'data-sal-easing'   => $easing
		];
		return $data_attributes;
	}

	/** Geeft opties voor duur terug */
	public static function get_duration_options() : array {
		for ( $t = 200; $t <= 2000; $t+=50 ) {
			$durations[ $t ] = sprintf( __( '%d ms', 'siw' ), $t );
		}
		return $durations;
	}

	/** Geeft opties voor vertraging terug */
	public static function get_delay_options() : array {
		$delays['none'] = __( 'Geen', 'siw' );
		for ( $t = 100; $t <= 1000; $t+=50 ) {
			$delays[ $t ] = sprintf( __( '%d ms', 'siw' ), $t );
		}
		return $delays;
	}

	/** Geeft opties voor easing terug */
	public static function get_easing_options() : array {
		return siw_get_data( 'animation/easings' );
	}

	/** Geeft animatietypes terug */
	public static function get_types() : array {
		return siw_get_data( 'animation/types' );
	}
}
