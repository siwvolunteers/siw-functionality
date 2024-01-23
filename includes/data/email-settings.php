<?php declare(strict_types=1);

namespace SIW\Data;

class Email_Settings extends Data {

	protected string $confirmation_mail_sender;
	protected string $notification_mail_recipient;

	public function get_confirmation_mail_sender(): string {
		return $this->add_domain( $this->confirmation_mail_sender );
	}

	public function get_notification_mail_recipient(): string {
		return $this->add_domain( $this->notification_mail_recipient );
	}

	protected function add_domain( string $local_part ): string {
		return $local_part . '@siw.nl';
	}
}
