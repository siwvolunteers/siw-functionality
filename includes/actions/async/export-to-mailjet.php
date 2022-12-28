<?php declare(strict_types=1);

namespace SIW\Actions\Async;

use SIW\Integrations\Mailjet;
use SIW\Interfaces\Actions\Async as I_Async_Action;

/**
 * Exporteren contactpersoon naar MailJet
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Export_To_Mailjet implements I_Async_Action {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'export_to_mailjet';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Exporteren naar Mailjet', 'siw' );
	}

	/** {@inheritDoc} */
	public function process( string $email = '', int $list_id = null, array $properties = [] ) {
		$mailjet = Mailjet::create();
		$mailjet->subscribe_user( $email, $list_id, $properties );
	}

	/** {@inheritDoc} */
	public function get_argument_count(): int {
		return 3;
	}

}
