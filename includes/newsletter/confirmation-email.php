<?php declare(strict_types=1);

namespace SIW\Newsletter;

use SIW\Email\Template;
use SIW\Properties;
use SIW\Newsletter\Hash;
use SIW\Util\Links;

/**
 * Bevestigingsmail voor aanmelding nieuwsbrief
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Confirmation_Email {

	/**
	 * E-mailadres
	 *
	 * @var string
	 */
	protected $email;

	/**
	 * ID van maillijst
	 *
	 * @var int
	 */
	protected $list_id;

	/**
	 * Properties voor Mailjet
	 *
	 * @var array
	 */
	protected $properties;

	/**
	 * E-mail instellingen
	 *
	 * @var array
	 */
	protected $email_settings;
	
	/**
	 * Init
	 *
	 * @param string $email
	 * @param int $list_id
	 * @param array $properties
	 */
	public function __construct( string $email, int $list_id, array $properties ) {
		$this->email = $email;
		$this->list_id = $list_id;

		//TODO: is dit echt nodig?
		$properties = wp_parse_args(
			$properties,
			[
				'firstname' => '',
				'lastname'  => ''
			]
		);
		$this->properties = $properties;
		$this->email_settings = siw_get_email_settings( 'newsletter' );
	}

	/**
	 * Verstuurt bevestigingsmail
	 */
	public function send() : bool {
		$email_hash = sha1( $this->email );

		// Afbreken als bevestigingsmail al verstuurd is
		if ( get_transient( "siw_newsletter_email_{$email_hash}" ) ) {
			return false;
		}

		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			sprintf( 'From: %s <%s>', Properties::NAME, $this->email_settings['email'] ),
		];

		$result = wp_mail(
			$this->email,
			__( 'Bevestig je aanmelding voor onze nieuwsbrief', 'siw' ),
			$this->generate_message(),
			$headers
		);

		set_transient( "siw_newsletter_email_{$email_hash}", true, HOUR_IN_SECONDS );
		return $result;
	}

	/**
	 * Genereert mailtekst
	 *
	 * @return string
	 */
	protected function generate_message() : string {
		$template_args = [
			'subject' => __( 'Aanmelding nieuwsbrief', 'siw' ),
			'message' => implode(
				'',
				[
					sprintf( __('Beste %s,', 'siw' ), $this->properties['firstname'] ). BR2,
					__( 'Bedankt voor je aanmelding voor de SIW-nieuwsbrief!', 'siw' ) . SPACE,
					__( 'Om zeker te weten dat je inschrijving correct is, vragen we je je aanmelding te bevestigen.', 'siw' ) . BR2,
					Links::generate_link(
						$this->generate_confirmation_url(),
						__( 'Klik hier om je aanmelding voor onze nieuwsbrief direct te bevestigen.', 'siw' )
					) . BR2,
					sprintf( __( 'Tip: voeg %s toe aan je adresboek.', 'siw' ), $this->email_settings['email'] ) . SPACE,
					__( 'Zo mis je nooit meer nieuws over onze infodagen, ervaringsverhalen of projecten.', 'siw' ),
				]
			),
			'show_signature'  => true,
			'signature_name'  => $this->email_settings['name'],
			'signature_title' => $this->email_settings['title'],
		];

		$template = new Template( $template_args );
		return $template->generate();
	}

	/**
	 * Genereert url voor bevestingslink
	 *
	 * @return string
	 */
	protected function generate_confirmation_url() : string {

		$data = [
			'email'      => $this->email,
			'list_id'    => $this->list_id,
			'properties' => $this->properties,
		];
		$json_data = json_encode( $data );

		return add_query_arg(
			[
				'nl_confirmation' => true,
				'nl_data'         => urlencode( base64_encode( $json_data ) ),
				'nl_hash'         => urlencode( Hash::generate_hash( $json_data ) ),
			],
			SIW_SITE_URL
		);
	}
}
