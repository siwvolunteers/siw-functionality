<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van sociale netwerken
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'slug'                => 'facebook',
		'name'                => __( 'Facebook', 'siw' ),
		'icon_class'          => 'siw-icon-facebook-f',
		'color'               => '#3b5998',
		'follow'              => true,
		'follow_url'          => 'https://www.facebook.com/SIWvolunteers/',
		'share'               => true,
		'share_url_template'  => 'https://www.facebook.com/sharer/sharer.php?u={{ url }}',
	],
	[
		'slug'                => 'twitter',
		'name'                => __( 'Twitter', 'siw' ),
		'icon_class'          => 'siw-icon-twitter',
		'color'               => '#00aced',
		'follow'              => true,
		'follow_url'          => 'https://twitter.com/SIWvolunteers',
		'share'               => true,
		'share_url_template'  => 'https://twitter.com/intent/tweet?text={{ title }}&amp;url={{ url }}&amp;via=siwvolunteers',
	],
	[
		'slug'                => 'instagram',
		'name'                => __( 'Instagram', 'siw' ),
		'icon_class'          => 'siw-icon-instagram',
		'color'               => '#dd2a7b',
		'follow'              => true,
		'follow_url'          => 'https://www.instagram.com/siwvrijwilligersprojecten/',
		'share'               => false,
	],
	[
		'slug'                => 'linkedin',
		'name'                => __( 'LinkedIn', 'siw' ),
		'icon_class'          => 'siw-icon-linkedin-in',
		'color'               => '#007bb6',
		'follow'              => true,
		'follow_url'          => 'https://www.linkedin.com/company/siw',
		'share'               => true,
		'share_url_template'  => 'https://www.linkedin.com/shareArticle?mini=true&url={{ url }}&amp;title={{ title }}',
	],
	[
		'slug'                => 'youtube',
		'name'                => __( 'YouTube', 'siw' ),
		'icon_class'          => 'siw-icon-youtube',
		'color'               => '#ff3333',
		'follow'              => false, //TODO: aanpassen is kanaal gereanimeerd is
		'follow_url'          => 'https://www.youtube.com/user/SIWvolunteerprojects',
		'share'               => false,
	],
	[
		'slug'                => 'pinterest',
		'name'                => __( 'Pinterest', 'siw' ),
		'icon_class'          => 'siw-icon-pinterest-p',
		'color'               => '#e60023',
		'follow'              => false, //TODO: aanpassen is kanaal gereanimeerd is
		'follow_url'          => 'https://nl.pinterest.com/SIWvolunteers/',
		'share'               => false,
	],
	[
		'slug'                => 'whatsapp',
		'name'                => __( 'WhatsApp', 'siw' ),
		'icon_class'          => 'siw-icon-whatsapp',
		'color'               => '#25D366',
		'follow'              => false,
		'share'               => true,
		'share_url_template'  => 'https://api.whatsapp.com/send?&text={{ url }}',
	],
];

return $data;