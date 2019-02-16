<?php
/**
 * Gegevens van sociale netwerken
 * 
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

add_filter( 'siw_social_network_data', function( $data ) {
	$data = [
		[
			'slug'       => 'facebook',
			'name'       => __( 'Facebook', 'siw' ),
			'follow'     => true,
			'follow_url' => SIW_Properties::FACEBOOK_URL,
			'share'      => true,
			'share_url'  => 'https://www.facebook.com/sharer/sharer.php?u={{ url }}',
		],
		[
			'slug'       => 'twitter',
			'name'       => __( 'Twitter', 'siw' ),
			'follow'     => true,
			'follow_url' => SIW_Properties::TWITTER_URL,
			'share'      => true,
			'share_url'  => 'https://twitter.com/intent/tweet?text={{ title }}&amp;url={{ url }}&amp;via=siwvolunteers',
		],
		[
			'slug'       => 'instagram',
			'name'       => __( 'Instagram', 'siw' ),
			'follow'     => true,
			'follow_url' => SIW_Properties::INSTAGRAM_URL,
			'share'      => false,
		],
		[
			'slug'       => 'linkedin',
			'name'       => __( 'LinkedIn', 'siw' ),
			'follow'     => true,
			'follow_url' => SIW_Properties::LINKEDIN_URL,
			'share'      => true,
			'share_url'  => 'https://www.linkedin.com/shareArticle?mini=true&url={{ url }}&amp;title={{ title }}',
		],
		[
			'slug'       => 'youtube',
			'name'       => __( 'YouTube', 'siw' ),
			'follow'     => true,
			'follow_url' => SIW_Properties::YOUTUBE_URL,
			'share'      => false,
		]
	];

	return $data;
});
