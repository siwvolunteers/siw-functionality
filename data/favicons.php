<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Properties;

/**
 * Gegevens van favicons voor diverse browsers
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$base_url = wp_make_link_relative( SIW_ASSETS_URL . 'favicons/' );

$data = [
	[
		'tag'        => 'link',
		'attributes' => [
			'rel'   => 'apple-touch-icon',
			'href'  => "{$base_url}apple-touch-icon.png",
		],
	],
	[
		'tag'        => 'link',
		'attributes' => [
			'rel'   => 'icon',
			'type'  => 'image/png',
			'sizes' => '32x32',
			'href'  => "{$base_url}favicon-32x32.png",
		],
	],
	[
		'tag'        => 'link',
		'attributes' => [
			'rel'   => 'icon',
			'type'  => 'image/png',
			'sizes' => '192x192',
			'href'  => "{$base_url}android-chrome-192x192.png",
		],
	],
	[
		'tag'        => 'link',
		'attributes' => [
			'rel'   => 'icon',
			'type'  => 'image/png',
			'sizes' => '16x16',
			'href'  => "{$base_url}favicon-16x16.png",
		],
	],
	[
		'tag'        => 'link',
		'attributes' => [
			'rel'  => 'manifest',
			'href' => "{$base_url}manifest.json",
		],
	],
	[
		'tag'        => 'link',
		'attributes' => [
			'rel'   => 'mask-icon',
			'href'  => "{$base_url}safari-pinned-tab.svg",
			'color' => Properties::PRIMARY_COLOR,
		],
	],
	[
		'tag'        => 'link',
		'attributes' => [
			'rel'  => 'shortcut icon',
			'href' => "{$base_url}favicon.ico",
		],
	],
	[
		'tag'        => 'link',
		'attributes' => [
			'rel'  => 'msapplication-config',
			'href' => "{$base_url}browserconfig.xml",
		],
	],
];

return $data;
