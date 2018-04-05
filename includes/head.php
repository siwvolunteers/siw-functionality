<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Optimalisatie HEAD */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );


/* Icons toevoegen aan head */ 
add_action( 'wp_head', function() {
	$icons_url = wp_make_link_relative( SIW_ASSETS_URL . 'icons/' );
?>
	<!-- Start favicons -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $icons_url;?>apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $icons_url;?>favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="192x192" href="<?php echo $icons_url;?>android-chrome-192x192.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $icons_url;?>favicon-16x16.png">
	<link rel="manifest" href="<?php echo $icons_url;?>manifest.json">
	<link rel="mask-icon" href="<?php echo $icons_url;?>safari-pinned-tab.svg" color="#ff9900">
	<link rel="shortcut icon" href="<?php echo $icons_url;?>favicon.ico">
	<meta name="msapplication-config" content="<?php echo $icons_url;?>browserconfig.xml">
	<!-- Einde favicons -->
<?php
});


/*
 * DNS-prefetch voor
 * - Google Analytics
 * - Google Maps
 * - Google Fonts
 */
add_filter( 'wp_resource_hints', function( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$hints[] = 'www.google-analytics.com';
		$hints[] = 'maps.googleapis.com';
		$hints[] = 'maps.google.com';
		$hints[] = 'maps.gstatic.com';
		$hints[] = 'csi.gstatic.com';
		$hints[] = 'fonts.googleapis.com';
		$hints[] = 'fonts.gstatic.com';
	}

	return $hints;
}, 99, 2 );
add_filter( 'emoji_svg_url', '__return_empty_string' );


/* meta-tags aan head toevoegen t.b.v. site-verificatie */
add_action( 'wp_head', function() {

	if ( ! is_front_page() ) {
		return;
	}
	echo '<!-- Start site verificatie -->';
	$google = siw_get_setting( 'google_search_console_verification' );
	if ( $google ) {
		printf( '<meta name="google-site-verification" content="%s">', esc_attr( $google ) );
	}
	$bing = siw_get_setting( 'bing_webmaster_tools_verification' );
	if ( $google ) {
		printf( '<meta name="msvalidate.01" content="%s">', esc_attr( $bing ) );
	}
	echo '<!-- Einde site verificatie -->';
});