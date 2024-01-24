<?php declare(strict_types=1);

namespace SIW\Jobs\Async;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Integrations\Mailjet;


class Export_To_Mailjet extends Base {
	#[Add_Action( self::class )]
	public function export_contact( string $email = '', int $list_id = null, array $properties = [] ) {
		$mailjet = Mailjet::create();
		$mailjet->subscribe_user( $email, $list_id, $properties );
	}
}
