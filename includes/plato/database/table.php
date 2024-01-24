<?php declare(strict_types=1);

namespace SIW\Plato\Database;

use BerlinDB\Database\Column;
use BerlinDB\Database\Query;

abstract class Table extends \BerlinDB\Database\Table {

	public $name = '';
	public $description = '';

	abstract public function get_schema(): Schema;
	abstract public function get_query(): Query;
	abstract public function get_view_columns(): array;

	protected function set_schema() {
		$columns = array_map(
			fn( Column $column ): string => $this->get_column_string( $column ),
			$this->get_schema()->get_columns()
		);

		$columns[] = 'PRIMARY KEY (id)'; //TODO: afleiden van column + UNIQUE keys toevoegen
		$this->schema = implode( ',' . PHP_EOL, $columns );
	}

	protected function get_column_string( Column $column ) {

		$column_string = '';
		if ( ! empty( $column->name ) ) {
			$column_string .= $column->name;
		}
		if ( ! empty( $column->type ) ) {
			$column_string .= " {$column->type}";
		}

		if ( ! empty( $column->length ) ) {
			$column_string .= '(' . $column->length . ')';
		}

		if ( ! empty( $column->unsigned ) ) {
			$column_string .= ' unsigned';
		}

		if ( empty( $column->allow_null ) ) {
			$column_string .= ' NOT NULL ';
		}

		if ( ! empty( $column->default ) ) {
			$column_string .= " default '{$column->default}'";
		}

		if ( ! empty( $column->extra ) ) {
			$column_string .= " {$column->extra}";
		}

		return $column_string;
	}
}
