<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Toevoegen Google Analytics tracking code */
add_action( 'wp_footer', function() {

	/*Geen GA voor ingelogde gebruikers*/
	if ( is_user_logged_in() ) {
		return;
	}
	$google_analytics_id = siw_get_setting( 'google_analytics_id' );
	$google_analytics_enable_linkid = siw_get_setting( 'google_analytics_enable_linkid' );

	if ( $google_analytics_id ) {?>
		<script>
		window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
		ga('create','<?php echo esc_js( $google_analytics_id ); ?>',{'siteSpeedSampleRate': 100});
		ga('set', 'anonymizeIp', true);
		ga('set', 'forceSSL', true);
		<?php if ( $google_analytics_enable_linkid ) {?>
		ga('require', 'linkid', {
			'levels': 5
		});
		<?php }?>
		ga('send','pageview')
		</script>
		<script src="https://www.google-analytics.com/analytics.js" async defer></script><?php
	}
} );
