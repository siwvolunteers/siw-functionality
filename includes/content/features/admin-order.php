<?php declare(strict_types=1);

namespace SIW\Content\Features;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\Interfaces\Content\Admin_Order as I_Admin_Order;
use SIW\Interfaces\Content\Type as I_Type;

/**
 * Volgorde in admin scherm
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Admin_Order extends Base {

	/** {@inheritDoc} */
	protected function __construct( protected I_Type $type, protected I_Admin_Order $admin_order ) {}

	/** Zet standaard volgorde voor admin scherm */
	#[Action( 'pre_get_posts' )]
	public function set_default_orderby( \WP_Query $query ) {

		// Afbreken
		if ( ! $query->is_admin || $this->type->get_post_type() !== $query->get( 'post_type' ) ) {
			return;
		}

		if ( empty( $query->get( 'orderby' ) ) ) {
			$query->set( 'orderby', $this->admin_order->get_admin_orderby() );
		}

		if ( empty( $query->get( 'meta_key' ) ) && 'meta_value' === $this->admin_order->get_admin_orderby() ) {
			$query->set( 'meta_key', $this->admin_order->get_admin_orderby_meta_key() );
		}

		if ( empty( $query->get( 'order' ) ) ) {
			$query->set( 'order', $this->admin_order->get_admin_order() );
		}
	}
}
