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
				'name' => __( 'Titel', 'siw' ),
				'type' => 'text',
				'std'  => __( 'Blijf op de hoogte', 'siw' ),
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
		$subscriber_count = do_shortcode( '[wysija_subscribers_count list_id="' . esc_attr( $instance['list'] ) . '" ]' );

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
				<?php siw_render_field( 'text', array( 'label' => __( 'Voornaam', 'siw' ), 'name' => 'name', 'id' => 'newsletter_name', 'required' => true ), array( 'tag' => 'p' )  ) ;?>
				<?php siw_render_field( 'email', array( 'label' => __( 'E-mail', 'siw' ), 'name' => 'email', 'id' => 'newsletter_email', 'required' => true ), array( 'tag' => 'p' )  ) ;?>
				<?php siw_render_field( 'submit', array( 'value' => __( 'Aanmelden', 'siw') ), array( 'tag' => 'p' ) );?>
				<?php siw_render_field( 'hidden', array( 'value' => $instance['list'], 'name' => 'list_id', 'id' => 'newsletter_list_id' ) ); ?>
				<?php wp_nonce_field( 'siw_newsletter_nonce', 'newsletter_nonce', false );?>
			</form>
		</div>
		<?php
		echo $args['after_widget'];

	}
}
