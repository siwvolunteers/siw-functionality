<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/*
 * Widget met eerstvolgende evenementen tonen
 */
add_action( 'widgets_init', function() {
	register_widget( 'SIW_Agenda' );
} );
class SIW_Agenda extends \TDP\Widgets_Helper {

	public function __construct() {
		$this->widget_name = __( 'SIW: Agenda', 'siw' );
		$this->widget_description = __( 'Toont het eerstvolgende evenement', 'siw' );
		$this->widget_fields = array(
			array(
				'id'   => 'title',
				'name' => __('Titel', 'siw'),
				'type' => 'text',
				'std'  => __('Agenda', 'siw'),
			),
		);
		$this->init();
	}

	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance['title'] );

		$events = get_transient( 'siw_upcoming_events' );
		if ( false === $events ) {
			$events = siw_get_upcoming_events( 2 );
			set_transient( 'siw_upcoming_events', $events, HOUR_IN_SECONDS );
		}

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}?>
		<?php if ( ! empty( $events ) ){?>
		<ul class="siw_events">
		<?php
		foreach ( $events as $event ) {
			?>
			<li class="siw_event">
				<h5 class="siw_event_title">
					<a href="<?php echo esc_url( $event['permalink'] ); ?>" class="siw_event_link"><?php echo esc_html( $event['title'] ); ?></a>
				</h5>
				<span class="siw_event_duration" >
					<?php echo esc_html( $event['date_range'] );?> <br/>
					<?php echo esc_html( $event['start_time'] . '&nbsp;-&nbsp;' . $event['end_time'] );?><br/>
				</span>
				<span class="siw_event_location"><?php echo esc_html( $event['location'] . ',&nbsp;' . $event['city'] );?></span>
				<script type="application/ld+json">
[{
	"@context" : "http://schema.org",
	"name" : "<?php echo esc_attr( $event['title'] );?>",
	"description" : "<?php echo esc_attr( $event['excerpt'] );?>",
	"image" : "<?php echo esc_url( $event['post_thumbnail_url'] );?>",
	"@type" : "event",
	"startDate" : "<?php echo esc_attr( $event['start_date'] ); ?>",
	"endDate" : "<?php echo esc_attr( $event['end_date'] ); ?>",
	"location" : {
		"@type" : "Place",
		"name" : "<?php echo esc_attr( $event['location'] ); ?>",
		"address" : "<?php echo esc_attr( $event['address'] . ', ' . $event['postal_code'] . ' ' . $event['city'] ); ?>"
	},
	"url": "<?php echo esc_url( $event['permalink'] ); ?>"
}]
				</script>
			</li>
			<?php }?>
		</ul>
		<p class="siw_agenda_page_link">
			<a href="<?php echo esc_url( get_page_link( siw_get_setting( 'agenda_parent_page' ) ) ); ?>"><?php esc_html_e( 'Bekijk de volledige agenda.', 'siw' ); ?></a>
		</p>
		<?php } else { ?>
			<p><?php esc_html_e( 'Er zijn momenteel geen geplande activiteiten.', 'siw' ); ?></p>
		<?php };
		echo $args['after_widget'];
	}

}
