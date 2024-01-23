<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

interface Notification_Mail {

	public function get_notification_mail_subject(): string;

	public function get_notification_mail_message(): string;
}
