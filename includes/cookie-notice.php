<?php declare(strict_types=1);

namespace SIW;

use SIW\Elements\Cookie_Notice as Cookie_Notice_Element;

/**
 * SIW Cookie Notice
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class Cookie_Notice {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wp_footer', [ $self, 'add_cookie_notice'] );
	}

	/** Voegt cookie notice toe aan footer */
	public function add_cookie_notice() {
		Cookie_Notice_Element::create()->render();
	}
}
