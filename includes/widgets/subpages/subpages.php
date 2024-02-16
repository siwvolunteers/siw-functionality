<?php declare(strict_types=1);

namespace SIW\Widgets;

/**
 * Widget Name: SIW: Subpagina's
 * Description: Toont subpagina's
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Subpages extends Widget {

	#[\Override]
	protected function get_name(): string {
		return __( "Subpagina's", 'siw' );
	}

	#[\Override]
	protected function get_description(): string {
		return __( "Toont subpagina's", 'siw' );
	}

	#[\Override]
	protected function get_template_id(): string {
		return $this->get_id();
	}

	#[\Override]
	protected function get_dashicon(): string {
		return 'networking';
	}

	#[\Override]
	protected function supports_title(): bool {
		return false;
	}

	#[\Override]
	protected function supports_intro(): bool {
		return false;
	}

	#[\Override]
	public function get_template_variables( $instance, $args ) {

		$parent_id = get_the_ID();
		if ( false === $parent_id ) {
			return [];
		}

		/** @var \WP_Post[]|false */
		$page_ids = get_posts(
			[
				'post_type'      => 'page',
				'post_parent'    => $parent_id,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'fields'         => 'id',
				'posts_per_page' => -1,
			]
		);

		if ( false === $page_ids || 0 === count( $page_ids ) ) {
			return [];
		}

		foreach ( $page_ids as $page_id ) {
			$subpages[] = [
				'title'     => get_the_title( $page_id ),
				'permalink' => get_permalink( $page_id ),
				'image'     => get_the_post_thumbnail( $page_id ),
				'excerpt'   => get_the_excerpt( $page_id ),
			];
		}

		return [
			'subpages' => $subpages,
		];
	}

	#[\Override]
	public function initialize() {
		$this->register_frontend_styles(
			[
				[
					self::get_asset_handle(),
					self::get_style_asset_url(),
					[],
					SIW_PLUGIN_VERSION,
				],
			]
		);
	}
}
