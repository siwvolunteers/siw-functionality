<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

/**
 * Interface voor formulier via MetaBox
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Notification_Mail {

	public function get_notification_mail_subject(): string;

	public function get_notification_mail_message(): string;

}
