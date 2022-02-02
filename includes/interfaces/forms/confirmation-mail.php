<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

/**
 * Interface voor formulier via MetaBox
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Confirmation_Mail {

	/** Onderwerp van bevestigingsmail */
	public function get_confirmation_mail_subject(): string;

	/** Inhoud van bevestigingsmail */
	public function get_confirmation_mail_message(): string;
}
