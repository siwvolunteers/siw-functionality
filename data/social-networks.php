<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van sociale netwerken
 * 
 * @author    Maarten Bruna
 * @package   SIW\Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'slug'                => 'facebook',
		'name'                => __( 'Facebook', 'siw' ),
		'icon_class'          => 'siw-icon-facebook-f',
		'follow'              => true,
		'follow_url'          => SIW_Properties::FACEBOOK_URL,
		'share'               => true,
		'share_url_template'  => 'https://www.facebook.com/sharer/sharer.php?u={{ url }}',
	],
	[
		'slug'                => 'twitter',
		'name'                => __( 'Twitter', 'siw' ),
		'icon_class'          => 'siw-icon-twitter',
		'follow'              => true,
		'follow_url'          => SIW_Properties::TWITTER_URL,
		'share'               => true,
		'share_url_template'  => 'https://twitter.com/intent/tweet?text={{ title }}&amp;url={{ url }}&amp;via=siwvolunteers',
	],
	[
		'slug'                => 'instagram',
		'name'                => __( 'Instagram', 'siw' ),
		'icon_class'          => 'siw-icon-instagram',
		'follow'              => true,
		'follow_url'          => SIW_Properties::INSTAGRAM_URL,
		'share'               => false,
	],
	[
		'slug'                => 'linkedin',
		'name'                => __( 'LinkedIn', 'siw' ),
		'icon_class'          => 'siw-icon-linkedin-in',
		'follow'              => true,
		'follow_url'          => SIW_Properties::LINKEDIN_URL,
		'share'               => true,
		'share_url_template'  => 'https://www.linkedin.com/shareArticle?mini=true&url={{ url }}&amp;title={{ title }}',
	],
	[
		'slug'                => 'youtube',
		'name'                => __( 'YouTube', 'siw' ),
		'icon_class'          => 'siw-icon-youtube',
		'follow'              => true,
		'follow_url'          => SIW_Properties::YOUTUBE_URL,
		'share'               => false,
	]
];

return $data;