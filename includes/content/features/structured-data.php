<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\Interfaces\Content\Structured_Data as I_Structured_Data;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * Voegt teller met actieve posts toe aan admin menu
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Structured_Data extends Base {

	/** Init */
	private function __construct( protected I_Type $type, protected I_Structured_Data $structured_data ) {}

	#[Action( 'wp_footer' )]
	/** Voegt structured data toe */
	public function add_structured_data() {

		if ( ! is_singular( $this->type->get_post_type() ) ) {
			return;
		}
		$structured_data = $this->structured_data->get_structured_data( get_the_ID() );
		echo $structured_data->to_script(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
