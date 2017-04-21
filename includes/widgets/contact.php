<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Widget met contactgegevens */
add_action( 'widgets_init', function() {
	register_widget( 'SIW_Contact_Information' );
});

class SIW_Contact_Information extends \TDP\Widgets_Helper {

	public function __construct() {
		$this->widget_name = __( 'SIW: Contactinformatie', 'siw' );
		$this->widget_description = __( 'Contactinformatie' );
		$this->widget_fields = array(
			array(
				'id'   => 'title',
				'name' => __( 'Titel', 'siw' ),
				'type' => 'text',
				'std'  => __( 'Contact', 'siw' ),
			),
		);
		$this->init();
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}?>
		<div class="vcard">
			<p class="fn org"><b><?php echo esc_html( SIW_NAME );?></b></p>
			<p class="adr">
				<span class="street-address"><?php echo esc_html( SIW_ADDRESS );?></span><br/>
				<span class="postal-code"><?php echo esc_html( SIW_POSTAL_CODE );?></span>&nbsp;<span class="locality"><?php echo esc_html( SIW_CITY );?></span>
			</p>
			<p class="tel fixedtel"><i class="kt-icon-phone3"></i>&nbsp;<?php echo esc_html( SIW_PHONE );?></p>
			<p><a href="mailto:<?php echo antispambot( SIW_EMAIL );?>" class="email"><i class="kt-icon-envelop"></i>&nbsp;<?php echo antispambot( SIW_EMAIL );?></a></p>
		</div>
		<?php
		echo $args['after_widget'];
	}
}
