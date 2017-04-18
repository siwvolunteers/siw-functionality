<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* EVS-kaart niet tonen op mobiel */
add_filter( 'siteorigin_panels_data', function( $panels_data ) {
	//TODO:vervangen door generieke instelling i.p.v. op basis van css-klasse
	$detect = new Mobile_Detect_pinnacle;
	$index = '';
	if ( $detect->isMobile() ) {
		if ( ! empty( $panels_data['widgets'] ) && is_array( $panels_data['widgets'] ) ) {
			foreach ( $panels_data['widgets'] as &$widget ) {
				if ( isset( $widget['panels_info']['style']['class'] ) && 'mapplic' == $widget['panels_info']['style']['class'] ) {
				$index = $widget['panels_info']['id'];
				}
			}
		}
		if ('' != $index ) {
			array_splice( $panels_data['widgets'], $index, 1 );
		}
	}
	return $panels_data;
}, 1 );

/* Siteorigin Live Editor over ssl */
add_filter('woocommerce_unforce_ssl_checkout', function ( $unforce_ssl ) {
	if ( 'true' == $_REQUEST['siteorigin_panels_live_editor'] ) {
		return false;
	}
	return $unforce_ssl;
} );
