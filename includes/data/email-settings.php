<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Bevat email instellingen
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Email_Settings extends Data {

	/** Afzender van bevestigingsmail */
	protected string $confirmation_mail_sender;

	/** Ontvanger */
	protected string $notification_mail_recipient;

	/** CC van notificatiemail */
	protected array $notification_mail_cc = [];

	/** Geeft afzender van bevestigingsmail terug */
	public function get_confirmation_mail_sender(): string {
		return $this->add_domain( $this->confirmation_mail_sender );
	}

	/** Geeft ontvanger van notificatiemail terug */
	public function get_notification_mail_recipient(): string {
		return $this->add_domain( $this->notification_mail_recipient );
	}

	/** Geeft cc voor notificatiemail terug */
	public function get_notification_mail_cc(): array {
		return array_map( [ $this, 'add_domain' ], $this->notification_mail_cc );
	}

	/** Voegt domein @siw.nl toe aan emailadres */
	protected function add_domain( string $local_part ): string {
		return $local_part . '@siw.nl';
	}

}
