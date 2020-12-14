<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Util;

/**
 * Widget met formulier voor Snel Zoeken
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data 
 * Widget Name: SIW: Snel Zoeken - formulier
 * Description: Toont zoekformulier
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Quick_Search_Form extends Widget {

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_id ='quick_search_form';

	/**
	 * {@inheritDoc}
	 */
	protected string $widget_dashicon = 'search';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Snel Zoeken - formulier', 'siw');
		$this->widget_description = __( 'Toont zoekformulier', 'siw' );		
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_widget_form() {
		$widget_forms = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
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
	
	/**
	 * {@inheritDoc}
	 */
	protected function get_template_parameters( array $instance, array $args, array $template_vars, string $css_name ): array {

		return [
			'result_page_url' => wp_make_link_relative( get_permalink( $instance['result_page'] ) ),
			'destinations' => [
				'id'      => 'bestemming',
				'name'    => 'bestemming',
				'options' => $this->get_destinations(),
			],
			'months'   => [
				'id'      => 'maand',
				'name'    => 'maand',
				'options' => $this->get_months(),
			],
			'i18n' => [
				'search' => __( 'Zoeken', 'siw' )
			]
		];

	}

	/**
	 * Haalt bestemmingen met beschikbare projecten op
	 * 
	 * @return array
	 */
	protected function get_destinations() : array {

		$categories = get_terms( [
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'meta_query' => [
				[
					'key'     => 'post_count',
					'value'   => 0,
					'compare' => '>',
				],
			],
		] );
	
		$destinations[] = [
			'value'    => '',
			'label'    => __( 'Waar wil je heen?', 'siw' ),
			'selected' => true,
		];
		foreach ( $categories as $category ) {
			$destinations[] = [
				'value' => $category->slug,
				'label' => $category->name,
			];
		}
		return $destinations;
	}
	
	/**
	 * Haalt maanden met beschikbare projecten op
	 * 
	 * @return array
	 */
	protected function get_months() : array {
		$terms = get_terms( [
			'taxonomy'   => 'pa_maand',
			'hide_empty' => true,
			'meta_query' => [
				[
					'key'     => 'post_count',
					'value'   => 0,
					'compare' => '>',
				],
			]
		]);
	
		$months[] = [
			'value'    => '',
			'label'    => __( 'Wanneer wil je weg?', 'siw' ),
			'selected' => true,
		];
		foreach ( $terms as $term ) {
			$months[] =[
				'value' => $term->slug,
				'label' => $term->name,
			];
		}
		return $months;
	}
}
