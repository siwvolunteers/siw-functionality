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
			<p class="fn org"><?php echo esc_html( SIW_NAME );?></p>
			<p class="adr">
				<span class="street-address"><?php echo esc_html( SIW_ADDRESS );?></span>&nbsp;|&nbsp;<span class="postal-code"><?php echo esc_html( SIW_POSTAL_CODE );?></span>&nbsp;<span class="locality"><?php echo esc_html( SIW_CITY );?></span>
			</p>
			<p class="tel fixedtel"><i class="kt-icon-phone3"></i>&nbsp;<?php echo esc_html( SIW_PHONE );?>&nbsp;|&nbsp;<a href="mailto:<?php echo antispambot( SIW_EMAIL );?>" class="email"><i class="kt-icon-envelop"></i>&nbsp;<?php echo antispambot( SIW_EMAIL );?></a></p>
			<p><i class="kt-icon-clock3"></i>&nbsp;<?php printf( esc_html__( 'Maandag t/m vrijdag %s', 'siw' ), SIW_OPENING_HOURS );?></p>
		</div>
 		<div class="siw-social-links clearfix">
			<a href="<?php echo esc_url( SIW_FACEBOOK_URL); ?>" class="facebook" title="Facebook" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="Facebook"><i class="kt-icon-facebook3"></i></a>
			<a href="<?php echo esc_url( SIW_TWITTER_URL ); ?>" class="twitter" title="Twitter" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="Twitter"><i class="kt-icon-twitter2"></i></a>
			<a href="<?php echo esc_url( SIW_INSTAGRAM_URL ); ?>" class="instagram" title="Instagram" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="Instagram"><i class="kt-icon-instagram"></i></a>
			<a href="<?php echo esc_url( SIW_YOUTUBE_URL ); ?>" class="youtube" title="YouTube" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="YouTube"><i class="kt-icon-youtube"></i></a>
			<a href="<?php echo esc_url( SIW_LINKEDIN_URL ); ?>" class="linkedin" title="LinkedIn" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="LinkedIn"><i class="kt-icon-linkedin2"></i></a>
		</div>
		<?php
		echo $args['after_widget'];
	}
}
