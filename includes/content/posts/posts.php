<?php declare(strict_types=1);

namespace SIW\Content\Posts;

abstract class Posts {

	abstract protected static function get_post_class(): string;

	abstract protected static function get_post_type(): string;

	abstract protected static function get_default_args(): array;

	public static function get() {

		$args = static::get_default_args();
		$args['post_type'] = static::get_post_type();

		$posts = get_posts( $args );

		return array_map(
			fn( \WP_Post $post ) => new ( static::get_post_class() )( $post ),
			$posts
		);
	}
}
