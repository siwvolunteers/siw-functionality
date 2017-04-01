<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//Widget
add_action( 'widgets_init', function() {
	register_widget( 'siw_mailpoet_subscription' );
});

class SIW_Mailpoet_Subscription extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'class'         => 'siw_mailpoet_subscription',
			'description'   => __( 'Aanmeldformulier voor Mailpoet', 'siw' ),
		);

		parent::__construct(
			'siw_mailpoet_subscription',
			__( 'SIW: Aanmelden nieuwsbrief', 'siw' ),
			$widget_ops
		);
	}

	public function form( $instance ) {
		$widget_defaults = array(
			'title'	=> __( 'Blijf op de hoogte', 'siw' ),
			'list'	=> '',
		);
		$instance  = wp_parse_args( (array) $instance, $widget_defaults );

		$model_list = WYSIJA::get( 'list','model' );
		$mailpoet_lists = $model_list->get( array( 'name','list_id' ), array( 'is_enabled' => 1) );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titel', 'siw' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>">
			<label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php _e( 'Lijst', 'siw' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>" class="widefat">
			<?php
			foreach ($mailpoet_lists as $list) {
				echo '<option value="', $list['list_id'], '"', $instance['list'] == $list['list_id'] ? ' selected="selected"' : '', '>', $list['name'], '</option>';
			}
		  echo '</select>'; ?>
		</p>


		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['list'] = $new_instance['list'];
		return $instance;
	}


    public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$list = $instance['list'];
		$subscriber_count = do_shortcode( '[wysija_subscribers_count list_id="' . $list . '" ]' );

		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
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
				<input type="hidden" value="<?php echo $list; ?>" name="list_id" id="newsletter_list_id">
				<?php wp_nonce_field( 'siw-newsletter-nonce', 'newsletter_nonce', false);?>
			</form>
		</div>
	<?php
	echo $after_widget;
    }

}
