<?php declare(strict_types=1);

namespace SIW\Newsletter;

use SIW\Email\Template;
use SIW\Helpers\Email;
use SIW\Properties;
use SIW\Util\Hash;
use SIW\Util\Links;

/**
 * Bevestigingsmail voor aanmelding nieuwsbrief
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Confirmation_Email {

	/**  E-mailadres */
	protected string $email;

	/** ID van maillijst */
	protected int $list_id;

	/** Properties voor Mailjet */
	protected array $properties;

	/** E-mail instellingen */
	protected array $email_settings;
	
	/** Init */
	protected function __construct() {
		$this->email_settings = siw_get_email_settings( 'newsletter' );
	}

	/** Creeert bevestigingsmail */
	public static function create( string $email, int $list_id, array $properties  ) {
		$self = new static();
		$self->email = $email;
		$self->list_id = $list_id;
		$self->properties = $properties;
		return $self;
	}

	/** Verstuurt bevestigingsmail */
	public function send() : bool {
		$email_hash = sha1( $this->email );

		// Afbreken als bevestigingsmail al verstuurd is
		if ( get_transient( "siw_newsletter_email_{$email_hash}" ) ) {
			return false;
		}

		$result = Email::create(
			__( 'Bevestig je aanmelding voor onze nieuwsbrief', 'siw' ),
			$this->generate_message(),
			$this->email,
			$this->properties['firstname']
		)
		->set_from( $this->email_settings['email'], Properties::NAME )
		->set_content_type( Email::TEXT_HTML )
		->send();
		set_transient( "siw_newsletter_email_{$email_hash}", true, HOUR_IN_SECONDS );
		return $result;
	}

	/** Genereert mailtekst */
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

	/** Genereert url voor bevestingslink */
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
