<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'widgets_init', function() {
	register_widget( 'SIW_Quick_Search' );
} );

class SIW_Quick_Search extends \TDP\Widgets_Helper {

	public function __construct() {
		$this->widget_name = __( 'SIW: Snel zoeken', 'siw' );
		$this->widget_description = __( 'Snel zoeken in groepsprojecten', 'siw' );
		$this->widget_fields = array(
			array(
				'id'   => 'title',
				'name' => __('Titel', 'siw'),
				'type' => 'text',
				'std'  => __('Blijf op de hoogte', 'siw'),
			),
		);
		$this->init();
	}

	public function widget( $args, $instance ) {

		$result_page_id = siw_get_setting( 'quick_search_result_page' );
		$result_page_url = wp_make_link_relative( get_permalink( $result_page_id ) );

		$categories = siw_get_quick_search_destinations();
		$months = siw_get_quick_search_months();
		
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}?>
		<div>
			<form id="siw_quick_search" method="get" action="<?php echo esc_url( $result_page_url );?>">
				<ul>
					<li><?php siw_render_field( 'select', array( 'name' => 'bestemming', 'id' => 'bestemming', 'options' => $categories ) );?></li>
					<li><?php siw_render_field( 'select', array( 'name' => 'maand', 'id' => 'maand', 'options' => $months ) );?></li>
					<li><?php siw_render_field( 'submit', array( 'value' => __( 'Zoeken', 'siw') ) );?></li>
				</ul>
			</form>
		</div>
		<?php
		echo $args['after_widget'];

	}
}
