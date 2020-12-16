<?php declare(strict_types=1);

namespace SIW\Options;

use SIW\Interfaces\Options\Option as Option_Interface;

use Caldera_Forms_Forms;
use SIW\Formatting;
use SIW\Modules\Topbar;
use SIW\Properties;

/**
 * Opties voor Configuratie
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class Settings implements Option_Interface {

	/**
	 * {@inheritDoc}
	 */
	public function get_id(): string {
		return 'settings';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_title(): string {
		return __( 'Instellingen', 'siw' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_capability() : string {
		return 'edit_posts';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_parent_page(): string {
		return 'options-general.php';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_tabs() : array {
		$tabs = [
			[
				'id'    => 'board',
				'label' => __( 'Bestuur', 'siw' ),
				'icon'  => 'dashicons-businessman',
			],
			[
				'id'    => 'email',
				'label' => __( 'Email', 'siw' ),
				'icon'  => 'dashicons-email',
			],
			[
				'id'    => 'workcamps',
				'label' => __( 'Groepsprojecten', 'siw' ),
				'icon'  => 'dashicons-groups'
			],
			[
				'id'    => 'annual_reports',
				'label' => __( 'Jaarverslagen', 'siw' ),
				'icon'  => 'dashicons-media-document',
			],
			[
				'id'    => 'dutch_projects',
				'label' => __( 'Nederlandse projecten', 'siw' ),
				'icon'  => 'dashicons-admin-home'
			],
			[
				'id'    => 'notifications',
				'label' => __( 'Notificaties', 'siw' ),
				'icon'  => 'dashicons-megaphone',
			],
			[
				'id'    => 'tailor_made',
				'label' => __( 'Op Maat', 'siw' ),
				'icon'  => 'dashicons-admin-settings',
			],
			[
				'id'    => 'opening_hours',
				'label' => __( 'Openingstijden', 'siw' ),
				'icon'  => 'dashicons-clock',
			],
			[
				'id'    => 'job_postings',
				'label' => __( 'Vacatures', 'siw' ),
				'icon'  => 'dashicons-clipboard'
			],
		];
		return $tabs;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_fields() : array {
		$fields = [];

		//Bestuur
		$fields[] =  [
			'id'            => 'board_members',
			'type'          => 'group',
			'tab'           => 'board',
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
					'options'  => \siw_get_board_titles(),
				],
			]
		];

		//Jaarverslagen
		$fields[] = [
			'id'            => 'annual_reports',
			'type'          => 'group',
			'tab'           => 'annual_reports',
			'clone'         => true,
			'sort_clone'    => true,
			'max_clone'     => Properties::MAX_ANNUAL_REPORTS,
			'collapsible'   => true,
			'default_state' => 'collapsed',
			'group_title'   => [ 'field' => 'year'],
			'add_button'    => __( 'Jaarverslag toevoegen', 'siw' ),
			'fields'        => [
				[
					'id'       => 'year',
					'name'     => __( 'Jaar', 'siw' ),
					'type'     => 'number',
					'required' => true,
					'min'      => intval( date( 'Y', strtotime( Properties::FOUNDING_DATE ) ) ),
					'max'      => intval(date( 'Y' ) )
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

		//Nederlandse projecten
		$fields[] = [
			'id'            => 'dutch_projects_booklets',
			'type'          => 'group',
			'tab'           => 'dutch_projects',
			'clone'         => true,
			'sort_clone'    => true,
			'max_clone'     => 5,
			'collapsible'   => true,
			'default_state' => 'collapsed',
			'group_title'   => 'Programmaboekje {year}',
			'add_button'    => __( 'Programmaboekje toevoegen', 'siw' ),
			'fields'        => [
				[
					'id'       => 'year',
					'name'     => __( 'Jaar', 'siw' ),
					'type'     => 'number',
					'required' => true,
					'min'      => intval( date( 'Y', strtotime( Properties::FOUNDING_DATE ) ) ),
					'max'      => intval( date( 'Y' ) )
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

		//Vacatures TODO: group voor vacaturetekst
		$fields[] = [
			'type'     => 'heading',
			'name'     => __( 'Vacaturetekst', 'siw' ),
			'tab'      => 'job_postings',
		];
		$fields[] = [
			'id'       => 'job_postings_organization_profile',
			'name'     => __( 'Wie zijn wij', 'siw' ),
			'type'     => 'wysiwyg',
			'tab'      => 'job_postings',
			'required' => true,
		];
		$fields[] = [
			'id'        => 'hr_manager',
			'type'      => 'group',
			'tab'       => 'job_postings',
			'fields'    => [
				[
					'type'     => 'heading',
					'name'     => __( 'P&O manager', 'siw' ),
					'desc'     => __( 'Standaard contactpersoon voor sollicitaties', 'siw' ),
				],
				[
					'id'       => 'name',
					'name'     => __( 'Naam', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => 'title',
					'name'     => __( 'Functie', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => 'email',
					'name'     => __( 'E-mail', 'siw' ),
					'type'     => 'email',
					'required' => true,
				],
			],
		];

		//Groepsprojecten
		$continents = siw_get_continents( 'array' );
		$approval_fields = [
			[
				'type'       => 'heading',
				'name'       => __( 'Beoordelen projecten', 'siw' ),
				'desc'       => __( 'Ontvangers van mail over te beoordelen projecten')
			],
			[
				'id'         => 'supervisor',
				'name'       => __( 'Coördinator', 'siw' ),
				'type'       => 'user',
				'desc'       => __( 'Staat op cc van alle mails', 'siw' ),
				'field_type' => 'select_advanced',
			],
		];
		foreach ( $continents as $slug =>$name ) {
			$approval_fields[] = [
				'id'                => "responsible_{$slug}",
				'name'              => $name,
				'type'              => 'user',
				'field_type'        => 'select_advanced',
			];
		}

		$fields[] = [
			'id'      => 'workcamp_sale',
			'type'    => 'group',
			'tab'     => 'workcamps',
			'fields'  => [
				[
					'type'      => 'heading',
					'name'      => __( 'Kortingsactie', 'siw' ),
				],
				[
					'id'        => 'active',
					'name'      => __( 'Actief', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw'),
				],
				[
					'type'      => 'custom_html',
					'visible'   => [ 'workcamp_sale[active]', true ],
					'std'       => implode(
						BR,
						[
							sprintf(
								'%s: %s',
								__( 'Regulier', 'siw' ),
								Formatting::format_sale_amount( Properties::WORKCAMP_FEE_REGULAR, Properties::WORKCAMP_FEE_REGULAR_SALE )
							),
							sprintf(
								'%s: %s',
								__( 'Student', 'siw' ),
								Formatting::format_sale_amount( Properties::WORKCAMP_FEE_STUDENT, Properties::WORKCAMP_FEE_STUDENT_SALE )
							),
						]
					),
				],
				[
					'id'        => 'start_date',
					'name'      => __( 'Startdatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'workcamp_sale[active]', true ],
				],
				[
					'id'        => 'end_date',
					'name'      => __( 'Einddatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'workcamp_sale[active]', true ],
				],
			],
		];
		$fields[] = [
			'id'      => 'workcamp_teaser_text',
			'type'    => 'group',
			'tab'     => 'workcamps',
			'fields'  => [
				[
					'type'      => 'heading',
					'name'      => __( 'Aankondiging nieuw seizoen', 'siw' ),
					'desc'      => __( 'Wordt getoond op overzichten van Groepsprojecten.', 'siw' ),
				],
				[
					'id'        => 'active',
					'name'      => __( 'Tonen', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw'),
				],
				[
					'id'        => 'start_date',
					'name'      => __( 'Startdatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'workcamp_teaser_text[active]', true ],
				],
				[
					'id'        => 'end_date',
					'name'      => __( 'Einddatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'workcamp_teaser_text[active]', true ],
				],
			],
		];
		$fields[] = [
			'id'      => 'workcamp_approval',
			'type'    => 'group',
			'tab'     => 'workcamps',
			'fields'  => $approval_fields
		];

		//Op Maat
		$fields[] = [
			'id'      => 'tailor_made_sale',
			'type'    => 'group',
			'tab'     => 'tailor_made',
			'fields'  => [
				[
					'type'      => 'heading',
					'name'      => __( 'Kortingsactie', 'siw' ),
				],
				[
					'id'        => 'active',
					'name'      => __( 'Actief', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw'),
				],
				[
					'type'      => 'custom_html',
					'visible'   => [ 'tailor_made_sale[active]', true ],
					'std'       => implode(
						BR,
						[
							sprintf(
								'%s: %s',
								__( 'Regulier', 'siw' ),
								Formatting::format_sale_amount( Properties::TAILOR_MADE_FEE_REGULAR, Properties::TAILOR_MADE_FEE_REGULAR_SALE )
							),
							sprintf(
								'%s: %s',
								__( 'Student', 'siw' ),
								Formatting::format_sale_amount( Properties::TAILOR_MADE_FEE_STUDENT, Properties::TAILOR_MADE_FEE_STUDENT_SALE )
							),
						]
					),
				],
				[
					'id'        => 'start_date',
					'name'      => __( 'Startdatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'tailor_made_sale[active]', true ],
				],
				[
					'id'        => 'end_date',
					'name'      => __( 'Einddatum', 'siw' ),
					'type'      => 'date',
					'required'  => true,
					'visible'   => [ 'tailor_made_sale[active]', true ],
				],
			],
		];

		//Openingstijden
		$days = siw_get_days();

		/* Reguliere openingstijden */
		$opening_hours_fields[] = [
			'type'   => 'heading',
			'name'   => __( 'Reguliere openingstijden', 'siw' ),
		];
		foreach ( $days as $slug => $name ) {
			$day_fields = [
				'id'    => "day_{$slug}",
				'type'  => 'group',
				'fields' => [
					[
						'type'     =>'custom_html',
						'std'      => $name,
						'columns'  => 2,
					],
					[
						'id'        => 'open',
						'type'      => 'switch',
						'on_label'  => __( 'Geopend', 'siw' ),
						'off_label' => __( 'Gesloten', 'siw' ),
						'columns'   => 2,
					],
					[
						'id'       => 'opening_time',
						'type'     => 'time',
						'columns'  => 4,
						'prepend'  => __('Van', 'siw' ),
						'required' => true,
						'visible'  => [ "opening_hours[day_{$slug}][open]", true ],
					],
					[
						'id'       => 'closing_time',
						'type'     => 'time',
						'columns'  => 4,
						'prepend'  => __('Tot', 'siw' ),
						'required' => true,
						'visible'  => [ "opening_hours[day_{$slug}][open]", true ],
					],
				],
			];
			array_push( $opening_hours_fields, $day_fields );
		}

		/* Afwijkende openingstijden */
		$special_opening_hours_fields = [
			[
				'id'        => 'date',
				'type'      => 'date',
				'columns'   => 2,
			],
			[
				'id'        => 'opened',
				'type'      => 'switch',
				'on_label'  => __( 'Geopend', 'siw' ),
				'off_label' => __( 'Gesloten', 'siw' ),
				'columns'   => 2,
			],
			[
				'id'       => 'opening_time',
				'type'     => 'time',
				'columns'  => 4,
				'prepend'  => __( 'Van', 'siw' ),
				'required' => true,
				'visible'  => [ 'opened', true ],
			],
			[
				'id'       => 'closing_time',
				'type'     => 'time',
				'columns'  => 4,
				'prepend'  => __( 'Tot', 'siw' ),
				'required' => true,
				'visible'  => [ 'opened', true ],
			],
		];
		$fields[] = [
			'id'     => 'opening_hours',
			'type'   => 'group',
			'tab'    => 'opening_hours',
			'fields' => $opening_hours_fields,
		];
		$fields[] = [
			'type'   => 'heading',
			'name'   => __( 'Afwijkende openingstijden', 'siw' ),
			'tab'    => 'opening_hours',
		];
		$fields[] = [
			'id'     => 'special_opening_hours',
			'type'   => 'group',
			'tab'    => 'opening_hours',
			'clone'  => true,
			'fields' => $special_opening_hours_fields,
		];

		//Notificaties
		$fields[] = [
			'id'      => 'topbar',
			'type'    => 'group',
			'tab'     => 'notifications',
			'fields'  => [
				[
					'type'      => 'heading',
					'name'      => __( 'Banner', 'siw' ),
				],
				[
					'id'        => 'show_page_content',
					'name'      => __( 'Link naar pagina tonen', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw'),
				],
				[
					'id'      => 'page_content',
					'type'    => 'group',
					'visible' => [ 'topbar[show_page_content]', true ],
					'fields' => [
						[
							'id'                => 'intro',
							'name'              => __( 'Intro', 'siw' ),
							'type'              => 'text',
							'required'          => true,
							'label_description' => __( 'Wordt verborgen op mobiel', 'siw' ),
						],
						[
							'id'       => 'link_text',
							'name'     => __( 'Tekst voor link', 'siw' ),
							'type'     => 'text',
							'required' => true,
						],
						[
							'id'       => 'link_url',
							'name'     => __( 'URL voor link', 'siw' ),
							'type'     => 'url',
							'required' => true,
						],
						[
							'id'        => 'start_date',
							'name'      => __( 'Startdatum', 'siw' ),
							'type'      => 'date',
							'required'  => true,
						],
						[
							'id'        => 'end_date',
							'name'      => __( 'Einddatum', 'siw' ),
							'type'      => 'date',
							'required'  => true,
						],
					],
				],
				[
					'type'              => 'divider',
				],
				[
					'id'                => 'show_sale_content', //TODO: preview van gekozen items
					'name'              => __( 'Kortingsactie tonen', 'siw' ),
					'label_description' => __( 'Indien de kortingsactie actief is', 'siw' ),
					'type'              => 'switch',
					'on_label'          => __( 'Ja', 'siw' ),
					'off_label'         => __( 'Nee', 'siw'),
				],
				[
					'type'              => 'divider',
				],
				[
					'id'                => 'show_event_content',
					'name'              => __( 'Evenement tonen', 'siw' ),
					'label_description' => sprintf( __( 'Als er een evenement binnen %s dagen begint', 'siw' ), Topbar::EVENT_SHOW_DAYS_BEFORE ),
					'type'              => 'switch',
					'on_label'          => __( 'Ja', 'siw' ),
					'off_label'         => __( 'Nee', 'siw'),
				],
				[
					'type'              => 'divider',
				],
				[
					'id'                => 'show_job_posting_content',
					'name'              => __( 'Vacature tonen', 'siw' ),
					'label_description' => __( 'Indien er een actieve vacature uitgelicht is', 'siw' ),
					'type'              => 'switch',
					'on_label'          => __( 'Ja', 'siw' ),
					'off_label'         => __( 'Nee', 'siw'),
				],
			],
		];

		//Email
		$forms = [];
		if ( class_exists( '\Caldera_Forms_Forms' ) ) {
			$forms = Caldera_Forms_Forms::get_forms( true );
		}
		$forms[] = [
			'ID'   =>'workcamp',
			'name' => __( 'Groepsprojecten', 'siw' ),
		];
		$forms[] = [
			'ID'   => 'newsletter',
			'name' => __( 'Nieuwsbrief', 'siw' ),
		];

		$fields[]= [
			'id'     => 'email_settings',
			'type'   => 'group',
			'tab'    => 'email',
			'fields' => [
				[
					'type' => 'heading',
					'name' => __( 'Standaardinstellingen', 'siw' ),
					'desc' => __( 'Afzender en ontvanger van bevestigingsmail', 'siw' ),
				],
				[
					'id'      => 'email',
					'name'    => __( 'E-mailadres', 'siw' ),
					'type'    => 'email',
				],
				[
					'id'      => 'name',
					'name'    => __( 'Naam', 'siw' ),
					'type'    => 'text',
				],
				[
					'id'      => 'title',
					'name'    => __( 'Functie', 'siw' ),
					'type'    => 'text',
				],
			]
		];

		foreach ( $forms as $form ) {
			$fields[] = [
				'id'     => "{$form['ID']}_email",
				'type'   => 'group',
				'tab'    => 'email',
				'fields' => [
					[
						'type' => 'heading',
						'name' => $form['name'],
					],
					[
						'id'        => 'use_specific',
						'name'      => __( 'Gebruik afwijkende instellingen', 'siw' ),
						'type'      => 'switch',
						'on_label'  => __( 'Ja', 'siw' ),
						'off_label' => __( 'Nee', 'siw' ),
					],
					[
						'id'       => 'email',
						'name'     => __( 'E-mailadres', 'siw' ),
						'type'     => 'email',
						'required' => true,
						'visible'  => [ "{$form['ID']}_email[use_specific]", true ],
					],
					[
						'id'       => 'name',
						'name'     => __( 'Naam', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'visible'  => [ "{$form['ID']}_email[use_specific]", true ],
					],
					[
						'id'       => 'title',
						'name'     => __( 'Functie', 'siw' ),
						'type'     => 'text',
						'required' => true,
						'visible'  => [ "{$form['ID']}_email[use_specific]", true ],
					],
				]
			];
		}
		return $fields;
	}
}
