<?php declare(strict_types=1);

namespace SIW\Actions;

use SIW\Abstracts\Object_Loader as Object_Loader_Abstract;
use SIW\Actions\Async\Action as Async_Action;
use SIW\Actions\Batch\Action as Batch_Action;
use SIW\Interfaces\Actions\Async as Async_Action_Interface;
use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;

class Loader extends Object_Loader_Abstract {

	/** {@inheritDoc} */
	public function get_classes(): array {
		return [
			Async\Export_Plato_Application::class,
			Async\Export_To_Mailjet::class,
			Async\Import_Plato_Project::class,
			Batch\Create_WooCommerce_Taxonomies::class,
			Batch\Delete_Applications::class,
			Batch\Delete_Old_Posts::class,
			Batch\Delete_Stockphotos::class,
			Batch\Import_All_Plato_Projects::class,
			Batch\Import_Plato_Dutch_Projects::class,
			Batch\Import_Plato_Project_Free_Places::class,
			Batch\Import_Plato_Projects::class,
			Batch\Send_Workcamp_Approval_Emails::class,
			Batch\Update_Database::class,
			Batch\Update_Mailjet_Properties::class,
			Batch\Update_Projects::class,
			Batch\Update_WooCommerce_Terms::class,
		];
	}

	/** {@inheritDoc} */
	protected function load( object $action ) {
		if ( is_a( $action, Batch_Action_Interface::class ) ) {
			new Batch_Action( $action );
		} elseif ( is_a( $action, Async_Action_Interface::class ) ) {
			new Async_Action( $action );
		}
	}
}
