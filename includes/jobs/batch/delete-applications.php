<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Job_Frequency;
use SIW\Facades\WooCommerce;
use SIW\Jobs\Scheduled_Job;

class Delete_Applications extends Scheduled_Job {

	private const ACTION_HOOK = self::class;
	private const MAX_AGE_APPLICATIONS = 12 * MONTH_IN_SECONDS;

	#[\Override]
	protected function get_frequency(): Job_Frequency {
		return Job_Frequency::WEEKLY;
	}

	#[\Override]
	public function get_name(): string {
		return __( 'Verwijder aanmeldingen', 'siw' );
	}

	#[\Override]
	public function start(): void {
		$args = [
			'limit'        => -1,
			'return'       => 'ids',
			'type'         => 'shop_order',
			'date_created' => '<' . ( time() - self::MAX_AGE_APPLICATIONS ),
		];
		$this->enqueue_items( WooCommerce::get_orders( $args ), self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function delete_application( int $order_id ) {
		$order = WooCommerce::get_order( $order_id );
		if ( ! is_a( $order, \WC_Order::class ) ) {
			return false;
		}
		$refunds = $order->get_refunds();
		foreach ( $refunds as $refund ) {
			$refund->delete();
		}
		$order->delete( true );
	}
}
