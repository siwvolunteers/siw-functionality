<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Interfaces\Options\Option as Option_Interface;

use SIW\Util;

/**
 * Opties voor Configuratie
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */
class Configuration implements Option_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'configuration';
	}

	/** {@inheritDoc} */
	public function get_capability(): string {
		return 'manage_options';
	}

	/** {@inheritDoc} */
	public function get_parent_page(): string {
		return 'options-general.php';
	}

	/** {@inheritDoc} */
	public function get_title(): string {
		return __( 'Configuratie', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_tabs() : array {
		$tabs = [
			[
				'id'    => 'blacklists',
				'label' => __( 'Blacklists', 'siw' ),
				'icon'  => 'dashicons-shield',
			],
			[
				'id'    => 'pages',
				'label' => __( "Pagina's", 'siw' ),
				'icon'  => 'dashicons-admin-page',
			],
		];
		return $tabs;
	}

	/** {@inheritDoc} */
	public function get_fields() : array {
		$fields = [];
		$fields[] = [
			'id'     => 'pages',
			'type'   => 'group',
			'tab'    => 'pages',
			'fields' => [
				[
					'id'     => 'explanation',
					'type'   => 'group',
					'fields' => [
						[
							'type' => 'heading',
							'name' => __( 'Zo werkt het', 'siw' ),
						],
						[
							'id'      => 'workcamps',
							'name'    => __( 'Groepsprojecten', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
						[
							'id'      => 'info_days',
							'name'    => __( 'Infodagen', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
						[
							'id'      => 'esc',
							'name'    => __( 'ESC', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
						[
							'id'      => 'tailor_made',
							'name'    => __( 'Op Maat', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
						[
							'id'      => 'school_projects',
							'name'    => __( 'Scholenprojecten', 'siw' ),
							'type'    => 'select_advanced',
							'options' => Util::get_pages(),
						],
					],
				],
				[
					'type' => 'heading',
					'name' => __( 'Overig', 'siw' ),
				],
				[
					'id'      => 'contact',
					'name'    => __( 'Contact', 'siw' ),
					'type'    => 'select_advanced',
					'options' => Util::get_pages(),
				],
				[
					'id'      => 'child_policy',
					'name'    => __( 'Kinderbeleid', 'siw' ),
					'type'    => 'select_advanced',
					'options' => Util::get_pages(),
				],
				[
					'id'      => 'newsletter_confirmation',
					'name'    => __( 'Bevestiging aanmelding nieuwsbrief', 'siw' ),
					'type'    => 'select_advanced',
					'options' => Util::get_pages(),
				],
			],
		];

		// Blacklists
		$fields[] = [
			'type' => 'heading',
			'name' => __( 'Bot blacklist', 'siw' ),
			'tab'  => 'blacklists',
		];
		$fields[] = [
			'id'         => 'blocked_bots',
			'type'       => 'text',
			'tab'        => 'blacklists',
			'clone'      => true,
			'add_button' => __( 'User agent toevoegen', 'siw' ),
		];

		return $fields;
	}
}
