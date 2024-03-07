<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Plato\Database\Free_Places\Table as Free_Places_Table;
use SIW\Plato\Database\Partners\Table as Partners_Table;
use SIW\Plato\Database\Projects\Table as Projects_Table;

class Database_Tables extends Base {

	#[Add_Action( 'init' )]
	public function register_tables(): void {
		// ( new Free_Places_Table() )->uninstall();
		// ( new Partners_Table() )->uninstall();
		// ( new Projects_Table() )->uninstall();

		new Free_Places_Table();
		new Partners_Table();
		new Projects_Table();
	}
}
