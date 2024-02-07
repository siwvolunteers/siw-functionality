<?php declare(strict_types=1);

namespace SIW\Options;

class Help extends Option {

	#[\Override]
	public function get_title(): string {
		return __( 'Help', 'siw' );
	}

	#[\Override]
	public function get_capability(): string {
		return 'edit_posts';
	}

	#[\Override]
	public function get_parent_page(): string {
		return 'options-general.php';
	}

	#[\Override]
	public function get_tabs(): array {
		$tabs = [
			[
				'id'    => 'faq',
				'label' => __( 'Q&A', 'siw' ),
				'icon'  => 'dashicons-editor-help',
			],
		];

		foreach ( $this->get_post_types() as $post_type ) {
			$tabs[] = [
				'id'    => $post_type->name,
				'label' => $post_type->label,
				'icon'  => $post_type->menu_icon,
			];
		}

		return $tabs;
	}

	#[\Override]
	public function get_fields(): array {
		$fields = [];

		$fields[] = [
			'id'     => 'faq',
			'type'   => 'group',
			'tab'    => 'faq',
			'fields' => [
				[
					'id'        => 'show_page',
					'name'      => __( 'Toon Q&A pagina', 'siw' ),
					'type'      => 'switch',
					'on_label'  => __( 'Ja', 'siw' ),
					'off_label' => __( 'Nee', 'siw' ),
				],
				[
					'id'            => 'questions',
					'type'          => 'group',
					'clone'         => true,
					'sort_clone'    => true,
					'collapsible'   => true,
					'default_state' => 'collapsed',
					'group_title'   => [ 'field' => 'question' ],
					'visible'       => [ 'faq[show_page]', true ],
					'add_button'    => __( 'Vraag toevoegen', 'siw' ),
					'fields'        => [
						[
							'id'       => 'question',
							'name'     => __( 'Vraag', 'siw' ),
							'type'     => 'text',
							'required' => true,
						],
						[
							'id'       => 'answer',
							'name'     => __( 'Antwoord', 'siw' ),
							'type'     => 'textarea',
							'required' => true,
						],
					],
				],
			],
		];

		foreach ( $this->get_post_types() as $post_type ) {
			$fields[] = [
				'id'         => "{$post_type->name}_help_tabs",
				'type'       => 'group',
				'tab'        => $post_type->name,
				'add_button' => __( 'Vraag toevoegen', 'siw' ) . $post_type->label,
				'fields'     => [
					[
						'id'     => 'edit',
						'type'   => 'group',
						'fields' => [
							[
								'type' => 'heading',
								'name' => __( 'Overzichtsscherm', 'siw' ),
							],
							[
								'id'        => 'show_help_tabs',
								'name'      => __( 'Toon help tabs', 'siw' ),
								'type'      => 'switch',
								'on_label'  => __( 'Ja', 'siw' ),
								'off_label' => __( 'Nee', 'siw' ),
							],
							[
								'id'            => 'help_tabs',
								'type'          => 'group',
								'clone'         => true,
								'sort_clone'    => true,
								'collapsible'   => true,
								'default_state' => 'collapsed',
								'group_title'   => [ 'field' => 'title' ],
								'visible'       => [ "{$post_type->name}_help_tabs[edit][show_help_tabs]", true ],
								'add_button'    => __( 'Tab toevoegen', 'siw' ),
								'fields'        => [
									[
										'id'       => 'title',
										'name'     => __( 'Titel', 'siw' ),
										'type'     => 'text',
										'required' => true,
									],
									[
										'id'       => 'content',
										'name'     => __( 'Inhoud', 'siw' ),
										'type'     => 'textarea',
										'required' => true,
									],
								],
							],
						],
					],
					[
						'id'     => 'post',
						'type'   => 'group',
						'fields' => [
							[
								'type' => 'heading',
								'name' => __( 'Individueel scherm', 'siw' ),
							],
							[
								'id'        => 'show_help_tabs',
								'name'      => __( 'Toon help tabs', 'siw' ),
								'type'      => 'switch',
								'on_label'  => __( 'Ja', 'siw' ),
								'off_label' => __( 'Nee', 'siw' ),
							],
							[
								'id'            => 'help_tabs',
								'type'          => 'group',
								'clone'         => true,
								'sort_clone'    => true,
								'collapsible'   => true,
								'default_state' => 'collapsed',
								'group_title'   => [ 'field' => 'title' ],
								'visible'       => [ "{$post_type->name}_help_tabs[post][show_help_tabs]", true ],
								'add_button'    => __( 'Tab toevoegen', 'siw' ),
								'fields'        => [
									[
										'id'       => 'title',
										'name'     => __( 'Titel', 'siw' ),
										'type'     => 'text',
										'required' => true,
									],
									[
										'id'       => 'content',
										'name'     => __( 'Inhoud', 'siw' ),
										'type'     => 'textarea',
										'required' => true,
									],
								],
							],
						],

					],
				],
			];
		}

		return $fields;
	}


	/**
	 * Geeft array van post types terug
	 *
	 * @return \WP_Post_Type[]
	 */
	protected function get_post_types(): array {
		return get_post_types( [ 'public' => true ], 'objects' );
	}
}
