<?php declare(strict_types=1);

namespace SIW\Data\Animation;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Type: string implements Labels {

	use Enum_List;

	case FADE = 'fade';
	case SLIDE_UP = 'slide-up';
	case SLIDE_DOWN = 'slide-down';
	case SLIDE_LEFT = 'slide-left';
	case SLIDE_RIGHT = 'slide-right';
	case ZOOM_IN = 'zoom-in';
	case ZOOM_OUT = 'zoom-out';
	case FLIP_UP = 'flip-up';
	case FLIP_DOWN = 'flip-down';
	case FLIP_LEFT = 'flip-left';
	case FLIP_RIGHT = 'flip-right';

	#[\Override]
	public function label(): string {
		return match ( $this ) {
			self::FADE => __( 'Fade', 'siw' ),
			self::SLIDE_UP => __( 'Slide up', 'siw' ),
			self::SLIDE_DOWN => __( 'Slide down', 'siw' ),
			self::SLIDE_LEFT => __( 'Slide left', 'siw' ),
			self::SLIDE_RIGHT => __( 'Slide right', 'siw' ),
			self::ZOOM_IN => __( 'Zoom in', 'siw' ),
			self::ZOOM_OUT => __( 'Zoom out', 'siw' ),
			self::FLIP_UP => __( 'Flip up', 'siw' ),
			self::FLIP_DOWN => __( 'Flip down', 'siw' ),
			self::FLIP_LEFT => __( 'Flip left', 'siw' ),
			self::FLIP_RIGHT => __( 'Flip right', 'siw' ),
		};
	}
}
