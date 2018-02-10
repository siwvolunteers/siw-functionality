<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if (! defined( 'ABSPATH' )) {
    exit;
}
/* Content*/
require_once( __DIR__ . '/event.php' );
require_once( __DIR__ . '/job.php' );


add_action( 'kt_before_header_content', function () {

	$topbar_content = siw_get_topbar_content();
	if ( false == $topbar_content ) {
		return;
	}
	$target = isset( $topbar_content['link_target'] ) ? $topbar_content['link_target'] : '_self';

	?>
<div id="topbar" class="topclass">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="topbar-content">
					<span class="hidden-xs hidden-sm"><?php echo esc_html( $topbar_content['intro'] );?>&nbsp;</span>
					<?php printf('<a id="topbar_link" href="%s" target="%s">%s</a>', esc_url( $topbar_content['link_url'] ), esc_attr( $target ), esc_html( $topbar_content['link_text'] ) ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
});


/**
 * [siw_get_topbar_content description]
 * @return array
 */
function siw_get_topbar_content() {

	$topbar_social_content = siw_get_topbar_social_content();
	if ( ! empty( $topbar_social_content ) ) {
		return $topbar_social_content;
	}

	$topbar_event_content = siw_get_topbar_event_content();
	if ( ! empty( $topbar_event_content ) ) {
		return $topbar_event_content;
	}

	$topbar_job_content = siw_get_topbar_job_content();
	if ( ! empty ( $topbar_job_content ) ) {
		return $topbar_job_content;
	}

	return false;
}

function siw_get_topbar_social_content() {

	if ( ! siw_get_setting( 'topbar_social_link_enabled' ) ||
		empty( siw_get_setting( 'topbar_social_link_date_end' ) ) ||
		date( 'Y-m-d' ) > siw_get_setting( 'topbar_social_link_date_end' ) ||
 		empty( siw_get_setting( 'topbar_social_link_intro' ) ) ||
		empty( siw_get_setting( 'topbar_social_link_text' ) ) ||
		empty( siw_get_setting( 'topbar_social_link_network' ) )
		) {
		return false;
	}

	//TODO: naar functions verplaatsen
	$social_urls = array(
		'facebook'	=> SIW_FACEBOOK_URL,
		'twitter'	=> SIW_TWITTER_URL,
		'instagram'	=> SIW_INSTAGRAM_URL,
	);

	$topbar_social_content = array(
		'intro' => siw_get_setting( 'topbar_social_link_intro' ),
		'link_url' => $social_urls[ siw_get_setting( 'topbar_social_link_network') ],
		'link_text' => siw_get_setting( 'topbar_social_link_text' ),
		'link_target' => '_blank',
	);
    return $topbar_social_content;
}
