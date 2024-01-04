<?php declare(strict_types=1);

namespace SIW;

use SIW\Attributes\Add_Action;
use SIW\Plato\Database\Free_Places\Table as Free_Places_Table;
use SIW\Plato\Database\Partners\Table as Partners_Table;
use SIW\Plato\Database\Projects\Table as Projects_Table;

/**
 * Configuratie van database tabellen
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Database_Tables extends Base {

	#[Add_Action( 'init' )]
	public function register_tables(): void {
		new Free_Places_Table();
		new Partners_Table();
		new Projects_Table();
	}
}
