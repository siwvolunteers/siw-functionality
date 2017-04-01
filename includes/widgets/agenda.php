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
	register_widget( 'siw_agenda' );
});

class SIW_Agenda extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'class'			=> 'siw_agenda',
			'description'	=> __( 'Toont het eerstvolgende evenement', 'siw' ),
		);

		parent::__construct(
			'siw_agenda',
			__( 'SIW: Agenda', 'siw' ),
			$widget_ops
		);
	}

	public function form( $instance ) {
		$widget_defaults = array(
			'title'	=> __( 'Agenda', 'siw' ),
		);
		$instance = wp_parse_args( (array) $instance, $widget_defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titel', 'siw' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		$agenda_page = siw_get_setting( 'agenda_parent_page' );
		$meta_quer_args = array(
			'relation'	=>	'AND',
			array(
				'key'		=>	'siw_agenda_eind',
				'value'		=>	strtotime( date("Y-m-d") ),
				'compare'	=>	'>='
			)
		);
		$query_args = array(
			'post_type'				=>	'agenda',
			'posts_per_page'		=>	2,
			'post_status'			=>	'publish',
			'ignore_sticky_posts'	=>	true,
			'meta_key'				=>	'siw_agenda_start',
			'orderby'				=>	'meta_value_num',
			'order'					=>	'ASC',
			'meta_query'			=>	$meta_quer_args
		);
		$siw_agenda = new WP_Query( $query_args );

		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<?php if ($siw_agenda->have_posts()):?>
		<ul class="siw_events">
		<?php
			while( $siw_agenda->have_posts() ): $siw_agenda->the_post();
				$start_ts = get_post_meta( get_the_ID(), 'siw_agenda_start', true );
				$end_ts = get_post_meta( get_the_ID(), 'siw_agenda_eind', true );
				$date_range = siw_get_date_range_in_text( date( 'Y-m-d',$start_ts),  date( 'Y-m-d',$end_ts), false );
				$location = get_post_meta( get_the_ID(), 'siw_agenda_locatie', true );
				$address = get_post_meta( get_the_ID(), 'siw_agenda_adres', true );
				$postal_code = get_post_meta( get_the_ID(), 'siw_agenda_postcode', true );
				$city = get_post_meta( get_the_ID(), 'siw_agenda_plaats', true );
				$start_time = date("H:i",$start_ts);
				$end_time = date("H:i",$end_ts);
			?>
				<li class="siw_event">
					<h5 class="siw_event_title">
					<a href="<?php esc_url( the_permalink() ); ?>" class="siw_event_link"><?php esc_html( the_title() ); ?></a>
					</h5>
					<span class="siw_event_duration" >
						<?php echo esc_html( $date_range );?> <br/>
						<?php echo esc_html( $start_time . '&nbsp;-&nbsp;' . $end_time );?><br/>
					</span>
					<span class="siw_event_location"><?php echo esc_html( $location. ',&nbsp;' . $city );?></span>
					<script type="application/ld+json">
[{
"@context" : "http://schema.org",
"name" : "<?php esc_attr( the_title() );?>",
"description" : "<?php echo esc_attr( get_the_excerpt() );?>",
"image" : "<?php esc_url( the_post_thumbnail_url() );?>",
"@type" : "event",
"startDate" : "<?php echo esc_attr( date( 'Y-m-d', $start_ts ) ); ?>",
"endDate" : "<?php echo esc_attr( date( 'Y-m-d', $end_ts ) ); ?>",
"location" : {
	"@type" : "Place",
	"name" : "<?php echo esc_attr( $location ); ?>",
	"address" : "<?php echo esc_attr( $address . ', ' .$postal_code . ' ' . $city ); ?>"
},
"url": "<?php esc_url( the_permalink() ); ?>"
}]
					</script>
					</li>
			<?php endwhile;?>
		</ul>
		<p class="siw_agenda_page_link">
			<a href="<?php echo esc_url( get_page_link( $agenda_page ) ); ?>"><?php esc_html_e( 'Bekijk de volledige agenda.', 'siw' ); ?></a>
		</p>
		<?php else: ?>
		<p><?php esc_html_e( 'Er zijn momenteel geen geplande activiteiten.', 'siw' ); ?></p>
		<?php endif;
		wp_reset_query();
		echo $after_widget;
	}
}
