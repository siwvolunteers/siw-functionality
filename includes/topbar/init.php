<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Content*/
require_once( __DIR__ . '/event.php' );
require_once( __DIR__ . '/job.php' );


add_action( 'kt_before_header_content', function() {

	$topbar_event_content = siw_get_topbar_event_content();
	$topbar_job_content = siw_get_topbar_job_content();
	if ( ! empty( $topbar_event_content ) ) {
		$topbar_content = $topbar_event_content;
	}
	elseif ( ! empty ( $topbar_job_content ) ) {
		$topbar_content = $topbar_job_content;
	}
	else {
		return;
	}
	?>
<div id="topbar" class="topclass">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="topbar-content">
					<?php echo $topbar_content;?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
});
