<?php declare(strict_types=1);

use SIW\External\Mailjet;

/**
 * Functies m.b.t. nieuwsbrief
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 *
 * @todo evt class met static functions van maken
 */

/** Abonnee aanmelden voor nieuwsbrief */
function siw_newsletter_subscribe( string $email, int $list_id, array $properties = [] ): bool {
	$mailjet = new Mailjet();
	return $mailjet->subscribe_user(
		$email,
		$list_id,
		$properties
	);
}
