<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Toevoegen Google Analytics tracking code */
add_action( 'wp_footer', function() {
	$google_analytics_id = siw_get_setting( 'google_analytics_id' );
	$google_analytics_enable_linkid = siw_get_setting( 'google_analytics_enable_linkid' );

	if ( $google_analytics_id ) {?>
		<script>
		(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
			e.src='//www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
			ga('create','<?php echo $google_analytics_id; ?>',{'siteSpeedSampleRate': 100});
			ga('set', 'anonymizeIp', true);
			ga('set', 'forceSSL', true);
			<?php if ( $google_analytics_enable_linkid ) {?>
			ga('require', 'linkid', {
				'levels': 5
			});
			<?php }?>
			ga('send','pageview');
		</script><?php
	}
} );
