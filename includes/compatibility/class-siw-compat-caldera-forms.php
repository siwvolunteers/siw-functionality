<?php

/**
 * Aanpassingen voor Caldera Forms
 * 
 * @package   SIW\Compatibility
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Properties
 * @uses      SIW_Util
 */

class SIW_Compat_Caldera_Forms{ 

	/**
	 * Init
	 */
	public static function init() {
		
		if ( ! class_exists( 'Caldera_Forms' ) ) {
			return;
		}
		$self = new self();

		add_filter( 'kses_allowed_protocols', [ $self, 'allow_magic_tags' ] );

		$self->disable_wpautop();
		$self->remove_shortcode_button();

		add_filter( 'caldera_forms_save_revision', '__return_false' );
		add_filter( 'caldera_forms_magic_summary_should_use_label', '__return_true' );
		add_filter( 'caldera_forms_render_field_type-checkbox', [ $self, 'add_input_markup' ] );
		add_filter( 'caldera_forms_render_field_type-radio', [ $self, 'add_input_markup' ] );
		add_filter( 'caldera_forms_render_field_type-gdpr', [ $self, 'add_input_markup' ] );
		add_filter( 'caldera_forms_do_magic_tag', [ $self, 'set_summary_magic_table' ], 10, 2 );

		add_filter( 'caldera_forms_summary_magic_pattern', [ $self, 'set_summary_magic_pattern' ] );
		add_filter( 'caldera_forms_field_attributes', [ $self, 'set_validation_field_attributes' ] , 10, 2 );
		add_action( 'caldera_forms_render_end', [ $self, 'maybe_add_postcode_script'] );
		add_filter( 'caldera_forms_render_assets_minify', '__return_false' );
	}

	/**
	 * Verwijdert shortcode knop
	 *
	 * @return void
	 */
	public function remove_shortcode_button() {
		remove_action( 'media_buttons', array( Caldera_Forms_Admin::get_instance(), 'shortcode_insert_button' ), 11 );
		add_filter( 'caldera_forms_insert_button_include', '__return_false' );
	}

	/**
	 * Zorgt ervoor dat Magic tags in links toegestaan zijn
	 *
	 * @param array $protocols
	 * @return array
	 */
	public function allow_magic_tags( array $protocols ) {
		$protocols[] = '{embed_post';
		return $protocols;
	}

	/**
	 * Verwijdert wpautop van e-mails
	 */
	public function disable_wpautop() {
		remove_filter( 'caldera_forms_mailer', array( Caldera_Forms::get_instance(), 'format_message' ) );
		remove_filter( 'caldera_forms_autoresponse_mail', array( 'Caldera_Forms_Email_Filters', 'format_autoresponse_message' ) );
	}

	/**
	 * Voegt markup voor gestylde radiobuttons en checkboxes toe
	 *
	 * @param string $field_html
	 * @return string
	 */
	public function add_input_markup( string $field_html ) {
		$field_html = preg_replace( '/<input(.*?)>/s', '<input$1><div class="control-indicator"></div>', $field_html );
		return $field_html;
	}

	/**
	 * Voegt tabel om samenvatting toe
	 *
	 * @param string $value
	 * @param string $tag
	 * @return string
	 */
	public function set_summary_magic_table( ?string $value, string $tag ) {
		if ( '{summary}' !== $tag ) {
			return $value;
		}
		$value = SIW_Formatting::array_to_text(
			[
				'<table width="100%" border="0" cellspacing="0" cellpadding="0">',
					'<tr>',
						sprintf(
							'<td colspan="3" height="20" style="font-family:Verdana, normal; color:%s; font-size:0.8em; font-weight:bold; border-top:thin solid %s" >', SIW_Properties::FONT_COLOR, SIW_Properties::PRIMARY_COLOR
						),
							esc_html__( 'Ingevulde gegevens', 'siw' ),
						'</td>',
					'</tr>',
					$value,
				'</table>'
			],
			''
		);

		return $value;
	}

	/**
	 * Zet het patroon voor de samenvatting in e-mails toe
	 *
	 * @param string $pattern
	 * @return string
	 */
	public function set_summary_magic_pattern( string $pattern ) {
		$pattern = '<tr>
			<td width="35%%" style="font-family: Verdana, normal; color:' . SIW_Properties::FONT_COLOR . '; font-size:0.8em;">%s</td>
			<td width="5%%"></td>
			<td width="50%%" style="font-family: Verdana, normal; color:' . SIW_Properties::FONT_COLOR . '; font-size:0.8em; font-style:italic">%s</td>
		</tr>';
		return $pattern;
	}

	/**
	 * Voegt attributes toe voor data-validatie
	 * 
	 * - Datum
	 * - Postcode
	 *
	 * @param array $attrs
	 * @param array $field
	 * @return array
	 */
	public function set_validation_field_attributes( array $attrs, array $field ) {
		if ( 'geboortedatum' === $field['ID'] ) {
			$attrs[ 'data-parsley-pattern-message' ] = __( 'Dit is geen geldige datum.', 'siw' );
			$attrs[ 'data-parsley-pattern' ] = SIW_Util::get_regex( 'date' );
		}
	
		if ( 'postcode' === $field['ID'] ) {
			$attrs[ 'data-parsley-pattern-message' ] = __( 'Dit is geen geldige postcode.', 'siw' );
			$attrs[ 'data-parsley-pattern' ] = SIW_Util::get_regex( 'postal_code' );
		}
		return $attrs;
	}

	/**
	 * Voegt inline script voor postcode lookup toe als formulier een postcode-veld bevat
	 *
	 * @param array $form
	 * 
	 * @todo instelling bij formulier i.p.v. afleiden van veld
	 */
	public function maybe_add_postcode_script( array $form ) {

		if ( isset( $form['fields']['postcode'] ) ) {

			$inline_script = "
			$( 'input[name=\'postcode\'], input[name=\'huisnummer\']' ).change(function() {
				siwPostcodeLookupFromForm( 'input[name=\'postcode\']', 'input[name=\'huisnummer\']', 'input[name=\'straat\']', 'input[name=\'woonplaats\']' );
				return false;
			});";
			wp_add_inline_script( 'siw-postcode', "(function( $ ) {" . $inline_script . "})( jQuery );" );
		}

	}
}