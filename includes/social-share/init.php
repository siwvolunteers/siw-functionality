<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*
Social sharing links toevoegen aan
- Op maat projecten
- Vacatures
- Agenda-items
- Groepsprojecten
- Ervaringsverhalen
*/


add_actions( array(	'kadence_single_portfolio_after', 'siw_vacature_footer', 'siw_agenda_footer', 'woocommerce_after_single_product', 'kadence_single_post_after' ), function() {
	$post_type = get_post_type();
	$hr = false;
	if ( 'portfolio' == $post_type or 'product' == $post_type ) {
		$post_type_description = __( 'Deel dit project', 'siw' );
		$hr = true;
	}
	elseif ( 'vacatures' == $post_type ) {
		$post_type_description = __( 'Deel deze vacature', 'siw' );
	}
	elseif ( 'agenda' == $post_type ) {
		$post_type_description = __( 'Deel dit evenement', 'siw' );
	}
	elseif ( 'wpm-testimonial' == $post_type ) {
		$post_type_description = __( 'Deel dit ervaringsverhaal', 'siw' );
	}
	else {
		$post_type_description = __( 'Deel deze pagina', 'siw' );
	}

	/*
	 * Eigenschappen van pagina/post
	 */
	$url = urlencode( get_permalink() );
 	$title = rawurlencode( html_entity_decode( get_the_title() ) );

	/*
	 * Share-url's voor diverse sociale netwerken genereren
	 */
	$twitter_url = sprintf( 'https://twitter.com/intent/tweet?text=%s&amp;url=%s&amp;via=siwvolunteers', $title, $url);
	$facebook_url = sprintf( 'https://www.facebook.com/sharer/sharer.php?u=%s', $url);
	$linkedin_url = sprintf( 'https://www.linkedin.com/shareArticle?mini=true&url=%s&amp;title=%s', $title, $url);

	/*
	 * html voor social share links genereren
	 */
	$share_buttons = ( $hr ) ? '<hr>' : '';
	$share_buttons .=
		'<div class="siw-social">' .
			'<div class="title">' . esc_html( $post_type_description ).  ':</div>' .
			'<a class="facebook" data-toggle="tooltip" data-placement="bottom" data-original-title="Facebook" href="' . esc_url( $facebook_url ) .'" target="_blank"><i class="kt-icon-facebook2"></i></a>' .
			'<a class="twitter" data-toggle="tooltip" data-placement="bottom" data-original-title="Twitter" href="' . esc_url( $twitter_url ) .'" target="_blank"><i class="kt-icon-twitter2"></i></a>' .
			'<a class="linkedin" data-toggle="tooltip" data-placement="bottom" data-original-title="LinkedIn" href="' . esc_url( $linkedin_url ) .'" target="_blank"><i class="kt-icon-linkedin2"></i></a>' .
		'</div>';

	echo $share_buttons;
}, 60 );
