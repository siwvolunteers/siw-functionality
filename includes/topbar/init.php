<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'kt_before_header_content', function() {

	//TODO: ophalen informatie verplaatsen naar functie get_next_event o.i.d.
	$show_topbar_days_before_event = siw_get_setting( 'show_topbar_days_before_event' );
	$hide_topbar_days_before_event = siw_get_setting( 'hide_topbar_days_before_event' );
	$meta_query_args = array(
		'relation'	=>	'AND',
		array(
			'key'		=>	'siw_agenda_eind',
			'value'		=>	strtotime( date( 'Y-m-d' ) ) + ( $hide_topbar_days_before_event * DAY_IN_SECONDS ),
			'compare'	=>	'>='
		),
		array(
			'key'		=> 'siw_agenda_start',
			'value'		=> strtotime( date( 'Y-m-d' ) ) + ( $show_topbar_days_before_event * DAY_IN_SECONDS ),
			'compare'	=>	'<='
		),
	);

	$query_args = array(
		'post_type'				=>	'agenda',
		'posts_per_page'		=>	1,
		'post_status'			=>	'publish',
		'ignore_sticky_posts'	=>	true,
		'meta_key'				=>	'siw_agenda_start',
		'orderby'				=>	'meta_value_num',
		'order'					=>	'ASC',
		'meta_query'			=>	$meta_query_args,
		'fields' 				=> 'ids'
	);
	$next_event_for_topbar = get_posts( $query_args );

	if ( ! empty( $next_event_for_topbar ) ) {
		$post_id = $next_event_for_topbar[0];
		$start_ts = get_post_meta( $post_id, 'siw_agenda_start', true );
		$end_ts = get_post_meta( $post_id, 'siw_agenda_eind', true );
		$date_range = siw_get_date_range_in_text( date( 'Y-m-d', $start_ts ), date( 'Y-m-d', $end_ts ), false );
		$permalink = get_permalink( $post_id );
		$title = get_the_title( $post_id );
		$link_title = sprintf(__( 'Meer informatie over de %s' ), $title );
		$link = sprintf( '<a id="topbar_link" href="%s" title="%s">%s</a>', esc_url( $permalink ), esc_attr( $link_title ), esc_html( $title ) );

	?>
<div id="topbar" class="topclass">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="eventbar">
					<span class="hide_on_mobile"><?php esc_html_e( 'Maak kennis met SIW.', 'siw' );?></span><?php
						if ( date( 'Y-m-d', $start_ts ) == date( 'Y-m-d', $end_ts ) ) {
							printf( wp_kses_post( __( 'Kom naar de %s op %s', 'siw' ) ), $link, $date_range );
						}
						else {
							printf( wp_kses_post( __( 'Kom naar de %s van %s', 'siw' ) ), $link, $date_range );
						}?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	}
});
