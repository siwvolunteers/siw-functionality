<?php declare(strict_types=1);

namespace SIW\Jobs;

use SIW\Base_Loader;

class Loader extends Base_Loader {

	#[\Override]
	protected function get_classes(): array {
		return [
			Async\Export_Plato_Application::class,
			Async\Export_To_Mailjet::class,
			Async\Import_Plato_Project::class,
			Batch\Create_WooCommerce_Taxonomies::class,
			Batch\Delete_Applications::class,
			Batch\Update_Custom_Posts::class,
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
}
