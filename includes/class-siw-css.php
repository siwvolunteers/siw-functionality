<?php

/**
 * Hulpfuncties t.b.v. css
 *
 * @package   SIW
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class SIW_CSS {

	/**
	 * Genereert reponsive classes
	 *
	 * @param int $column_size
	 * @param int $tablet_size
	 * @param int $mobile_size
	 * @return string
	 */
	public static function generate_responsive_class( int $column_size, int $tablet_size = null, int $mobile_size = null ) {
		
		if ( is_int( $column_size ) ) {
			$class = "col-md-{$column_size}";
		}
		else {
			$class = 'col-md-12';
		}

		if ( $tablet_size && is_int( $tablet_size ) ) {
			$class .= " col-sm-{$tablet_size}";
		}

		if ( $mobile_size && is_int( $mobile_size ) ) {
			$class .= " col-ss-{$mobile_size}";
		}
		return $class;
	}

}
