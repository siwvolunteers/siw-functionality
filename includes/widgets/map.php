<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Widget met contactgegevens */
add_action( 'widgets_init', function() {
	register_widget( 'SIW_Map' );
});

class SIW_Map extends \TDP\Widgets_Helper {

	public function __construct() {
		$this->widget_name = __( 'SIW: Mapplic kaart', 'siw' );
		$this->widget_description = __( 'Toont Mapplic-kaart' );
		$this->widget_fields = array(
			array(
				'id'   => 'title',
				'name' => __( 'Titel', 'siw' ),
				'type' => 'text',
			),
			array(
				'id'   => 'map',
				'name' => __( 'Kaart', 'siw' ),
				'type' => 'select',
				'options' => siw_get_mapplic_maps(),
			),
		);
		$this->init();
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo do_shortcode( sprintf('[mapplic id="%s" shortcode="true"]', $instance['map']) );
		echo $args['after_widget'];
	}
}
