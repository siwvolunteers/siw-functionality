<?php declare(strict_types=1);

namespace SIW\Content\Post;

use SIW\Facades\Meta_Box;

abstract class Post {

	protected \WP_Post $post;

	public function __construct( \WP_Post|int $post ) {
		$this->post = get_post( $post );
	}

	public function get_id(): int {
		return $this->post->ID;
	}

	public function get_title(): string {
		return get_the_title( $this->post );
	}

	public function get_permalink(): string {
		return get_permalink( $this->post );
	}

	public function get_excerpt(): string {
		return $this->post->post_excerpt;
	}

	public function get_thumbnail_id(): int {
		return 0;
	}

	public function is_active(): bool {
		return true;
	}

	public function should_delete(): bool {
		return false;
	}

	protected function get_meta( string $key, array $args = [] ): mixed {
		return Meta_Box::get_meta( $key, $args, $this->get_id() );
	}

	protected function set_meta( string $key, mixed $value, array $args = [] ) {
		Meta_Box::set_meta( $this->get_id(), $key, $value, $args );
	}
}
