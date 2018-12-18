<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen voor Caldera Forms
 * 
 * @package   SIW\Compatibility
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Properties
 */

class SIW_Caldera_Forms{ 

	/**
	 * Init
	 *
	 * @return void
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
		add_filter( 'caldera_forms_summary_magic_pattern', [ $self, 'set_summary_magic_pattern' ] );
		add_filter( 'caldera_forms_field_attributes', [ $self, 'set_validation_field_attributes' ] , 10, 2 );
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
	 * @return void
	 */
	public function allow_magic_tags( $protocols ) {
		$protocols[] = '{embed_post';
		return $protocols;
	}

	/**
	 * Verwijdert wpautop van e-mails
	 *
	 * @return void
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
	public function add_input_markup( $field_html ) {
		$field_html = preg_replace( '/<input(.*?)>/s', '<input$1><div class="control-indicator"></div>', $field_html );
		return $field_html;
	}

	/**
	 * Zet het patroon voor de samenvatting in e-mails toe
	 *
	 * @param string $pattern
	 * @return string
	 */
	public function set_summary_magic_pattern( $pattern ) {
		$pattern = '<tr>
			<td width="35%%" style="font-family: Verdana, normal; color:' . SIW_Properties::get('font_color') . '; font-size:0.8em;">%s</td>
			<td width="5%%"></td>
			<td width="50%%" style="font-family: Verdana, normal; color:' . SIW_Properties::get('font_color') . '; font-size:0.8em; font-style:italic">%s</td>
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
	 * @return void
	 * 
	 * @uses siw_get_regex()
	 */
	public function set_validation_field_attributes( $attrs, $field ) {
		if ( 'geboortedatum' === $field['ID'] ) {
			$attrs[ 'data-parsley-pattern-message' ] = __( 'Dit is geen geldige datum.', 'siw' );
			$attrs[ 'data-parsley-pattern' ] = siw_get_regex( 'date' );
		}
	
		if ( 'postcode' === $field['ID'] ) {
			$attrs[ 'data-parsley-pattern-message' ] = __( 'Dit is geen geldige postcode.', 'siw' );
			$attrs[ 'data-parsley-pattern' ] = siw_get_regex( 'postal_code' );
		}
	
		return $attrs;
	}
}





