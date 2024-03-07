<?php declare(strict_types=1);

namespace SIW\Jobs\Async;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Plato\Database\Projects\Query;
use SIW\Plato\Database\Projects\Row;
use SIW\WooCommerce\Import\Product as Import_Product;

class Import_Plato_Project extends Base {

	#[Add_Action( self::class )]
	public function import_project( string $project_id ) {
		$query = new Query();
		/** @var Row */
		$project = $query->get_item_by( 'project_id', $project_id );
		$import = new Import_Product( $project, true );
		$import->process();
	}
}
