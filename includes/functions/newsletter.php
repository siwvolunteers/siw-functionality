<?php declare(strict_types=1);

use SIW\External\Mailjet;

/**
 * Functies m.b.t. nieuwsbrief
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 *
 * @todo evt class met static functions van maken
 */

/** Geeft lijst met nieuwsbrieven terug */
function siw_newsletter_get_lists( bool $include_subscriber_count = true ): array {
	$mailjet = new Mailjet();
	$mailjet_lists = $mailjet->get_lists();

	$lists = [];
	foreach ( $mailjet_lists as $list ) {
		$lists[ $list['id'] ] = $include_subscriber_count ? "{$list['name']} ({$list['subscriber_count']})" : $list['name'];
	}

	return $lists;
}

/** Abonnee aanmelden voor nieuwsbrief */
function siw_newsletter_subscribe( string $email, int $list_id, array $properties = [] ): bool {
	$mailjet = new Mailjet();
	return $mailjet->subscribe_user(
		$email,
		$list_id,
		$properties
	);
}
