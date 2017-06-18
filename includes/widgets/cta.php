<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Widget met contactgegevens */
add_action( 'widgets_init', function() {
	register_widget( 'SIW_CTA' );
});

class SIW_CTA extends \TDP\Widgets_Helper {

	public function __construct() {
		$this->widget_name = __( 'SIW: Call to action', 'siw' );
		$this->widget_description = __( 'Toont CTA met knop' );
		$this->widget_fields = array(
			array(
				'id'   => 'title',
				'name' => __( 'Titel', 'siw' ),
				'type' => 'text',
			),
			array(
				'id' 	=> 'heading',
				'name'	=> __( 'Heading', 'siw'),
				'type'	=> 'select',
				'options' => array(
					'h2' => 'h2',
					'h4' => 'h4',
				),
			),
			array(
				'id'   => 'button_text',
				'name' => __( 'Tekst voor knop', 'siw' ),
				'type' => 'text',
			),
			array(
				'id'   => 'button_page',
				'name' => __( 'Pagina voor knop', 'siw' ),
				'type' => 'select',
				'options' => siw_get_pages(),
			),
			array(
				'id' 	=> 'align',
				'name'	=> __( 'Uitlijning', 'siw'),
				'type'	=> 'select',
				'options' => array(
					'left' 		=> __( 'Links', 'siw' ),
					'center'	=> __( 'Midden', 'siw' ),
					'right'		=> __( 'Rechts', 'siw' ),
				),
				'std'	=> 'center',
			),
		);
		$this->init();
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		echo '<div class="siw-cta-title" style="text-align:' . esc_attr( $instance['align'] ) . '">';
		echo '<' . esc_attr($instance['heading']) . '>' . esc_html( $instance['title'] ) . '</'. esc_attr($instance['heading']) . '>';
		echo '</div>';
		echo '<div class="siw-cta-button" style="text-align:' . esc_attr( $instance['align'] ) . '">';
		echo '<a href="'. get_permalink( $instance['button_page']) . '" class="kad-btn">' . esc_html( $instance['button_text'] ) . '</a>';
		echo '</div>';
		echo $args['after_widget'];

	}
}


/* Widget toevoegen aan Pagebuilder-tab (inclusief eigen icoon) */
add_filter('siteorigin_panels_widgets', function ( $widgets ) {
	$widgets['SIW_CTA']['groups'] = array('siw');
	$widgets['SIW_CTA']['icon'] = 'dashicons dashicons-megaphone';
	return $widgets;
} );
