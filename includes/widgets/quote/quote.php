<?php

namespace SIW\Widgets;

/**
 * Widget met quote
 *
 * @copyright 2019-2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @widget_data
 * Widget Name: SIW: Quote
 * Description: Toont quote van deelnemer
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Quote extends Widget {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_id ='quote';

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	protected $widget_dashicon = 'editor-quote';

	/**
	 * {@inheritDoc}
	 */
	protected function set_widget_properties() {
		$this->widget_name = __( 'Quote', 'siw');
		$this->widget_description = __( 'Toont quote van deelnemer', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw'),
				'default' => __( 'Ervaringen van deelnemers', 'siw' ),
			],
			'category' => [
				'type'    => 'select',
				'label'   => __( 'Categorie', 'siw'),
				'options' => $this->get_categories(),
			]
		];
		return $widget_form;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_content( array $instance, array $args, array $template_vars, string $css_name ) {
		$quote = $this->get_quote( $instance['category'] );
		ob_start();
		?>
		<blockquote>
			<p><?php echo esc_html( $quote['quote'] );?></p>
			<footer><strong><?php echo esc_html( $quote['name'] );?></strong> | <?php echo esc_html( $quote['project'] );?></footer>
		</blockquote>
		<?php
		$content = ob_get_clean();
		return $content;
	}

	/**
	 * Geeft lijst van categorieën voor quotes terug
	 *
	 * @return array
	 */
	protected function get_categories() {
		$groups = get_terms( 'testimonial-group' );
		$categories[''] = __( 'Alle', 'siw' );
		foreach ( $groups as $group ) {
			$categories[ $group->slug ] = $group->name;
		}
		return $categories;
	}

	/**
	 * Geeft array met gegevens van een quote terug
	 *
	 * @param  string $category
	 * @return array
	 */
	protected function get_quote( $category = '' ) {

		$query_args = [
			'post_type'           => 'testimonial',
			'posts_per_page'      => 1,
			'post_status'         => 'publish',
			'orderby'             => 'rand',
			'fields'              => 'ids',
			'testimonial-group'   => $category,
		];
		$post_ids = get_posts( $query_args );

		if ( empty( $post_ids ) ) {
			return;
		}

		$post_id = $post_ids[0];
		$quote = [
			'quote'   => get_post_field('post_content', $post_id ),
			'name'    => get_the_title( $post_id ),
			'project' => get_post_meta( $post_id, '_kad_testimonial_location', true ),
		];
		return $quote;
	}


}
