<?php declare(strict_types=1);

use SIW\External\Mailjet;
use SIW\Newsletter\Confirmation_Email;

/**
 * Functies m.b.t. nieuwsbrief
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo evt class met static functions van maken
 */

/** Geeft lijst met nieuwsbrieven terug */
function siw_newsletter_get_lists( bool $include_subscriber_count = true ) : array {
	$mailjet = new Mailjet;
	$mailjet_lists = $mailjet->get_lists();
	
	$lists = [];
	foreach ( $mailjet_lists as $list ) {
		$lists[ $list['id'] ] = $include_subscriber_count ? "{$list['name']} ({$list['subscriber_count']})" : $list['name'];
	}

	return $lists;
}

/** Geeft aantal abonnees van lijst terug */
function siw_newsletter_get_subscriber_count( string $list_id ) : int {
	$mailjet = new Mailjet;
	$list = $mailjet->get_list( $list_id );
	return $list['subscriber_count'] ?? 0;
}

/** Abonnee aanmelden voor nieuwsbrief */
function siw_newsletter_subscribe( string $email, int $list_id, array $properties = [] ) : bool {
	$mailjet = new Mailjet;
	return $mailjet->subscribe_user(
		$email,
		$list_id,
		$properties
	);
}

/** Verstuur bevestigingsmail */
function siw_newsletter_send_confirmation_email( string $email, int $list_id, array $properties = [] ) : bool {
	$confirmation_mail = new Confirmation_Email( $email, $list_id, $properties, $properties );
	return $confirmation_mail->send();
}
