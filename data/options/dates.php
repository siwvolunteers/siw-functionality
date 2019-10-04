<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor datums
 *
 * @author    Maarten Bruna
 * @package   SIW\Data
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */


$data = [
	'id'             => 'dates',
	'title'          => __( 'Datums', 'siw' ),
	'settings_pages' => 'dates',
	'tabs'           => [
		'esc'      => __( 'ESC', 'siw' ),
		'info_day' => __( 'Infodag', 'siw' ),
	],
	'tab_style' => 'left',
	'fields'    => [
		[
			'type'       => 'heading',
			'name'       => __( 'Infodagen', 'siw' ),
			'tab'        => 'info_day',
		],
		[
			'id'         => 'info_days',
			'name'       => __( 'Datums', 'siw' ),
			'type'       => 'date',
			'tab'        => 'info_day',
			'js_options' => [
				'dateFormat'      => 'yy-mm-dd',
				'showButtonPanel' => false,
			],
			'readonly'   => true,
			'clone'      => true,
			'add_button' => __( 'Toevoegen', 'siw' ),
		],
		[
			'type'       => 'heading',
			'name'       => __( 'ESC deadlines', 'siw' ),
			'tab'        => 'esc',
		],
		[
			'id'         => 'esc_deadlines',
			'name'       => __( 'Datums', 'siw' ),
			'type'       => 'date',
			'tab'        => 'esc',
			'js_options' => [
				'dateFormat'      => 'yy-mm-dd',
				'showButtonPanel' => false,
			],
			'readonly'   => true,
			'clone'      => true,
			'add_button' => __( 'Toevoegen', 'siw' ),
		],
	],
];

return $data;
