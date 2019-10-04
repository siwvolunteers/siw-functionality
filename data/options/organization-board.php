<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor organisatiegegevens
 * 
 * @package   SIW\Data
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

$data = [
	'id'             => 'board_members',
	'title'          => __( 'Bestuur', 'siw' ),
	'settings_pages' => 'organization',
	'fields'         => [
		[
			'id'            => 'board_members',
			'name'          => 'Bestuursleden',
			'type'          => 'group',
			'clone'         => true,
			'sort_clone'    => true,
			'max_clone'     => SIW_Properties::MAX_BOARD_MEMBERS,
			'collapsible'   => true,
			'default_state' => 'collapsed',
			'group_title'   => [ 'field' => 'first_name, last_name'],
			'add_button'    => __( 'Toevoegen', 'siw' ),
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
					'id'      => 'title',
					'name'    => __( 'Functie', 'siw' ),
					'type'    => 'button_group',
					'options' => siw_get_board_titles(),
				],
			]
		]
	],
];

return $data;
