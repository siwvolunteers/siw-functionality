<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Database_Table;
use SIW\Helpers\Database;
use SIW\Jobs\Update_Job;

class Update_Database extends Update_Job {
	private const ACTION_HOOK = self::class;

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Database updaten', 'siw' );
	}

	/** {@inheritDoc} */
	public function start(): void {
		$database_tables = array_map(
			fn( Database_Table $table ): string => $table->value,
			Database_Table::cases()
		);
		$this->enqueue_items( $database_tables, self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function update_table( $table_name ) {
		$table = Database_Table::from( $table_name );
		$database = new Database( $table );
		if ( ! $database->create_table() ) {
			return false;
		}

		// TODO: verplaatsen naar tabel-definitie + methode create_table() uitbreiden
		if ( Database_Table::PLATO_PROJECT_IMAGES === $table ) {
			$database->add_foreign_key( Database_Table::PLATO_PROJECTS, [ 'project_id' ], [ 'project_id' ] );
		}
	}
}
