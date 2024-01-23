<?php declare(strict_types=1);

namespace SIW\Jobs;

use SIW\Attributes\Add_Filter;
use SIW\Base;

abstract class Batch_Job extends Base {

	private const GROUP = 'siw_batch_job';

	final protected static function get_id(): string {
		$class_name_components = explode( '\\', static::class );
		return strtolower( end( $class_name_components ) );
	}

	abstract protected function get_name(): string;

	abstract public function start(): void;

	final protected function enqueue_items( array $items, string $hook ) {
		array_walk(
			$items,
			fn( $item ) => as_enqueue_async_action(
				$hook,
				[ 'item' => $item ],
				self::GROUP
			)
		);
	}

	#[Add_Filter( 'woocommerce_debug_tools' )]
	final public function add_action_to_wc_debug_tools( array $tools ): array {
		$tools[ "siw_{$this->get_id()}" ] = [
			'name'     => "SIW: {$this->get_name()}",
			'button'   => __( 'Starten', 'siw' ),
			'desc'     => '',
			'callback' => [ $this, 'start' ],
		];
		return $tools;
	}
}
