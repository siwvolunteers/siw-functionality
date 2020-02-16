<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Properties;

/**
 * Opties voor organisatiegegevens
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'board_members',
	'title'          => __( 'Bestuur', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'board',
	'fields'         => [
		[
			'id'            => 'board_members',
			'type'          => 'group',
			'clone'         => true,
			'sort_clone'    => true,
			'max_clone'     => Properties::MAX_BOARD_MEMBERS,
			'collapsible'   => true,
			'default_state' => 'collapsed',
			'group_title'   => [ 'field' => 'first_name, last_name'],
			'add_button'    => __( 'Bestuurslid toevoegen', 'siw' ),
			'fields'        => [
				[
					'id'       => 'first_name',
					'name'     => __( 'Voornaam', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => 'last_name',
					'name'     => __( 'Achternaam', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => 'title',
					'name'     => __( 'Functie', 'siw' ),
					'type'     => 'button_group',
					'required' => true,
					'options'  => siw_get_board_titles(),
				],
			]
		]
	],
];

return $data;
