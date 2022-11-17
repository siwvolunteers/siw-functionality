<?php declare(strict_types=1);

namespace SIW\Interfaces\Content;

/**
 * Interfaces voor content types met een custom slug
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Admin_Order {

	/** Volgorde (`ASC`|`DESC`) */
	public function get_admin_order(): string;

	/** Volgorde (date, title, meta) */
	public function get_admin_orderby(): string;

	/** Meta key voor volgorde */
	public function get_admin_orderby_meta_key(): string;
}
