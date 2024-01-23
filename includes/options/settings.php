<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Data\Board_Title;
use SIW\Data\Continent;
use SIW\Properties;

class Settings extends Option {

	private const EMAIL_LOCAL_PART_PATTERN = '^[^\s@]+$';

	/** {@inheritDoc} */
	public function get_title(): string {
		return __( 'Instellingen', 'siw' );
	}

	/** {@inheritDoc} */
	public function get_capability(): string {
		return 'edit_posts';
	}

	/** {@inheritDoc} */
	public function get_parent_page(): string {
		return 'options-general.php';
	}

	/** {@inheritDoc} */
	public function get_tabs(): array {
		$tabs = [
			[
				'id'    => 'organisation',
				'label' => __( 'Organisatie', 'siw' ),
				'icon'  => 'dashicons-building',
			],
			[
				'id'    => 'email',
				'label' => __( 'E-mail', 'siw' ),
				'icon'  => 'dashicons-email',
			],
			[
				'id'    => 'workcamps',
				'label' => __( 'Groepsprojecten', 'siw' ),
				'icon'  => 'dashicons-groups',
			],
			[
				'id'    => 'annual_reports',
				'label' => __( 'Jaarverslagen', 'siw' ),
				'icon'  => 'dashicons-media-document',
			],
			[
				'id'    => 'sponsors',
				'label' => __( 'Sponsors', 'siw' ),
				'icon'  => 'dashicons-money',
			],
		];
		return $tabs;
	}

	/** {@inheritDoc} */
	public function get_fields(): array {
		$fields = [];

		// Bestuur
		$fields[] = [
			'type' => 'heading',
			'name' => __( 'Bestuur', 'siw' ),
			'tab'  => 'organisation',
		];
		$fields[] = [
			'id'            => 'board_members',
			'type'          => 'group',
			'tab'           => 'organisation',
			'clone'         => true,
			'sort_clone'    => true,
			'max_clone'     => Properties::MAX_BOARD_MEMBERS,
			'collapsible'   => true,
			'default_state' => 'collapsed',
			'group_title'   => [ 'field' => 'first_name, last_name' ],
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
					'options'  => Board_Title::list(),
				],
			],
		];
		$fields[] = [
			'id'     => 'staff',
			'type'   => 'group',
			'tab'    => 'organisation',
			'fields' => [
				[
					'type' => 'heading',
					'name' => __( 'Medewerkers', 'siw' ),

				],
				[
					'id'       => 'number_of_employees',
					'name'     => __( 'Aantal betaalde medewerkers', 'siw' ),
					'type'     => 'number',
					'required' => true,
					'min'      => 1,
					'max'      => 10,
					'size'     => 10,
				],
				[
					'id'       => 'number_of_volunteers',
					'name'     => __( 'Aantal vrijwilligers', 'siw' ),
					'type'     => 'number',
					'required' => true,
					'min'      => 1,
					'max'      => 99,
					'size'     => 10,
				],
			],
		];

		// Jaarverslagen
		$fields[] = [
			'id'            => 'annual_reports',
			'type'          => 'group',
			'tab'           => 'annual_reports',
			'clone'         => true,
			'sort_clone'    => true,
			'max_clone'     => Properties::MAX_ANNUAL_REPORTS,
			'collapsible'   => true,
			'default_state' => 'collapsed',
			'group_title'   => [ 'field' => 'year' ],
			'add_button'    => __( 'Jaarverslag toevoegen', 'siw' ),
			'fields'        => [
				[
					'id'       => 'year',
					'name'     => __( 'Jaar', 'siw' ),
					'type'     => 'number',
					'required' => true,
					'min'      => intval( gmdate( 'Y', strtotime( Properties::FOUNDING_DATE ) ) ),
					'max'      => intval( gmdate( 'Y' ) ),
				],
				[
					'id'               => 'file',
					'name'             => __( 'Bestand', 'siw' ),
					'type'             => 'file_advanced',
					'required'         => true,
					'max_file_uploads' => 1,
					'mime_type'        => 'application/pdf',
					'force_delete'     => false,
				],
			],
		];

		// Sponsors
		$fields[] = [
			'id'            => 'sponsors',
			'type'          => 'group',
			'tab'           => 'sponsors',
			'clone'         => true,
			'sort_clone'    => true,
			'max_clone'     => 10,
			'collapsible'   => true,
			'default_state' => 'collapsed',
			'group_title'   => [ 'field' => 'name' ],
			'add_button'    => __( 'Sponsor toevoegen', 'siw' ),
			'fields'        => [
				[
					'id'       => 'name',
					'name'     => __( 'Naam', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => 'site',
					'name'     => __( 'Site', 'siw' ),
					'type'     => 'url',
					'required' => true,
				],
				[
					'id'               => 'logo',
					'name'             => __( 'Logo', 'siw' ),
					'type'             => 'image_advanced',
					'required'         => true,
					'max_file_uploads' => 1,
					'force_delete'     => false,
				],
			],
		];

		// Groepsprojecten
		$approval_fields = [
			[
				'type' => 'heading',
				'name' => __( 'Beoordelen projecten', 'siw' ),
				'desc' => __( 'Ontvangers van mail over te beoordelen projecten', 'siw' ),
			],
			[
				'id'         => 'supervisor',
				'name'       => __( 'Coördinator', 'siw' ),
				'type'       => 'user',
				'desc'       => __( 'Staat op cc van alle mails', 'siw' ),
				'field_type' => 'select_advanced',
			],
		];
		foreach ( Continent::list() as $slug => $name ) {
			$approval_fields[] = [
				'id'         => "responsible_{$slug}",
				'name'       => $name,
				'type'       => 'user',
				'field_type' => 'select_advanced',
			];
		}
		$fields[] = [
			'id'     => 'workcamp_approval',
			'type'   => 'group',
			'tab'    => 'workcamps',
			'fields' => $approval_fields,
		];

		// Email
		$forms = siw_get_forms();
		$forms['workcamp'] = __( 'Groepsprojecten', 'siw' );

		foreach ( $forms as $id => $name ) {
			$email_setting_fields[] = [
				'id'     => "{$id}",
				'type'   => 'group',
				'tab'    => 'email',
				'fields' => [
					[
						'type' => 'heading',
						'name' => $name,
					],
					[
						'id'        => 'use_specific',
						'name'      => __( 'Gebruik afwijkende instellingen', 'siw' ),
						'type'      => 'switch',
						'std'       => true,
						'columns'   => 12,
						'on_label'  => __( 'Ja', 'siw' ),
						'off_label' => __( 'Nee', 'siw' ),
					],
					[
						'id'       => 'confirmation_mail_sender',
						'name'     => __( 'Afzender bevestigingsmail', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'pattern'  => self::EMAIL_LOCAL_PART_PATTERN,
						'append'   => '@siw.nl',
						'columns'  => 4,
						'visible'  => [ "email_settings[{$id}][use_specific]", true ],
					],
					[
						'id'       => 'notification_mail_recipient',
						'name'     => __( 'Ontvanger notificatiemail', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'pattern'  => self::EMAIL_LOCAL_PART_PATTERN,
						'append'   => '@siw.nl',
						'columns'  => 4,
						'visible'  => [ "email_settings[{$id}][use_specific]", true ],
					],
					[
						'id'      => 'notification_mail_cc',
						'name'    => __( 'CC notificatiemail', 'siw' ),
						'type'    => 'text',
						'pattern' => self::EMAIL_LOCAL_PART_PATTERN,
						'clone'   => true,
						'append'  => '@siw.nl',
						'columns' => 4,
						'visible' => [ "email_settings[{$id}][use_specific]", true ],
					],
				],
			];
		}

		$fields[] = [
			'id'     => 'email_settings',
			'type'   => 'group',
			'tab'    => 'email',
			'fields' => [
				[
					'type' => 'heading',
					'name' => __( 'E-mailinstellingen', 'siw' ),
				],
				[
					'id'            => 'default',
					'type'          => 'group',
					'collapsible'   => true,
					'default_state' => 'expanded',
					'group_title'   => __( 'Standaardinstellingen', 'siw' ),
					'fields'        => [
						[
							'id'       => 'confirmation_mail_sender',
							'name'     => __( 'Afzender bevestigingsmail', 'siw' ),
							'type'     => 'text',
							'required' => true,
							'pattern'  => self::EMAIL_LOCAL_PART_PATTERN,
							'append'   => '@siw.nl',
							'columns'  => 4,
						],
						[
							'id'       => 'notification_mail_recipient',
							'name'     => __( 'Ontvanger notificatiemail', 'siw' ),
							'type'     => 'text',
							'required' => true,
							'pattern'  => self::EMAIL_LOCAL_PART_PATTERN,
							'append'   => '@siw.nl',
							'columns'  => 4,
						],
						[
							'id'      => 'notification_mail_cc',
							'name'    => __( 'CC notificatiemail', 'siw' ),
							'type'    => 'text',
							'pattern' => self::EMAIL_LOCAL_PART_PATTERN,
							'clone'   => true,
							'append'  => '@siw.nl',
							'columns' => 4,
						],
					],
				],
				...$email_setting_fields,
			],
		];

		return $fields;
	}
}
