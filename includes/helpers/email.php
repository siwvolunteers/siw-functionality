<?php declare(strict_types=1);

namespace SIW\Helpers;

/**
 * Class om een e-mail te versturen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Email {

	/** Plaintext-email */
	const TEXT_HTML = 'text/html';

	/** HTML-email */
	const TEXT_PLAIN = 'text/plain';

	/** Ontvanger */
	protected array $to = [];

	/** Headers */
	protected array $headers = [];

	/** Onderwerp */
	protected string $subject;

	/** Inhoud */
	protected string $message;

	/** Bijlages */
	protected array $attachments = [];

	/** Constructor */
	protected function __construct() {}

	/** Creëer email */
	public static function create( string $subject, string $message, string $recipient_email, string $recipient_name = null ) : self {
		$self = new self();
		$self->subject = $subject;
		$self->message = $message;
		$self->to[] = $self->format_email( $recipient_email, $recipient_name );
		return $self;
	}

	/** Voeg ontvanger toe */
	public function add_recipient( string $email, string $name = null ) : self {
		$this->to[] = $this->format_email( $email, $name );
		return $this;
	}

	/** Zet afzender */
	public function set_from( string $email, string $name = null ) : self {
		$this->headers[] = $this->format_header( 'From', $this->format_email( $email, $name ) );
		return $this;
	}

	/** Voeg cc toe */
	public function add_cc( string $email, string $name = null ) {
		$this->headers[] = $this->format_header( 'Cc', $this->format_email( $email, $name ) );
		return $this;
	}

	/** Voeg bcc toe */
	public function add_bcc( string $email, string $name = null ) : self {
		$this->headers[] = $this->format_header( 'Bcc', $this->format_email( $email, $name ) );
		return $this;
	}

	/** Zet content type */
	public function set_content_type( string $content_type ) : self {
		$this->headers[] = $this->format_header( 'Content-Type', $content_type );
		return $this;
	}

	/** Voeg attachment toe */
	public function add_attachment( string $path ) : self {
		$this->attachments[] = $path;
		return $this;
	}

	/** Email verzenden */
	public function send() : bool {
		return wp_mail(
			$this->to,
			$this->subject,
			$this->message,
			$this->headers,
			$this->attachments
		);
	}

	/** Formatteert email */
	protected function format_email( string $email, string $name = null ) : string {
		return is_null( $name ) ? $email : "{$name} <{$email}>";
	}

	/** Formatteert header */
	protected function format_header( string $header, string $value ) : string {
		return "{$header}: {$value}";
	}
}