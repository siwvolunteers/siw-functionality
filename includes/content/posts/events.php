<?php declare(strict_types=1);

namespace SIW\Content\Posts;

use SIW\Content\Post\Event;
use SIW\Content\Post_Types\Event as Event_Post_Type;

class Events extends Posts {

	protected static function get_post_type(): string {
		return Event_Post_Type::get_post_type();
	}

	protected static function get_post_class(): string {
		return Event::class;
	}

	protected static function get_default_args(): array {
		return [
			'meta_key' => 'event_date',
			'orderby'  => 'meta_value',
			'order'    => 'ASC',
		];
	}

	/** @return Event[] */
	public static function get_future_events(): array {
		return array_values(
			array_filter(
				static::get(),
				fn( Event $event ) => $event->is_active()
			)
		);
	}

	/** @return Event[] */
	public static function get_future_info_days(): array {
		return array_values(
			array_filter(
				static::get(),
				fn( Event $event ) => $event->is_info_day()
			)
		);
	}
}
