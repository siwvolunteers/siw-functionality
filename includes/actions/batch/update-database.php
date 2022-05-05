<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Database_Table;
use SIW\Helpers\Database;
use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;

/**
 * Proces om database te updaten
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Update_Database implements Batch_Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'update_database';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Database updaten', 'siw' );
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function select_data(): array {
		$tables = [
			Database_Table::PLATO_PROJECTS(),
			Database_Table::PLATO_PROJECT_FREE_PLACES(),
			Database_Table::PLATO_PROJECT_IMAGES(),
		];
		return array_map(
			fn( Database_Table $table ) : string => $table->value,
			$tables
		);
	}

	/** {@inheritDoc} */
	public function process( $item ) {
		$table = Database_Table::from( $item );
		$database = new Database( $table );
		if ( ! $database->create_table() ) {
			return false;
		};

		// TODO: verplaatsen naar tabel-definitie + methode create_table() uitbreiden
		if ( $table->equals( Database_Table::PLATO_PROJECT_IMAGES() ) ) {
			$database->add_foreign_key( Database_Table::PLATO_PROJECTS(), [ 'project_id' ], [ 'project_id' ] );
		}
	}
}
