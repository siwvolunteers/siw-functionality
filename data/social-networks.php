<?php declare(strict_types=1);

use SIW\Data\Icons\Social_Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van sociale netwerken
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$siw_data = [
	[
		'slug'               => 'facebook',
		'name'               => __( 'Facebook', 'siw' ),
		'icon_class'         => Social_Icons::FACEBOOK,
		'color'              => '#3b5998',
		'follow'             => true,
		'follow_url'         => 'https://www.facebook.com/SIWvolunteers/',
		'share'              => true,
		'share_url_template' => 'https://www.facebook.com/sharer/sharer.php?u={{ url }}',
	],
	[
		'slug'               => 'twitter',
		'name'               => __( 'Twitter', 'siw' ),
		'icon_class'         => Social_Icons::TWITTER,
		'color'              => '#00aced',
		'follow'             => true,
		'follow_url'         => 'https://twitter.com/SIWvolunteers',
		'share'              => true,
		'share_url_template' => 'https://twitter.com/intent/tweet?text={{ title }}&amp;url={{ url }}&amp;via=siwvolunteers',
	],
	[
		'slug'       => 'instagram',
		'name'       => __( 'Instagram', 'siw' ),
		'icon_class' => Social_Icons::INSTAGRAM,
		'color'      => '#dd2a7b',
		'follow'     => true,
		'follow_url' => 'https://www.instagram.com/siwvrijwilligersprojecten/',
		'share'      => false,
	],
	[
		'slug'               => 'linkedin',
		'name'               => __( 'LinkedIn', 'siw' ),
		'icon_class'         => Social_Icons::LINKEDIN,
		'color'              => '#007bb6',
		'follow'             => true,
		'follow_url'         => 'https://www.linkedin.com/company/siw',
		'share'              => true,
		'share_url_template' => 'https://www.linkedin.com/sharing/share-offsite/?url={{ url }}',
	],
	[
		'slug'       => 'youtube',
		'name'       => __( 'YouTube', 'siw' ),
		'icon_class' => Social_Icons::YOUTUBE,
		'color'      => '#ff3333',
		'follow'     => false, // TODO: aanpassen is kanaal gereanimeerd is
		'follow_url' => 'https://www.youtube.com/user/SIWvolunteerprojects',
		'share'      => false,
	],
	[
		'slug'       => 'pinterest',
		'name'       => __( 'Pinterest', 'siw' ),
		'icon_class' => Social_Icons::PINTEREST,
		'color'      => '#e60023',
		'follow'     => false, // TODO: aanpassen is kanaal gereanimeerd is
		'follow_url' => 'https://nl.pinterest.com/SIWvolunteers/',
		'share'      => false,
	],
	[
		'slug'               => 'whatsapp',
		'name'               => __( 'WhatsApp', 'siw' ),
		'icon_class'         => Social_Icons::WHATSAPP,
		'color'              => '#25D366',
		'follow'             => false,
		'share'              => true,
		'share_url_template' => 'https://api.whatsapp.com/send?text={{ url }}',
	],
];

return $siw_data;
