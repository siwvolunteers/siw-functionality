<?php declare(strict_types=1);

namespace SIW\Widgets;

/**
 * Widget met subpagina's
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: CTA
 * Description: Toont subpagina's
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Subpages extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'subpages';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( "Subpagina's", 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( "Toont subpagina's", 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'networking';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return false;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {

		$parent_id = get_the_ID();
		if ( false === $parent_id ) {
			return [];
		}

		/** @var \WP_Post[]|false */
		$page_ids = get_posts(
			[
				'post_type'   => 'page',
				'post_parent' => $parent_id,
				'orderby'     => 'menu_order',
				'fields'      => 'id',
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
			'i18n'     => [
				'read_more' => __( 'Lees meer', 'siw' ),
			],
			'subpages' => $subpages,
		];
	}

	/** {@inheritDoc} */
	public function initialize() {
		$this->register_frontend_styles(
			[
				[
					'siw-widget-subpages',
					SIW_ASSETS_URL . 'css/widgets/subpages.css',
					[],
					SIW_PLUGIN_VERSION,
				],
			]
		);
	}
}
