<?php declare(strict_types=1);

namespace SIW\Helpers;

use SIW\Data\Database_Table;
use SIW\Util\Logger;

class Database {

	protected \wpdb $wpdb;
	protected string $table;
	protected array $columns;

	public function __construct( Database_Table $table ) {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table = $this->get_full_table_name( $table->value );
		$this->columns = siw_get_data( "database/{$table->value}" );
	}

	public function get_columns(): array {
		return $this->columns;
	}

	public function truncate(): bool {
		$result = (bool) $this->wpdb->query( "TRUNCATE TABLE {$this->table}" );
		if ( false === $result ) {
			Logger::error( $this->wpdb->last_error, static::class );
		}
		return $result;
	}

	public function insert( array $data ): bool {

		// Alleen data van bestaande kolommen gebruiken
		$data = wp_array_slice_assoc( $data, wp_list_pluck( $this->columns, 'name' ) );

		$column_types = $this->get_column_data_types();

		$values = [];
		$format = [];
		foreach ( $data as $column => $value ) {
			$values[ $column ] = $this->typecast_value( $value, $column_types[ $column ] );
			$format[] = $this->type_to_placeholder( $column_types[ $column ] );
		}

		$result = (bool) $this->wpdb->insert( $this->table, $values, $format );
		$this->maybe_log_error();
		return $result;
	}

	public function get_row( array $where ): ?array {

		// Where clause opbouwen
		foreach ( $where as $field => $value ) {
			if ( ! in_array( $field, wp_list_pluck( $this->columns, 'name' ), true ) ) {
				continue;
			}

			if ( is_null( $value ) ) {
				$conditions[] = "`$field` IS NULL";
				continue;
			}
			$conditions[] = "`$field` = %s"; // TODO: juiste placeholder gebruiken
			$values[]     = $value;
		}
		$where_clause = implode( ' AND ', $conditions );

		// Query uitvoeren
		$data = $this->wpdb->get_row(
			$this->wpdb->prepare(
				"SELECT * FROM {$this->table} WHERE $where_clause", // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
				$values
			),
			ARRAY_A
		);
		if ( null === $data ) {
			$this->maybe_log_error();
			return null;
		}

		// Data casten naar juiste datatype
		$data_types = $this->get_column_data_types();
		array_walk(
			$data,
			fn( &$value, $key, $data_types ) => $value = $this->typecast_value( $value, $data_types[ $key ] ), // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
			$data_types
		);
		return $data;
	}

	public function get_col( string $col, array $where = [] ): array {
		if ( ! in_array( $col, wp_list_pluck( $this->columns, 'name' ), true ) ) {
			return [];
		}

		// Where clause opbouwen TODO: losse functie
		foreach ( $where as $field => $value ) {
			if ( ! in_array( $field, wp_list_pluck( $this->columns, 'name' ), true ) ) {
				continue;
			}

			if ( is_null( $value ) ) {
				$conditions[] = "`$field` IS NULL";
				continue;
			}
			$conditions[] = "`$field` = %s"; // TODO: juiste placeholder gebruiken
			$values[]     = $value;
		}

		if ( ! empty( $where ) ) {
			$where_clause = implode( ' AND ', $conditions );
		} else {
			$where_clause = '1 = %d';
			$values[] = 1;
		}

		$data = $this->wpdb->get_col(
			$this->wpdb->prepare(
				"SELECT {$col} FROM {$this->table} WHERE $where_clause", // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
				$values
			)
		);

		// TODO: typecasten
		return $data;
	}

	public function delete( array $where ) {
		return (bool) $this->wpdb->delete( $this->table, $where );
	}

	public function create_table(): bool {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$columns = $this->columns;

		// Primary key bepalen TODO: aparte functie
		$primary_key_columns = wp_list_filter( $columns, [ 'primary_key' => true ] );
		$primay_key_names = wp_list_pluck( $primary_key_columns, 'name' );

		// Query opbouwen
		$sql[] = sprintf( 'CREATE TABLE `%s` (', $this->table );
		foreach ( $columns as $column ) {
			$sql[] = sprintf(
				'`%s` %s %s,',
				$column['name'],
				isset( $column['length'] ) ? "{$column['type']}({$column['length']})" : $column['type'],
				( isset( $column['nullable'] ) && $column['nullable'] ) ? '' : 'NOT NULL'
			);
		}
		$sql[] = sprintf( 'PRIMARY KEY (%s)', implode( ',', $primay_key_names ) );
		$sql[] = sprintf( ') %s;', $this->wpdb->get_charset_collate() );

		dbDelta( implode( PHP_EOL, $sql ) );

		$result = empty( $this->wpdb->last_error );
		$this->maybe_log_error();
		return $result;
	}

	public function add_foreign_key( Database_Table $referenced_table, array $referenced_fields, array $fields ): bool {
		// TODO: parameter voor on delete/ on update
		// TODO: checks op velden en tabel
		$referenced_table_full_name = $this->get_full_table_name( $referenced_table->value );

		// Eerst kijken of de foreign key al bestaat. Zo ja, dan afbreken
		$check_query = [];
		$check_query[] = 'SELECT CONSTRAINT_NAME';
		$check_query[] = 'FROM information_schema.TABLE_CONSTRAINTS';
		$check_query[] = sprintf( "WHERE CONSTRAINT_SCHEMA = '%s'", $this->wpdb->dbname );
		$check_query[] = sprintf( "AND CONSTRAINT_NAME = 'fk_%s'", $referenced_table_full_name );
		$check_query[] = "AND CONSTRAINT_TYPE = 'FOREIGN KEY'";
		$check_query[] = sprintf( "AND TABLE_NAME = '%s'", $this->table );

		if ( 0 !== $this->wpdb->query( implode( PHP_EOL, $check_query ) ) ) {
			return false;
		}

		$fk_query = [];
		$fk_query[] = sprintf( 'ALTER TABLE `%s`', $this->table );
		$fk_query[] = sprintf( 'ADD CONSTRAINT `fk_%s`', $referenced_table_full_name );
		$fk_query[] = sprintf( 'FOREIGN KEY (%s)', implode( ',', $fields ) );
		$fk_query[] = sprintf( 'REFERENCES `%s` (%s)', $referenced_table_full_name, implode( ',', $referenced_fields ) );
		$fk_query[] = 'ON DELETE CASCADE;';

		$result = (bool) $this->wpdb->query( implode( PHP_EOL, $fk_query ) );
		$this->maybe_log_error();
		return $result;
	}

	protected function typecast_value( $value, string $type ): mixed {
		$value = match ( $type ) {
			'CHAR',
			'VARCHAR',
			'TEXT',
			'DATE'    => trim( strval( $value ) ),
			'BOOL'    => boolval( $value ),
			'FLOAT'   => floatval( $value ),
			'INT',
			'TINYINT' => intval( $value ),
			default   => trim( strval( $value ) ),
		};
		return $value;
	}

	protected function type_to_placeholder( string $type ): string {

		$placeholder = match ( $type ) {
			'CHAR',
			'VARCHAR',
			'TEXT',
			'DATE'  => '%s',
			'BOOL'  => '%d',
			'FLOAT' => '%f',
			'INT',
			'TINYINT' => '%d',
			default   => '%s',
		};

		return $placeholder;
	}

	protected function get_full_table_name( string $table_name ): string {
		return $this->wpdb->prefix . 'siw_' . $table_name;
	}

	protected function get_column_data_types(): array {
		return wp_list_pluck( $this->columns, 'type', 'name' );
	}

	public function get_row_count(): ?int {
		$sql = "SELECT COUNT(1) FROM $this->table";
		return (int) $this->wpdb->get_var( $sql );
	}

	public function get_rows( array $args ): ?array {
		$defaults = [
			'orderby'        => null,
			'order'          => null,
			'search'         => null,
			'search_columns' => [],
			'page'           => 1,
			'per_page'       => null,
			'output'         => OBJECT,
		];
		$args = wp_parse_args( $args, $defaults );

		// Array met waarden voor wpdb->prepare
		$values = [];

		// TODO: where clause genereren (net als in https://developer.wordpress.org/reference/classes/wp_meta_query/) en search verplaatsen naar database list -table

		// Zoek in de opgegeven kolommen
		if ( null !== $args['search'] && ! empty( $args['search_columns'] ) ) {
			$search = $args['search'];

			foreach ( $args['search_columns'] as $column ) {
				$search_conditions[] = "`$column` LIKE '%s'";
				$values[] = '%' . $this->wpdb->esc_like( $search ) . '%';

			}
			$conditions[] = '(' . implode( ' OR ', $search_conditions ) . ')';
		}

		// start de query
		$query[] = 'SELECT * FROM ' . $this->table;

		// Voeg wehere clause toe
		if ( isset( $conditions ) ) {
			$query[] = 'WHERE ' . implode( ' AND ', $conditions );

		}

		// Voeg sortering toe
		if ( ! empty( $args['orderby'] ) ) {
			$query[] = 'ORDER BY ' . $args['orderby'];
			if ( ! empty( $args['order'] ) ) {
				$query[] = ' ' . strtoupper( $args['order'] ) === 'ASC' ? 'ASC' : 'DESC';
			}
		}

		// Voeg limit en offset toe
		if ( ! empty( $args['per_page'] ) ) {
			$query[] = 'LIMIT %d, %d';
			$values[] = ( $args['page'] - 1 ) * $args['per_page'];
			$values[] = $args['per_page'];
		}

		$query = $this->wpdb->prepare( implode( PHP_EOL, $query ), $values );
		return $this->wpdb->get_results( $query, $args['output'] );
	}

	protected function maybe_log_error(): void {
		if ( ! empty( $this->wpdb->last_error ) ) {
			Logger::error( $this->wpdb->last_error, static::class );
		}
	}
}
