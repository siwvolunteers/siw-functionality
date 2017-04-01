<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Widget met contactgegevens */
add_action( 'widgets_init', function() {
	register_widget( 'siw_contact_information' );
});

class SIW_Contact_Information extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'class'			=> 'siw_contact_information',
			'description'	=> __( 'Contactinformatie', 'siw' )
		);

		parent::__construct(
			'siw_contact_information',
			__( 'SIW: Contactinformatie', 'siw' ),
			$widget_ops
		);
	}

	public function form ( $instance ) {
		$widget_defaults = array(
			'title'			=> __( 'Contact', 'siw' ),
		);
		$instance  = wp_parse_args( (array) $instance, $widget_defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Titel', 'siw' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<?php
	}

	public function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}


    public function widget ( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
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
		echo $after_widget;
    }
}
