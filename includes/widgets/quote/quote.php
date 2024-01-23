<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Data\Country;
use SIW\Elements\Blockquote;

/**
 * Widget Name: SIW: Quote
 * Description: Toont quote van deelnemer
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Quote extends Widget {

	private const CONTINENT_TAXONOMY = 'siw_story_continent';
	private const PROJECT_TYPE_TAXONOMY = 'siw_story_project_type';

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'quote';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Quote', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont quote van deelnemer', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'editor-quote';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function get_widget_fields(): array {
		$widget_form = [
			'continent'    => [
				'type'    => 'select',
				'label'   => __( 'Continent', 'siw' ),
				'options' => $this->get_taxonomy_options( self::CONTINENT_TAXONOMY ),
			],
			'project_type' => [
				'type'    => 'select',
				'label'   => __( 'Projectsoort', 'siw' ),
				'options' => $this->get_taxonomy_options( self::PROJECT_TYPE_TAXONOMY ),
			],
		];
		return $widget_form;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {
		$quote = $this->get_quote( $instance['continent'], $instance['project_type'] );

		if ( is_null( $quote ) ) {
			return [];
		}

		$blockquote = Blockquote::create()
			->set_quote( $quote['quote'] )
			->set_name( $quote['name'] )
			->set_source( "{$quote['project_type']} | {$quote['country']}" );

		return [
			'content' => $blockquote->generate(),
		];
	}

	/** {@inheritDoc} */
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
	protected function get_taxonomy_options( string $taxonomy ): array {
		$terms = get_terms( $taxonomy );
		$options[''] = __( 'Alle', 'siw' );
		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}
		return $options;
	}

	protected function get_quote( string $continent, string $project_type ): ?array {
		$tax_query = [];
		if ( ! empty( $continent ) ) {
			$tax_query[] = [
				'taxonomy' => self::CONTINENT_TAXONOMY,
				'terms'    => $continent,
				'field'    => 'slug',
			];
		}
		if ( ! empty( $project_type ) ) {
			$tax_query[] = [
				'taxonomy' => self::PROJECT_TYPE_TAXONOMY,
				'terms'    => $project_type,
				'field'    => 'slug',
			];
		}

		$query_args = [
			'post_type'      => 'siw_story',
			'posts_per_page' => 1,
			'orderby'        => 'rand',
			'fields'         => 'ids',
			'tax_query'      => $tax_query,
		];
		$post_ids = get_posts( $query_args );

		if ( empty( $post_ids ) ) {
			return null;
		}

		$post_id = $post_ids[0];
		$rows = siw_meta( 'rows', [], $post_id );

		$quotes = dot( $rows )->get( '*.quote' );

		$quote = [
			'quote'        => $quotes[ array_rand( $quotes, 1 ) ],
			'name'         => siw_meta( 'name', [], $post_id ),
			'country'      => Country::tryFrom( siw_meta( 'country', [], $post_id ) )?->label(),
			'project_type' => wp_get_post_terms( $post_id, self::PROJECT_TYPE_TAXONOMY )[0]->name,
		];
		return $quote;
	}
}
