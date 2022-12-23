<?php declare(strict_types=1);

namespace SIW\Interfaces\Forms;

/**
 * Interface voor exporteren van formulieraanmelding naar MailJet
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
interface Export_To_Mailjet {

	/** Geeft Mailjet lijst id terug: */
	public function get_mailjet_list_id( \WP_REST_Request $request ): ?int;

	/** Geeft properties voor Mailjet terug */
	public function get_mailjet_properties( \WP_REST_Request $request ): array;
}
