<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Actions\Batch\Update_WooCommerce_Terms;
use SIW\Util;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Widget met formulier voor Snel Zoeken
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data 
 * Widget Name: SIW: Snel Zoeken - formulier
 * Description: Toont zoekformulier
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Quick_Search_Form extends Widget {

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'quick_search_form';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Snel Zoeken - formulier', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont zoekformulier', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return $this->get_id();
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'search';
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_forms = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw' ),
				'default' => __( 'Snel Zoeken', 'siw' ),
			],
			'result_page' => [
				'type'    => 'select',
				'label'   => __( 'Resultatenpagina', 'siw' ),
				'prompt'  => __( 'Selecteer een pagina', 'siw' ),
				'options' => Util::get_pages(), 
			],
		];
		return $widget_forms;
	}
	
	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {

		return [
			'result_page_url' => wp_make_link_relative( get_permalink( $instance['result_page'] ) ),
			'search_fields' => [
				[
					'id'      => Quick_Search_Results::DESTINATION,
					'name'    => Quick_Search_Results::DESTINATION,
					'options' => $this->get_taxonomy_options( Taxonomy_Attribute::CONTINENT()->value, __( 'Waar wil je heen?', 'siw' ) ),
				],
				[
					'id'      => Quick_Search_Results::MONTH,
					'name'    => Quick_Search_Results::MONTH,
					'options' => $this->get_taxonomy_options( Taxonomy_Attribute::MONTH()->value, __( 'Wanneer wil je weg?', 'siw' ) ),
				],
				[
					'id'     => Quick_Search_Results::DESTINATION,
					'name'   => Quick_Search_Results::DESTINATION,
					'options' => $this->get_taxonomy_options( Taxonomy_Attribute::DURATION()->value, __( 'Hoe lang wil je weg?', 'siw' ) ),
				]
			],
			'i18n' => [
				'search' => __( 'Zoeken', 'siw' )
			]
		];
	}

	/** Haalt lijst met opties per taxonomy op */
	protected function get_taxonomy_options( string $taxonomy, string $placeholder ): array {
		$terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			// 'meta_query' => [
			// 	[
			// 		'key'     => Update_WooCommerce_Terms::POST_COUNT_TERM_META,
			// 		'value'   => 0,
			// 		'compare' => '>',
			// 	],
			// ]
		]);
	
		$term_options[] = [
			'value'    => '',
			'label'    => $placeholder,
			'selected' => true,
		];
		foreach ( $terms as $term ) {
			$term_options[] =[
				'value' => $term->slug,
				'label' => $term->name,
			];
		}
		return $term_options;
	}
}
