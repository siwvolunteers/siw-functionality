<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Widget met quote van deelnemer */
add_action( 'init', function() {
	register_widget( 'SIW_Testimonial_Quote' );
}, 99 );

class SIW_Testimonial_Quote extends \TDP\Widgets_Helper {

	public function __construct() {
		$this->widget_name = __( 'SIW: Quote van deelnemer', 'siw' );
		$this->widget_description = __( 'Toont een willekeurige quote van deelnemer', 'siw' );
		$this->widget_fields = array(
			array(
				'id'   => 'title',
				'name' => __( 'Titel', 'siw' ),
				'type' => 'text',
				'std'  => __( 'Ervaringen van deelnemers', 'siw' ),
			),
			array(
				'id'      => 'cat',
				'name'    => __( 'Categorie', 'siw'),
				'type'    => 'select',
				'options' => siw_get_testimonial_quote_categories(),
			),
		);
		$this->init();
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$testimonial_quote = siw_get_testimonial_quote( $instance['cat'] );

		echo $args['before_widget'];
		echo '<div class="siw_quote_widget">';
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<div class="quote">
			<div class="text">
			"<?php echo esc_html( $testimonial_quote['quote'] );?>"
			</div>
			<div class="volunteer">
				<span class="name"><?php echo esc_html( $testimonial_quote['name'] );?></span>
				<span class="separator">&nbsp;|&nbsp;</span>
				<span class="category"><?php echo esc_html( $testimonial_quote['project'] );?></span>
			</div>
		</div><?php

		echo '</div>';
		echo $args['after_widget'];
	}
}
/*



	public function widget ( $args, $instance ) {
		extract( $args );

		$query = new WP_Query( array(
			'post_type'				=> 'testimonial',
			'testimonial-group'		=> $instance['cat'],
			'no_found_rows'			=> true,
			'posts_per_page'		=> 1,
			'orderby'				=> 'rand',
			'post_status'			=> 'publish',
			'ignore_sticky_posts'	=> true )
		);

		if ( $query->have_posts() ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			echo '<div class="siw_quote_widget">';
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			while ( $query->have_posts() ) : $query->the_post();
			global $post;
			$quote = get_the_content();
			$name = get_the_title();
			$project = get_post_meta( $post->ID, '_kad_testimonial_location', true );
			?>
			<div class="quote">
				<div class="text">
				"<?php echo esc_html( $quote );?>"
				</div>
				<div class="volunteer">
					<span class="name"><?php echo esc_html( $name );?></span>
					<span class="separator">&nbsp;|&nbsp;</span>
					<span class="category"><?php echo esc_html( $project );?></span>
				</div>
			</div>
		<?php endwhile; ?>
		<?php
		echo '</div>';
		echo $after_widget;

		wp_reset_postdata();
		}
	}
}
*/
