<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'widgets_init', function() {
	register_widget( 'SIW_Mailpoet_Subscription' );
} );

class SIW_Mailpoet_Subscription extends \TDP\Widgets_Helper {

	public function __construct() {
		$this->widget_name = __( 'SIW: Aanmelden nieuwsbrief', 'siw' );
		$this->widget_description = __( 'Aanmeldformulier voor Mailpoet', 'siw' );
		$this->widget_fields = array(
			array(
				'id'   => 'title',
				'name' => __('Titel', 'siw'),
				'type' => 'text',
				'std'  => __('Blijf op de hoogte', 'siw'),
			),
			array(
				'id'   => 'list',
				'name' => __('Lijst', 'siw'),
				'type' => 'select',
				'options' => siw_get_mailpoet_lists(),
			),
		);
		$this->init();
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$subscriber_count = do_shortcode( '[wysija_subscribers_count list_id="' . $instance['list'] . '" ]' );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}?>
		<div>
			<div id="newsletter_message" class="text-center hidden"></div>
			<div id="newsletter_loading" class="text-center hidden"></div>
			<form id="siw_newsletter_subscription" method="post" autocomplete="on">
				<p>
				<?php printf( esc_html__( 'Meld je aan voor onze nieuwsbrief en voeg je bij de %d abonnees.', 'siw' ), $subscriber_count );?>
				</p>
				<p>
					<label><?php esc_html_e( 'Voornaam','siw' );?></label>
					<input type="text" name="name" title="<?php esc_attr_e( 'Voornaam', 'siw' );?>" id="newsletter_name" required>
				</p>
				<p>
					<label><?php esc_html_e( 'E-mail','siw' );?></label>
					<input type="email" name="email" title="<?php esc_attr_e( 'E-mail', 'siw' );?>" id="newsletter_email" required>

				</p>
				<p>
					<input type="submit" value="<?php esc_attr_e( 'Aanmelden', 'siw' );?>">
				</p>
				<input type="hidden" value="<?php echo $instance['list']; ?>" name="list_id" id="newsletter_list_id">
				<?php wp_nonce_field( 'siw-newsletter-nonce', 'newsletter_nonce', false);?>
			</form>
		</div>
		<?php
		echo $args['after_widget'];

	}
}
