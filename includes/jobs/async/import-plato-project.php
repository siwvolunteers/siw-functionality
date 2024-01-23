<?php declare(strict_types=1);

namespace SIW\Jobs\Async;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\WooCommerce\Import\Product as Import_Product;

class Import_Plato_Project extends Base {

	#[Add_Action( self::class )]
	public function import_project( string $project_id ) {
		$plato_project = siw_get_plato_project( $project_id );
		if ( null === $plato_project ) {
			return;
		}
		$import = new Import_Product( $plato_project, true );
		$import->process();
	}
}
