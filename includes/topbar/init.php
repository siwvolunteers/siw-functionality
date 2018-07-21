<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
 */
if (! defined( 'ABSPATH' )) {
    exit;
}
/* Content*/
require_once( __DIR__ . '/event.php' );
require_once( __DIR__ . '/job.php' );


add_action( 'kt_before_header_content', function () {

	/* Topbar alleen tonen in het nederlands */
	if ( apply_filters( 'wpml_current_language', NULL ) != apply_filters( 'wpml_default_language', NULL ) ) {
		return;
	}

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
					<?php printf('<a id="topbar_link" href="%s" target="%s">%s</a>', esc_url( $topbar_content['link_url'] ), esc_attr( $target ), wp_kses_post( $topbar_content['link_text'] ) ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
});


/**
 * Bepaal de inhoud van de topbar
 * @return array
 */
function siw_get_topbar_content() {

	$topbar_sale_content = siw_get_topbar_sale_content();
	if ( ! empty( $topbar_sale_content ) ) {
		return $topbar_sale_content;
	}

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


/**
 * Bepaal of er een link naar social media in de topbar getoond moet worden
 *
 * @return array
 */
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


/**
 * Bepaal of er een kortingsactie in de topbar getoond moet worden
 *
 * @return array
 */
function siw_get_topbar_sale_content() {

	if ( ! siw_is_sale_active() ) {
		return false;
	}

	$tariffs = siw_get_workcamp_tariffs();
	$sale_tariff = siw_format_amount( $tariffs[ 'regulier_aanbieding' ] );
	$end_date = siw_get_date_in_text( siw_get_setting( 'workcamp_sale_end_date' ), false );

	$topbar_sale_content = array(
		'intro' => __( 'Grijp je kans en ontvang korting!',  'siw' ),
		'link_url' => wc_get_page_permalink( 'shop' ), //TODO: shop page i.p.v. zo werkt het
		'link_text' => sprintf( __( 'Meld je uiterlijk %s aan voor een project en betaal slechts %s.' , 'siw' ), $end_date, $sale_tariff ) ,
	);

	return $topbar_sale_content;
}