<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Animatie-types
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'fade'        => __( 'Fade', 'siw' ),
	'slide-up'    => __( 'Slide up', 'siw' ),
	'slide-down'  => __( 'Slide down', 'siw' ),
	'slide-left'  => __( 'Slide left', 'siw' ),
	'slide-right' => __( 'Slide right', 'siw' ),
	'zoom-in'     => __( 'Zoom in', 'siw' ),
	'zoom-out'    => __( 'Zoom out', 'siw' ),
	'flip-up'     => __( 'Flip up', 'siw' ),
	'flip-down'   => __( 'Flip down', 'siw' ),
	'flip-left'   => __( 'Flip left', 'siw' ),
	'flip-right'  => __( 'Flip right', 'siw' ),
];
return $data;