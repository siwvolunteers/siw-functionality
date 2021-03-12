<?php declare(strict_types=1);

namespace SIW\Forms;

use SIW\Interfaces\Forms\Form as Form_Interface;
use SIW\Interfaces\Forms\Multi_Page_Form as Multi_Page_Form_Interface;

use SIW\Email\Template;
use SIW\Properties;
use SIW\Util;

/**
 * Class om een Caldera Forms formulier toe te voegen
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Form {

	/** Key voor cache group */
	const CACHE_GROUP = 'siw_forms';

	/** Formulier */
	protected Form_Interface $form;

	/** Pagina's */
	protected array $pages;

	/** Init */
	public function __construct( Form_Interface $form ) {
		$this->form = $form;
	}

	/** Registreer formulier */
	public function register() {
		add_filter( 'caldera_forms_get_forms', [ $this, 'add_to_forms'] );
		add_filter( "caldera_forms_get_form-{$this->form->get_id()}", [ $this, 'add_form' ] );
	}

	/** Zet pagina's */
	public function set_pages( Multi_Page_Form_Interface $multi_page_form ) {
		add_filter(
			"siw_form_{$this->form->get_id()}",
			fn( array $form ) : array => wp_parse_args( ['page_names' => $multi_page_form->get_pages()], $form )
		);
	}

	/** Voegt formulier toe */
	public function add_to_forms( array $forms ) {
		$forms[ $this->form->get_id() ] = apply_filters( "caldera_forms_get_form-{$this->form->get_id()}", [] );
		return $forms;
	}

	/** Voegt eigenschappen van formulier toe */
	public function add_form( array $form ) : array {
		$form = wp_cache_get( $this->form->get_id(), self::CACHE_GROUP );
		if ( false !== $form ) {
			return $form;
		}
		$form = $this->get_form();

		wp_cache_set( $this->form->get_id(), $form, self::CACHE_GROUP );
		return $form;
	}

	/** Geef formulier terug */
	protected function get_form() : array {
		$form = [
			'ID'                 => $this->form->get_id(),
			'name'               => $this->form->get_name(),
			'db_support'         => false,
			'pinned'             => false,
			'pin_roles'          => [],
			'hide_form'          => true,
			'check_honey'        => true,
			'success'            => __( 'Je bericht werd succesvol verzonden.', 'siw' ),
			'form_ajax'          => true,
			'scroll_top'         => true,
			'has_ajax_callback'  => true,
			'layout_grid'        => $this->get_layout_grid(),
			'fields'             => $this->get_fields(),
			'page_names'         => [ $this->form->get_name() ],
			'auto_progress'      => true,
			'processors'         => $this->get_processors(),
			'conditional_groups' => [],
			'settings'           => [
				'responsive' => [
					'break_point' => 'sm',
				],
			],
			'mailer'             => $this->get_mailer(),
		];
		return apply_filters( "siw_form_{$this->form->get_id()}", $form );
	}

	/** Genereer layout grid */
	protected function get_layout_grid() : array {
		$fields = $this->get_fields();
		$keys = ['slug', 'page', 'width'];
		$fields = array_map(
			fn( array $field ) : array => wp_array_slice_assoc( $field, $keys ),
			$fields,
		);
		
		$layout_fields = [];
		$layout_structure = '';
		$row_index = 1;
		$cell_index = 0;
		$row_width = 0;
		$last_page = 1;

		foreach ( $fields as $field ) {
			
			$current_page = $field['page'];
			$new_row = false;
			$row_width += $field['width'];
			$cell_index++;

			if ( $row_width > Form_Interface::FULL_WIDTH ) {
				$new_row = true;
				$row_index++;
				$row_width = $field['width'];
				$cell_index = 1;
			}

			$layout_fields[ $field['slug'] ] = "{$row_index}:{$cell_index}";
			if ( 1 == $row_index && 1 == $cell_index ) {
				$layout_structure = $field['width'];
			}
			elseif ( $current_page != $last_page ) {
				$layout_structure .= "#{$field['width']}";
			}
			elseif ( $new_row ) {
				$layout_structure .= "|{$field['width']}";
			}
			else {
				$layout_structure .= ":{$field['width']}";
			}
			$last_page = $current_page;
		}

		return [
			'fields'    => $layout_fields,
			'structure' => $layout_structure
		];

	}

	/** Geeft velden terug */
	protected function get_fields() {
		$fields = array_map(
			[$this, 'parse_field'],
			$this->form->get_fields()
		);

		$fields = $this->add_buttons( $fields );
		$fields = array_column( $fields, null, 'slug' );
		return $fields;
	}

	/** Parset veld */
	protected function parse_field( array $field ) : array {
		$defaults = [
			'label'      => '',
			'required'   => true,
			'width'      => Form_Interface::HALF_WIDTH,
			'page'       => 1,
			'conditions' => [ 'type' => ''],

		];
		$field = wp_parse_args_recursive( $field, $defaults );

		//Zet slug als ID
		$field['ID'] = $field['slug'];
		
		//Formatteer opties
		if ( isset( $field['config']['option'] ) ) {
			$field['config']['option'] = $this->format_options( $field['config']['option'] );
		}

		return $field;
	}

	/** Voegt knoppen toe */
	protected function add_buttons( $fields ) : array {
		$fields_per_page = $this->group_fields_by_page( $fields );
		$fields = [];

		$last_page = sizeof( $fields_per_page );
		foreach ( $fields_per_page as $page => $page_fields ) {
			// 'Vorige'-knop toevoegen
			if ( 1 !== $page ) {
				$page_fields[] = [
					'ID'      => "from_page_{$page}_to_previous",
					'slug'    => "from_page_{$page}_to_previous",
					'type'    => 'button',
					'label'   => __( 'Vorige', 'siw' ),
					'page'    => $page,
					'width'   => Form_Interface::HALF_WIDTH,
					'conditions' => [ 'type' => ''],
					'config'  => [
						'type'         => 'prev',
						'class'        => 'ghost',
					],
				];
			}

			// 'Volgende'-knop toevoegen
			if ( $last_page != $page ) {
				$page_fields[] = [
					'ID'      => "from_page_{$page}_to_next",
					'slug'    => "from_page_{$page}_to_next",
					'type'    => 'button',
					'label'   => __( 'Volgende', 'siw' ),
					'page'    => $page,
					'width'   => ( 1 == $page ) ? Form_Interface::FULL_WIDTH : Form_Interface::HALF_WIDTH,
					'conditions' => [ 'type' => ''],
					'config'  => [
						'type'         => 'next',
						'class'        => '',
					],
				];
			}

			// 'Verzenden'-knop toevoegen
			if ( $last_page == $page ) {
				$page_fields[] = [
					'ID'      => 'verzenden',
					'slug'       => 'verzenden',
					'type'       => 'button',
					'label'      => __( 'Verzenden', 'siw' ),
					'page'       => $page,
					'width'      => ( 1 == $page ) ? Form_Interface::FULL_WIDTH : Form_Interface::HALF_WIDTH,
					'conditions' => [ 'type' => ''],
					'config'  => [
						'type'         => 'submit',
						'class'        => '',
					],
				];
			}

			$fields = array_merge( $fields, $page_fields );
		}
		return $fields;
	}

	/** Geeft mailer instellingen teug */
	protected function get_mailer() : array {
		return [
			'on_insert'     => true,
			'sender_name'   => __( 'Website', 'siw' ),
			'sender_email'  => $this->get_email_setting( 'email' ),
			'reply_to'      => "%{$this->get_primary_email()}%",
			'email_type'    => 'html',
			'recipients'    => $this->get_email_setting( 'email' ),
			'email_subject' => $this->form->get_notification_subject(),
			'email_message' => $this->get_email_template(
				[
					'subject'         => $this->form->get_notification_subject(),
					'message'         => $this->form->get_notification_message(),
					'show_signature'  => false,
					'show_summary'    => true,
				]
			),
		];
	}

	/** Voegt processors toe */
	protected function get_processors() : array {
		$processors = [];

		//Autoresponder
		$processors['fp_confirmation'] = [
			'ID'         => 'fp_confirmation',
			'type'       => 'auto_responder',
			'config'     => $this->get_confirmation_config(),
			'conditions' => [],
		];

		//Spam check
		$processors['fp_spam_check'] = [
			'ID'         => 'fp_spam_check',
			'type'       => 'siw_spam_check',
			'config'     => [ 'email' => $this->get_primary_email() ],
			'conditions' => [],
		];
		
		return $processors;
	}

	/** Geeft configuratie van bevestigingsmail terug */
	protected function get_confirmation_config() : array {
		$config = [
			'sender_name'     => Properties::NAME,
			'sender_email'    => $this->get_email_setting( 'email' ),
			'subject'         => $this->form->get_autoresponder_subject(),
			'recipient_name'  => $this->get_recipient_name(),
			'recipient_email' => "%{$this->get_primary_email()}%",
			'message'         => $this->get_email_template(
				[
					'subject'         => $this->form->get_autoresponder_subject(),
					'message'         => $this->form->get_autoresponder_message(),
					'show_signature'  => true,
					'signature_name'  => $this->get_email_setting( 'name' ),
					'signature_title' => $this->get_email_setting( 'title' ),
					'show_summary'    => true,
				]
			),
		];
		return $config;
	}

	/** Geeft slug van primary email adres terug TODO: fallback naar eerste email veld? */
	protected function get_primary_email() : string {
		$primary_email_fields = wp_list_filter( $this->form->get_fields(), [ 'primary_email' => true ] );
		$slugs = wp_list_pluck( $primary_email_fields, 'slug' );
		return reset( $slugs );
	}

	/** Haal e-mail instelling op */
	protected function get_email_setting( string $setting ) : string {
		$email_settings = siw_get_email_settings( $this->form->get_id() );
		return $email_settings[ $setting ] ?? '';
	}

	/** Haal naam voor bevestigingsmail op */
	protected function get_recipient_name() : string {
		$recipient_name_fields = wp_list_filter( $this->form->get_fields(), [ 'recipient_name' => true ] );
		$slugs = wp_list_pluck( $recipient_name_fields, 'slug' );
		$slugs = array_map(
			fn( string $slug ) : string => "%{$slug}%",
			$slugs
		);

		return implode( SPACE, $slugs );
	}

	/** Groepeer velden per pagina */
	function group_fields_by_page( array $fields ) : array {
		$fields_by_page = [];

		foreach ( $fields as $field ) {
			if ( array_key_exists( 'page', $field ) ) {
				$fields_by_page[ $field['page'] ][] = $field;
			} else {
				$fields_by_page[""][] = $field;
			}
		}
		return $fields_by_page;
	}

	/** Formatteert array met opties */
	protected function format_options( array $options ) : array {
		$has_values = ! wp_is_numeric_array( $options ); //TODO: is dit echt nodig
		foreach ( $options as $value => $label ) {
			$slug = $has_values ? sanitize_title( $value ) : sanitize_title( $label );
			$formatted_options[ $slug ] = [
				'value' => $slug,
				'label' => $label,
			];
		}
		return $formatted_options;
	}

	/** Haalt e-mailtemplate op */
	protected function get_email_template( array $args ) : string {
		$template = new Template( $args );
		return $template->generate();
	}
}
