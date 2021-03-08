<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Util;
use SIW\Properties;

/**
 * Aanpassingen voor Caldera Forms
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://calderaforms.com/
 * @since     3.0.0
 */
class Caldera_Forms {

	/** Init */
	public static function init() {
		
		if ( ! is_plugin_active( 'caldera-forms/caldera-core.php') ) {
			return;
		}
		$self = new self();

		add_filter( 'kses_allowed_protocols', [ $self, 'allow_magic_tags' ] );

		//Verwijder wpautop van e-mails
		remove_filter( 'caldera_forms_mailer', [ \Caldera_Forms::get_instance(), 'format_message' ] );
		remove_filter( 'caldera_forms_autoresponse_mail', [ 'Caldera_Forms_Email_Filters', 'format_autoresponse_message' ] );

		add_filter( 'caldera_forms_save_revision', '__return_false' );
		add_filter( 'caldera_forms_magic_summary_should_use_label', '__return_true' );
		add_filter( 'caldera_forms_render_field_type-checkbox', [ $self, 'add_input_markup' ] );
		add_filter( 'caldera_forms_render_field_type-radio', [ $self, 'add_input_markup' ] );
		add_filter( 'caldera_forms_render_field_type-gdpr', [ $self, 'add_input_markup' ] );
		add_filter( 'caldera_forms_do_magic_tag', [ $self, 'set_summary_magic_table' ], 10, 2 );

		add_filter( 'caldera_forms_summary_magic_pattern', [ $self, 'set_summary_magic_pattern' ] );
		add_filter( 'caldera_forms_field_attributes', [ $self, 'set_validation_field_attributes' ] , 10, 2 );
		add_filter( 'caldera_forms_field_attributes', [ $self, 'add_field_classes' ], 10, 2 );
		add_filter( 'caldera_forms_render_assets_minify', '__return_false' );
		add_filter( 'caldera_forms_render_form_attributes' , [ $self, 'maybe_add_postcode_lookup'], 10, 2 );
		add_action( 'caldera_forms_render_end', [ $self, 'enqueue_script' ] );
		add_filter( 'caldera_forms_render_grid_settings', [ $self, 'setup_unsemantic_grid' ], 10, 2 );
	}

	/** Zorgt ervoor dat Magic tags in links toegestaan zijn */
	public function allow_magic_tags( array $protocols ) : array {
		$protocols[] = '{embed_post';
		return $protocols;
	}

	/** Voegt markup voor gestylde radiobuttons en checkboxes toe */
	public function add_input_markup( string $field_html ) : string {
		$field_html = preg_replace( '/<input(.*?)>/s', '<input$1><span class="checkmark"></span>', $field_html );
		return $field_html;
	}

	/** Voegt tabel om samenvatting toe */
	public function set_summary_magic_table( ?string $value, string $tag ) : ?string {
		if ( '{summary}' !== $tag ) {
			return $value;
		}
		$value = implode(
			'',
			[
				'<table width="100%" border="0" cellspacing="0" cellpadding="0">',
					'<tr>',
						sprintf(
							'<td colspan="3" height="20" style="font-family:Verdana, normal; color:%s; font-size:0.8em; font-weight:bold; border-top:thin solid %s" >', Properties::FONT_COLOR, Properties::PRIMARY_COLOR
						),
							esc_html__( 'Ingevulde gegevens', 'siw' ),
						'</td>',
					'</tr>',
					$value,
				'</table>'
			],
		);
		return $value;
	}

	/** Zet het patroon voor de samenvatting in e-mails toe */
	public function set_summary_magic_pattern( string $pattern ) : string {
		$pattern = '<tr>
			<td width="35%%" style="font-family: Verdana, normal; color:' . Properties::FONT_COLOR . '; font-size:0.8em;">%s</td>
			<td width="5%%"></td>
			<td width="50%%" style="font-family: Verdana, normal; color:' . Properties::FONT_COLOR . '; font-size:0.8em; font-style:italic">%s</td>
		</tr>';
		return $pattern;
	}

	/** Voegt attributes toe voor data-validatie */
	public function set_validation_field_attributes( array $attrs, array $field ) : array {

		if ( ! isset( $field['config']['validation'] ) ) {
			return $attrs;
		}
		
		switch ( $field['config']['validation'] ) {
			case 'date':
				$attrs[ 'data-parsley-pattern-message' ] = __( 'Dit is geen geldige datum.', 'siw' );
				$attrs[ 'data-parsley-pattern' ] = Util::get_regex( 'date' );
				break;
			case 'postcode':
				$attrs[ 'data-parsley-pattern-message' ] = __( 'Dit is geen geldige postcode.', 'siw' );
				$attrs[ 'data-parsley-pattern' ] = Util::get_regex( 'postcode' );
				break;
			default:
		}
		return $attrs;
	}

	/** Voegt veldklasses toe */
	public function add_field_classes( array $attrs, array $field ) : array {
		if ( 'dropdown' === $field['type'] ) {
			$attrs['class'] .= SPACE . 'select-css';
		}
		if ( 'checkbox' === $field['type'] ) {
			$attrs['class'] .= SPACE . 'checkbox-css';
		}
		return $attrs;
	}

	/** Voegt attribute voor postcode lookup toe */
	public function maybe_add_postcode_lookup( array $attributes, array $form ) : array {
		$attributes['data-siw-postcode-lookup'] = isset( $form['postcode_lookup'] ) && $form['postcode_lookup'];
		return $attributes;
	}

	/** Voegt script toe Postcode lookup en Google Analytics event */
	public function enqueue_script() {
		wp_register_script( 'siw-cf-caldera-forms', SIW_ASSETS_URL . 'js/siw-caldera-forms.js', ['siw-api-postcode', 'siw-analytics', 'jquery'], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-cf-caldera-forms' );
	}

	/** Voegt responsive classes voor Unsemantic grid toe */
	public function setup_unsemantic_grid( array $grid, array $form ) : array {
		$grid['before'] = '<div %1$s class="row grid-container grid-parent %2$s">';
		$grid['column_before'] = '<div %1$s class="grid-%2$d %3$s">';
		return $grid;
	}
}
