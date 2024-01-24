<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

interface Confirmation_Mail {

	public function get_confirmation_mail_subject(): string;

	public function get_confirmation_mail_message(): string;
}
