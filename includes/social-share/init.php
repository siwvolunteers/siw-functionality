<?php
/*
(c)2016 SIW Internationale Vrijwilligersprojecten
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
	$twitter_url = 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $url . '&amp;via=siwvolunteers';
	$facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
	$linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&amp;title=' . $title;

	/*
	 * html voor social share links genereren
	 */
	$share_buttons = '';
	if ( $hr ) { $share_buttons .= '<hr>'; }
	$share_buttons .= '<div class="siw-social">';
	$share_buttons .= '<div class="title">' . $post_type_description .  ':</div>';
	$share_buttons .= '<a class="facebook" data-toggle="tooltip" data-placement="bottom" data-original-title="Facebook" href="'. $facebook_url .'" target="_blank"><i class="kt-icon-facebook2"></i></a>';
	$share_buttons .= '<a class="twitter" data-toggle="tooltip" data-placement="bottom" data-original-title="Twitter" href="'. $twitter_url .'" target="_blank"><i class="kt-icon-twitter2"></i></a>';
	$share_buttons .= '<a class="linkedin" data-toggle="tooltip" data-placement="bottom" data-original-title="LinkedIn" href="'. $linkedin_url .'" target="_blank"><i class="kt-icon-linkedin2"></i></a>';
	$share_buttons .= '</div>';


	echo $share_buttons;
}, 60 );
