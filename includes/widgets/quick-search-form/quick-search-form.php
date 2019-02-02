<?php
/*
 * 
 * @widget_data 
 * Widget Name: SIW: Snel Zoeken - formulier
 * Description: Toont zoekformulier
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget met formulier voor Snel Zoeken
 *
 * @package   SIW\Widgets
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Formatting
 */
class SIW_Widget_Quick_Search_Form extends SIW_Widget {

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
		];
		return $widget_forms;
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function get_content( $instance, $args, $template_vars, $css_name ) { 
		$result_page_id = siw_get_setting( 'quick_search_result_page' );
		$result_page_url = wp_make_link_relative( get_permalink( $result_page_id ) );
		ob_start();
		?>
		<div>
			<form id="siw_quick_search" method="get" action="<?= esc_url( $result_page_url );?>">
				<ul>
					<li><?= SIW_Formatting::generate_field( 'select', [ 'name' => 'bestemming', 'id' => 'bestemming', 'options' => $this->get_destinations() ] );?></li>
					<li><?= SIW_Formatting::generate_field( 'select', [ 'name' => 'maand', 'id' => 'maand', 'options' => $this->get_months() ] );?></li>
					<li><?= SIW_Formatting::generate_field( 'submit', [ 'value' => __( 'Zoeken', 'siw' ) ] );?></li>
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
		] );
	
		$destinations = [
			'' => __( 'Waar wil je heen?', 'siw' ),
		];
		foreach ( $categories as $category ) {
			if ( 'uncategorized' != $category->slug && get_term_meta( $category->term_id, 'project_count', true ) > 0 ) {
				$destinations[ $category->slug ] = $category->name;
			}
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
		]);
	
		$months = [
			'' => __( 'Wanneer wil je weg?', 'siw' )
		];
		foreach ( $terms as $term ) {
			if ( get_term_meta( $term->term_id, 'project_count', true ) > 0 ) {
				$months[ $term->slug ] = $term->name; 
			}
		}
		return $months;
	}
}