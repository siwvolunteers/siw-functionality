<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Widget met quote van deelnemer */
add_action( 'init', function() {
	register_widget( 'SIW_Quote' );
}, 99 );

class SIW_Quote extends \TDP\Widgets_Helper {

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
/* Widget toevoegen aan Pagebuilder-tab (inclusief eigen icoon) */
add_filter('siteorigin_panels_widgets', function ( $widgets ) {
	$widgets['SIW_Quote']['groups'] = array('siw');
	$widgets['SIW_Quote']['icon'] = 'dashicons dashicons-editor-quote';
	return $widgets;
} );
