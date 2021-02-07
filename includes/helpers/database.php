<?php declare(strict_types=1);

namespace SIW\Helpers;

use SIW\Database_Table;
use wpdb;

/**
 * Database helper voor SIW-tabellen
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Database {

	/** DB-connectie */
	protected wpdb $wpdb;

	/** Tabelnaam (inclusief prefix) */
	protected string $table;

	/** Informatie over kolommen */
	protected array $columns;

	/** Init */
	public function __construct( Database_Table $table ) {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table = $this->wpdb->prefix . 'siw_'. $table->value;
		$this->columns = siw_get_data( "database/{$table->value}" );
	}

	/** Geeft informatie over kolommen terug */
	public function get_columns() : array {
		return $this->columns;
	}

	/** Truncate tabel */
	public function truncate() : bool {
		return (bool) $this->wpdb->query( "TRUNCATE TABLE {$this->table}");
	}
	
	/** Insert data */
	public function insert( array $data ) : bool {

		//Alleen data van bestaande kolommen gebruiken
		$data = wp_array_slice_assoc( $data, wp_list_pluck( $this->columns, 'name' ) );
		
		$column_types = wp_list_pluck( $this->columns, 'type', 'name' );

		$values = [];
		$format = [];
		foreach ( $data as $column => $value ) {
			$values[ $column ] = $this->typecast_value( $value, $column_types[ $column ] );
			$format[] = $this->type_to_placeholder( $column_types[ $column ] );
		}
		return (bool) $this->wpdb->insert( $this->table, $values, $format );
	}

	/** Haal rij uit database (o.b.v. where-array met `column => value` ) */
	public function get_row( array $where ) : ?array {

		//Where clause opbouwen
		foreach ( $where as $field => $value ) {
			if ( ! in_array( $field, wp_list_pluck( $this->columns, 'name' ) ) ) {
				continue;
			}

			if ( is_null( $value['value'] ) ) {
				$conditions[] = "`$field` IS NULL";
				continue;
			}
			$conditions[] = "`$field` = %s"; //TODO: juiste placeholder gebruiken
			$values[]     = $value;
		}
		$where_clause = implode( ' AND ', $conditions );

		//Query uitvoeren
		$data = $this->wpdb->get_row(
			$this->wpdb->prepare(
				"SELECT * FROM {$this->table} WHERE $where_clause",
				$values
			),
			ARRAY_A
		);
		if ( null == $data ) {
			return null;
		}

		// Data casten naar juiste datatype
		$data_types = wp_list_pluck( $this->columns, 'type', 'name' );
		array_walk(
			$data,
			fn( &$value, $key, $data_types ) => $value = $this->typecast_value( $value, $data_types[ $key ] ),
			$data_types
		);
		return $data;
	}

	/** Verwijder data */
	public function delete( array $where ) {
		return (bool) $this->wpdb->delete( $this->table, $where );
	}

	/** Creëer tabel */
	public function create_table() : bool {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$columns = $this->columns;

		//Primary key bepalen TODO: aparte functie
		$primary_key_columns = wp_list_filter( $columns, [ 'primary_key' => true ] );
		$primay_key_names = wp_list_pluck( $primary_key_columns, 'name' );

		//Query opbouwen
		$sql[] = sprintf('CREATE TABLE `%s` (', $this->table );
		foreach ( $columns as $column ) {
			$sql[] = sprintf('`%s` %s %s,',
				$column['name'],
				isset( $column['length'] ) ? "{$column['type']}({$column['length']})" : $column['type'],
				( isset( $column['nullable'] ) && $column['nullable'] )  ? '' : 'NOT NULL'
			);
		}
		$sql[] = sprintf( 'PRIMARY KEY (%s)', implode( ',', $primay_key_names ) );
		$sql[] = sprintf( ') %s;', $this->wpdb->get_charset_collate() );

		dbDelta( implode( PHP_EOL, $sql ) );
		return empty( $this->wpdb->last_error );
	}

	/** Typecase waarde o.b.v. type */
	protected function typecast_value( $value, string $type ) {
		switch ( $type ) {
			case 'CHAR': 
			case 'VARCHAR':
			case 'TEXT':
			case 'DATE':
				$value = strval( $value );
				break;
			case 'BOOL':
				$value = boolval( $value );
				break;
			case 'FLOAT':
				$value = floatval( $value );
				break;
			case 'INT':
			case 'TINYINT':
				$value = intval( $value );
				break;
			default:
				$value = strval( $value );
		}
		return $value;
	}
	
	/** Zet mysql type om naar placeholder voor wpdb->prepare */
	protected function type_to_placeholder( string $type ) {
		switch ( $type ) {
			case 'CHAR': 
			case 'VARCHAR':
			case 'TEXT':
			case 'DATE':
				$placeholder = '%s';
				break;
			case 'BOOL':
				$placeholder = '%d';
				break;
			case 'FLOAT':
				$placeholder = '%f';
				break;
			case 'INT':
			case 'TINYINT':
				$placeholder = '%d';
				break;
			default:
			$placeholder = '%s';
		}
		return $placeholder;
	}
}
