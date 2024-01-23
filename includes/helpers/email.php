<?php declare(strict_types=1);

namespace SIW\Helpers;

class Email {

	public const TEXT_HTML = 'text/html';
	public const TEXT_PLAIN = 'text/plain';

	protected array $to = [];
	protected array $headers = [];
	protected string $subject;
	protected string $message;
	protected array $attachments = [];

	protected function __construct() {}

	public static function create(): self {
		$self = new self();
		return $self;
	}

	public function set_subject( string $subject ): self {
		$this->subject = $subject;
		return $this;
	}

	public function set_message( string $message ): self {
		$this->message = $message;
		return $this;
	}

	public function add_recipient( string $email, string $name = null ): self {
		$this->to[] = $this->format_email( $email, $name );
		return $this;
	}

	public function set_from( string $email, string $name = null ): self {
		$this->headers[] = $this->format_header( 'From', $this->format_email( $email, $name ) );
		return $this;
	}

	public function add_cc( string $email, string $name = null ) {
		$this->headers[] = $this->format_header( 'Cc', $this->format_email( $email, $name ) );
		return $this;
	}

	public function add_bcc( string $email, string $name = null ): self {
		$this->headers[] = $this->format_header( 'Bcc', $this->format_email( $email, $name ) );
		return $this;
	}

	public function set_reply_to( string $email, string $name = null ): self {
		$this->headers[] = $this->format_header( 'Reply-To', $this->format_email( $email, $name ) );
		return $this;
	}

	public function set_content_type( string $content_type ): self {
		$this->headers[] = $this->format_header( 'Content-Type', $content_type );
		return $this;
	}

	public function add_attachment( string $path ): self {
		$this->attachments[] = $path;
		return $this;
	}

	public function send(): bool {
		return wp_mail(
			$this->to,
			$this->subject,
			$this->message,
			$this->headers,
			$this->attachments
		);
	}

	protected function format_email( string $email, string $name = null ): string {
		return is_null( $name ) ? $email : "{$name} <{$email}>";
	}

	protected function format_header( string $header, string $value ): string {
		return "{$header}: {$value}";
	}
}
