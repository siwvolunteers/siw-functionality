<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

/**
 * Interface voor definiëren van een formulier
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Form {

	/** Volledige breedte */
	const FULL_WIDTH = 100;

	/** Halve breedte */
	const HALF_WIDTH = 50;

	/** Geeft ID van Form terug */
	public function get_id() : string;

	/** Geeft naam van formulier terug */
	public function get_name() : string;
	
	/** Geeft velden van formulier terug */
	public function get_fields() : array;

	/** Geeft onderwerp van notificatiemail terug */
	public function get_notification_subject() : string;

	/** Geeft inhoud van notificatiemail terug */
	public function get_notification_message() : string;

	/** Geeft onderwerp van bevestigingsmail terug */
	public function get_autoresponder_subject() : string;

	/** Geeft inhoud van bevestigingsmail terug */
	public function get_autoresponder_message() : string;
}