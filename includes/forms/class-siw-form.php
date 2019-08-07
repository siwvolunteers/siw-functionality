<?php

/**
 * Class om een Caldera Forms formulier toe te voegen
 * 
 * @package   SIW\Forms
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */
class SIW_Form {

	/**
	 * Standaard cell-breedte
	 */
	const DEFAULT_CELL_WIDTH = 6;

	/**
	 * ID van het formulier
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Formulier
	 *
	 * @var array
	 */
	protected $form;

	/**
	 * Data uit configuratiebestand
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Instellingen voor e-mail
	 *
	 * @var array
	 */
	protected $email_settings;

	/**
	 * Init
	 *
	 * @param string $id
	 */
	public function __construct( string $id ) {
		$this->id = $id;
		add_filter( 'caldera_forms_get_forms', [ $this, 'add_to_forms'] );
		add_filter( "caldera_forms_get_form-{$this->id}", [ $this, 'add_form' ] );
	}

	/**
	 * Laadt data uit bestand
	 * 
	 * @return bool
	 */
	protected function load_data() {
		$data = siw_get_data( $this->id, 'forms' ); //TODO: str_replace 
		if ( null === $data ) {
			return false;
		}
		$this->data = wp_parse_args_recursive( $data, [
			'args'          => [],
			'intro'         => '',
			'pages'         => [],
			'fields'        => [],
			'confirmation'  => [],
			'notification'  => [],
			'email_option'  => '',
			'processors'    => [],
		]);
		return true;
	}

	/**
	 * Voegt formulier toe
	 *
	 * @param array $forms
	 */
	public function add_to_forms( array $forms ) {
		$forms[ $this->id ] = apply_filters( "caldera_forms_get_form-{$this->id}", [] );
		return $forms;
	}

	/**
	 * Voegt eigenschappen van formulier toe
	 *
	 * @param array $form
	 */
	public function add_form( array $form ) {
		
		$form = wp_cache_get( $this->id, 'siw_forms' );
		if ( false !== $form ) {
			return $form;
		}

		if ( false === $this->load_data() ) {
			return [];
		}
		$this->email_settings = siw_get_option( $this->data['email_option'] );
		$this->init_form();
		$this->set_pages();
		$this->set_fields();
		$this->set_mailer();
		$this->set_autoresponder();
		$this->set_processors();

		wp_cache_set( $this->id, $this->form, 'siw_forms' );

		return $this->form;
	}

	/**
	 * Initialiseer formulier
	 */
	protected function init_form() {
		$this->form = [
			'ID'                 => $this->id,
			'name'               => __( 'Formulier', 'siw' ),
			'db_support'         => false,
			'pinned'             => false,
			'pin_roles'          => [],
			'hide_form'          => true,
			'check_honey'        => true,
			'success'            => __( 'Je bericht werd succesvol verzonden.', 'siw' ),
			'form_ajax'          => true,
			'scroll_top'         => true,
			'has_ajax_callback'  => true,
			'custom_callback'    => 'siwSendGaFormSubmissionEvent',
			'layout_grid'        => [],
			'fields'             => [],
			'page_names'         => [],
			'auto_progress'      => true,
			'processors'         => [],
			'conditional_groups' => [],
			'settings'           => [
				'responsive' => [
					'break_point' => 'sm',
				],
			],
			'mailer'             => []
		];
	}

	/**
	 *  Zet instellingen van mailer
	 */
	protected function set_mailer() {
		$notification = $this->data['notification'];

		$this->form['mailer'] = [
			'on_insert'     => true,
			'sender_name'   => __( 'Website', 'siw' ),
			'sender_email'  => $this->email_settings['sender'],
			'reply_to'      => $notification['reply_to'],
			'email_type'    => 'html',
			'recipients'    => $this->email_settings['sender'],
			'email_subject' => $notification['subject'],
			'email_message' => $this->get_email_template(
				[
					'subject'         => $notification['subject'],
					'message'         => $notification['message'],
					'show_signature'  => false,
					'show_summary'    => true,
				]
			),
		];
	}

	/**
	 * Zet instellingen van autoresponder
	 */
	protected function set_autoresponder() {
		$confirmation = $this->data['confirmation'];

		$config = [
			'sender_name'     => SIW_Properties::NAME,
			'sender_email'    => $this->email_settings['sender'],
			'subject'         => $confirmation['subject'],
			'recipient_name'  => $confirmation['recipient_name'],
			'recipient_email' => $confirmation['recipient_email'],
			'message'         => $this->get_email_template(
				[
					'subject'         => $confirmation['subject'],
					'message'         => $confirmation['message'],
					'show_signature'  => true,
					'signature_name'  => $this->email_settings['signature']['name'],
					'signature_title' => $this->email_settings['signature']['title'],
					'show_summary'    => true,
				]
			),
		];
		$this->add_processor( 'confirmation', 'auto_responder', $config );
	}

	/**
	 * Voegt formprocessor toe
	 *
	 * @param string $id
	 * @param string $type
	 * @param array $config
	 * @param array $conditions
	 */
	protected function add_processor( string $id, string $type, array $config, array $conditions = [] ) {
		$this->form['processors']["fp_{$id}"] = [
			'ID'         => "fp_{$id}",
			'type'       => $type,
			'config'     => $config,
			'conditions' => $conditions,
		];
	}

	/**
	 * Voeg formprocessors toe
	 */
	protected function set_processors() {
		foreach ( $this->data['processors'] as $processor ) {
			$this->add_processor( $processor['id'], $processor['type'], $processor['config'] );
		}
	}

	/**
	 * Voegt pagina's toe
	 * 
	 * @todo: naam van formulier gebruiken als fallback
	 */
	protected function set_pages() {
		if ( empty( $this->data['pages'] ) ) {
			$this->form['page_names'] = [
				__( 'Formulier', 'siw' ),
			];
		}
		else {
			foreach ( $this->data['pages'] as $page ) {
				$this->form['page_names'][] = $page;
			}
		}
	}

	/**
	 * Zet de velden van het formulier
	 */
	protected function set_fields() {
		//Bepaal index van laatste pagina
		$last_page = count( $this->data['fields'] ) - 1;

		$form_structure = [];
		$row_index = 0;
		foreach ( $this->data['fields'] as $page_index => $rows ) {

			// Introtekst toevoegen als veld op eerste pagina
			if ( 0 == $page_index && ! empty( $this->data['intro'] ) ) {
				array_unshift(
					$rows,
					[
						[
							'slug'   => 'intro',
							'type'   => 'html',
							'width'  => 12,
							'config' => [
								'default' => SIW_Formatting::array_to_text( $this->data['intro'] ) . HR,
							],
						],
					]
				);
			}

			//Knoppen toevoegen
			$button_row = $this->generate_button_row( $page_index, $last_page );
			if ( ! empty( $button_row ) ) {
				array_push( $rows, $button_row );
			}

			$page_structure = [];
			foreach ( $rows as $row ) {
				$row_structure = [];
				$row_index ++;
				$cell_index = 0;
				foreach ( $row as $field ) {
					//TODO: $this->add_field();
	
					//Formatteer opties
					if ( isset( $field['config']['option'] ) ) {
						$field['config']['option'] = $this->format_options( $field['config']['option'] );
					}

					//Voeg condities toe
					if ( isset( $field['condition'] ) ) {
						$this->add_conditional_group( $field['slug'], $field['condition']['type'], $field['condition']['groups'] );
						$field['conditions'] = [ 'type' => "con_{$field['slug']}" ];
						unset( $field['condition'] );
					}
	
					$this->form['fields'][ $field['slug'] ] = wp_parse_args_recursive(
						$field,
						[
							'ID'         => $field['slug'],
							'label'      => '',
							'required'   => true,
							'caption'    => '',
							'config'     => [
								'custom_class' => '',
								'default'      => '',
							],
							'conditions' => [ 'type' => ''],
						]
					);
					
					if ( ! isset( $field['same_cell'] ) ) {
						$cell_index++;
						$row_structure[] = $field['width'] ?? self::DEFAULT_CELL_WIDTH;
					}
					$layout_fields[ $field['slug'] ] = "{$row_index}:{$cell_index}";
				}
				$page_structure[] = implode( ':', $row_structure );

			}
			$form_structure[] = implode( '|', $page_structure );
		}

		$this->form['layout_grid'] = [
			'fields'    => $layout_fields,
			'structure' => implode( '#', $form_structure ) . '|12',
		];
	}

	/**
	 * Genereert knoppenrij
	 *
	 * @param int $current_page
	 * @param int $last_page
	 * @return array
	 */
	protected function generate_button_row( int $current_page, int $last_page ) {
		$button_row = [];
		

		// Vorige knop toevoegen
		if ( 0 != $current_page ) {
			$button_row[] = [
				'slug'    => "from_screen_{$current_page}_to_previous",
				'type'    => 'button',
				'label'   => __( 'Vorige', 'siw' ),
				'config'  => [
					'type'         => 'prev',
					'class'        => 'kad-btn',
				],
			];
		}

		//Volgende knop toevoegen
		if ( $last_page != $current_page ) {
			$button_row[] = [
				'slug'    => "from_screen_{$current_page}_to_next",
				'type'    => 'button',
				'label'   => __( 'Volgende', 'siw' ),
				'config'  => [
					'type'         => 'next',
					'class'        => 'kad-btn kad-btn-primary',
				],
			];
		}

		//Verzenden knop toevoegen
		if ( $last_page == $current_page  ) {
			$button_row[] = [
				'slug'    => 'verzenden',
				'type'    => 'button',
				'label'   => __( 'Verzenden', 'siw' ),
				'config'  => [
					'type'         => 'submit',
					'class'        => 'kad-btn kad-btn-primary',
				],
			];
		}

		return $button_row;
	}

	/**
	 * Voegt condities toe
	 *
	 * @param string $slug
	 * @param string $type
	 * @param array $groups
	 */
	protected function add_conditional_group( string $slug, string $type, array $groups ) {
		$condition_groups = [];

		foreach ( $groups as $group_index => $group ) {
			$condition_lines = [];

			foreach ( $group as $line_index => $line ) {
				$line['parent'] = "con_{$slug}_group_{$group_index}";
				$condition_lines["con_{$slug}_group_{$group_index}_line_{$line_index}"] = $line;
			}
			$condition_groups["con_{$slug}_group_{$group_index}"] = $condition_lines;;
		}

		$this->form['conditional_groups']['conditions'][ "con_{$slug}" ] = [
			'id'    => "con_{$slug}",
			'name'  => $slug,
			'type'  => $type,
			'group' => $condition_groups,
		];
	}

	/**
	 * Formatteert array met opties
	 *
	 * @param array $options
	 * @return string
	 */
	protected function format_options( array $options ) {

		$has_values = ( array_values( $options ) !== $options );

		foreach ( $options as $value => $label ) {
			$slug = $has_values ? sanitize_title( $value ) : sanitize_title( $label );
			$formatted_options[ $slug ] = [
				'value' => $slug,
				'label' => $label,
			];
		}
		return $formatted_options;
	}

	/**
	 * Haalt e-mailtemplate op
	 *
	 * @param array $args
	 * @return string
	 * 
	 * @todo aparte class voor template maken
	 */
	protected function get_email_template( array $args ) {
		return siw_get_email_template( $args );
	}
}