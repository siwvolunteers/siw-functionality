<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\Elements\Cookie_Notice as Cookie_Notice_Element;

/**
 * SIW Cookie Notice
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class Cookie_Notice extends Base {

	#[Action( 'wp_footer' )]
	/** Voegt cookie notice toe aan footer */
	public function add_cookie_notice() {
		Cookie_Notice_Element::create()->render();
	}
}
