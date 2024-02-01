<?php declare(strict_types=1);

namespace SIW\Data\Elements;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Chart_Type: string implements Labels {
	use Enum_List;

	case LINE = 'line';
	case BAR = 'bar';
	case AXIS_MIXED = 'axis-mixed';
	case SCATTER = 'scatter';
	case PIE = 'pie';
	case PERCENTAGE = 'percentage';
	case HEATMAT = 'heatmap';

	public function label(): string {
		return match ( $this ) {
			self::LINE => __( 'Lijn', 'siw' ),
			self::BAR => __( 'Staafdiagram', 'siw' ),
			self::AXIS_MIXED => __( 'Gemixed', 'siw' ),
			self::SCATTER => __( 'Scatter', 'siw' ),
			self::PIE => __( 'Taart', 'siw' ),
			self::PERCENTAGE => __( 'percentage', 'siw' ),
			self::HEATMAT => __( 'Heatmap', 'siw' ),
		};
	}
}
