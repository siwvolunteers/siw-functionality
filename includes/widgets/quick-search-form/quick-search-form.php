<?php

namespace SIW\Widgets;

use SIW\HTML;
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
	protected $widget_id ='quick_search_form';

	/**
	 * {@inheritDoc}
	 */
	protected $widget_dashicon = 'search';

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
	protected function get_content( array $instance, array $args, array $template_vars, string $css_name ) { 
		$result_page_url = wp_make_link_relative( get_permalink( $instance['result_page'] ) );
		ob_start();
		?>
		<div>
			<form id="siw_quick_search" method="get" action="<?= esc_url( $result_page_url );?>">
				<ul>
					<li><?= HTML::generate_field( 'select', [ 'name' => 'bestemming', 'id' => 'bestemming', 'class' => 'select-css', 'options' => $this->get_destinations() ] );?></li>
					<li><?= HTML::generate_field( 'select', [ 'name' => 'maand', 'id' => 'maand', 'class' => 'select-css', 'options' => $this->get_months() ] );?></li>
					<li><?= HTML::generate_field( 'submit', [ 'value' => __( 'Zoeken', 'siw' ), 'class' => 'button' ] );?></li>
				</ul>
			</form>
		</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}

	/**
	 * Haalt bestemmingen met beschikbare projecten op
	 * 
	 * @return array
	 */
	protected function get_destinations() {

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
	
		$destinations = [
			'' => __( 'Waar wil je heen?', 'siw' ),
		];
		foreach ( $categories as $category ) {
			$destinations[ $category->slug ] = $category->name;
		}
		return $destinations;
	}
	
	/**
	 * Haalt maanden met beschikbare projecten op
	 * 
	 * @return array
	 */
	protected function get_months() {
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
	
		$months = [
			'' => __( 'Wanneer wil je weg?', 'siw' )
		];
		foreach ( $terms as $term ) {
			$months[ $term->slug ] = $term->name;
		}
		return $months;
	}
}
