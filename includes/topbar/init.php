<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'kt_before_header_content', function() {

	$show_topbar_days_before_event = siw_get_setting( 'show_topbar_days_before_event' );
	$hide_topbar_days_before_event = siw_get_setting( 'hide_topbar_days_before_event' );

	$date_before = strtotime( date( 'Y-m-d' ) ) + ( $hide_topbar_days_before_event * DAY_IN_SECONDS );
	$date_after = strtotime( date( 'Y-m-d' ) ) + ( $show_topbar_days_before_event * DAY_IN_SECONDS );

	$upcoming_events = siw_get_upcoming_events( 1, $date_before, $date_after );

	if ( empty ( $upcoming_events ) ) {
		return;
	}
	$event = $upcoming_events[0];
	$link_title = sprintf(__( 'Meer informatie over de %s' ), $event['title'] );
	$link = sprintf( '<a id="topbar_link" href="%s" title="%s">%s</a>', esc_url( $event['permalink'] ), esc_attr( $link_title ), esc_html( $event['title'] ) );

	?>
<div id="topbar" class="topclass">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="eventbar">
					<span class="hide_on_mobile"><?php esc_html_e( 'Maak kennis met SIW.', 'siw' );?>&nbsp;</span><?php
						if ( $event['start_date'] == $event['end_date'] ) {
							printf( wp_kses_post( __( 'Kom naar de %s op %s', 'siw' ) ), $link, $event['date_range'] );
						}
						else {
							printf( wp_kses_post( __( 'Kom naar de %s van %s', 'siw' ) ), $link, $event['date_range'] );
						}?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
});
