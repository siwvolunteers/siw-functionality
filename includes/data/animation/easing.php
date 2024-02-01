<?php declare(strict_types=1);

namespace SIW\Data\Animation;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Easing: string implements Labels {

	use Enum_List;

	case LINEAR = 'linear';
	case EASE = 'ease';
	case EASE_IN = 'ease-in';
	case EASE_OUT = 'ease-out';
	case EASE_IN_OUT = 'ease-in-out';
	case EASE_IN_CUBIC = 'ease-in-cubic';
	case EASE_OUT_CUBIC = 'ease-out-cubic';
	case EASE_IN_OUT_CUBIC = 'ease-in-out-cubic';
	case EASE_IN_CIRC = 'ease-in-circ';
	case EASE_OUT_CIRC = 'ease-out-circ';
	case EASE_IN_OUT_CIRC = 'ease-in-out-circ';
	case EASE_IN_EXPO = 'ease-in-expo';
	case EASE_OUT_EXPO = 'ease-out-expo';
	case EASE_IN_OUT_EXPO = 'ease-in-out-expo';
	case EASE_IN_QUAD = 'ease-in-quad';
	case EASE_OUT_QUAD = 'ease-out-quad';
	case EASE_IN_OUT_QUAD = 'ease-in-out-quad';
	case EASE_IN_QUART = 'ease-in-quart';
	case EASE_OUT_QUART = 'ease-out-quart';
	case EASE_IN_OUT_QUART = 'ease-in-out-quart';
	case EASE_IN_QUINT = 'ease-in-quint';
	case EASE_OUT_QUINT = 'ease-out-quint';
	case EASE_IN_OUT_QUINT = 'ease-in-out-quint';
	case EASE_IN_SINE = 'ease-in-sine';
	case EASE_OUT_SINE = 'ease-out-sine';
	case EASE_IN_OUT_SINE = 'ease-in-out-sine';
	case EASE_IN_BACK = 'ease-in-back';
	case EASE_OUT_BACK = 'ease-out-back';
	case EASE_IN_OUT_BACK = 'ease-in-out-back';

	#[\Override]
	public function label(): string {
		// Omzetten kebab-case naar camelCase
		return lcfirst( str_replace( '-', '', ucwords( $this->value, '-' ) ) );
	}
}
