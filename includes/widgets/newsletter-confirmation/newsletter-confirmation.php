<?php declare(strict_types=1);

namespace SIW\Widgets;

/**
 * Widget met 
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @widget_data
 * Widget Name: SIW: Nieuwsbrief - bevestiging aanmelding
 * Description: TODO
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Newsletter_Confirmation extends Widget {

	//Constantes voor query args
	CONST QUERY_ARG_EMAIL = 'nl_email';
	CONST QUERY_ARG_EMAIL_HASH = 'nl_email_hash';
	CONST QUERY_ARG_FIRST_NAME = 'nl_first_name';
	CONST QUERY_ARG_FIRST_NAME_HASH = 'nl_first_name_hash';

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'newsletter_confirmation';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Nieuwsbrief - bevestiging', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont bevestiging van nieuwsbrief', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'rss';
	}

	/** {@inheritDoc} */
	public function get_widget_form() {
		$widget_form = [
			'title' => [
				'type'    => 'text',
				'label'   => __( 'Titel', 'siw' ),
			],
		];
		return $widget_form;
	}

	/** {@inheritDoc} */
	function get_template_variables( $instance, $args ) {
		return [
			'content' => $this->process_confirmation(),
		];
	}

	/** Verwerk aanmelding voor de nieuwsbrief */
	protected function process_confirmation(): string {
		
		// Haal parameters van request op
		$email_raw = get_query_arg( self::QUERY_ARG_EMAIL ) ?? '';
		$email_hash_raw = get_query_arg( self::QUERY_ARG_EMAIL_HASH ) ?? '';

		$first_name_raw = get_query_arg( self::QUERY_ARG_FIRST_NAME ) ?? '';
		$first_name_hash_raw = get_query_arg( self::QUERY_ARG_FIRST_NAME_HASH ) ?? '';

		// Check of alle parameters gevuld zijn
		if ( empty( $email_raw ) || empty( $email_hash_raw ) || empty( $first_name_raw ) || empty( $first_name_hash_raw ) ) {
			return __( 'Helaas is er iets misgegaan met de aanmelding.', 'siw' );
		}

		// Decode de parameters
		$email = base64_decode( urldecode( $email_raw ) );
		$email_hash = urldecode( $email_hash_raw );
		$first_name = base64_decode( urldecode( $first_name_raw ) );
		$first_name_hash = urldecode( $first_name_hash_raw );

		// Check of hashes correct zijn
		if ( ! hash_equals( siw_hash( $email ), $email_hash ) || ! hash_equals( siw_hash( $first_name ), $first_name_hash ) ) {
			return __( 'Helaas is er iets misgegaan met de aanmelding.', 'siw' );
		}

		//Afbreken als aanmelding al gedaan is TODO:: transient verplaatsen naar Mailjet-class?
		if ( get_transient( "siw_newsletter_confirm_{$email_hash}" ) ) {
			return __( 'Je bent al aangemeld voor de SIW-nieuwsbrief.', 'siw' );
		}

		$properties = [
			'firstname' => $first_name,
		];

		if ( siw_newsletter_subscribe( $email, (int) siw_get_option( 'newsletter_list' ), $properties ) ) {
			
			//Transient zetten zodat aanmelding niet nog een keer verwerkt wordt bij opnieuw bezoeken pagina
			set_transient( "siw_newsletter_confirm_{$email_hash}", true, DAY_IN_SECONDS );
			return  __( 'Gefeliciteerd! Je bent nu aangemeld voor de SIW-nieuwsbrief.', 'siw' );
		}
		return __( 'Helaas is er iets misgegaan met de aanmelding.', 'siw' );
	}
}
