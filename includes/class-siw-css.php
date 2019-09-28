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
		
		$class = "col-md-{$column_size}";

		if ( null !== $tablet_size ) {
			$class .= " col-sm-{$tablet_size}";
		}

		if ( null !== $mobile_size  ) {
			$class .= " col-ss-{$mobile_size}";
		}
		return $class;
	}

	/**
	 * Genereert css o.b.v. array met regels
	 *
	 * @param array $rules
	 * @return string
	 */
	public static function generate_inline_css( array $rules ) {
		$css = '';
		foreach ( $rules as $selector => $styles ) {
			$css .= $selector . '{';
			foreach ( $styles as $property => $value ) {
				$css .= $property . ':' . $value . ';';
			}
			$css .= '}';
		}
	
		return $css;
	}

}
