<?php declare(strict_types=1);

namespace SIW\Helpers;

use SIW\Database_Table;

/**
 * Database helper voor SIW-tabellen
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Database {

	/** DB-connectie */
	protected \wpdb $wpdb;

	/** Tabelnaam (inclusief prefix) */
	protected string $table;

	/** Informatie over kolommen */
	protected array $columns;

	/** Init */
	public function __construct( Database_Table $table ) {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table = $this->get_full_table_name( $table->value );
		$this->columns = siw_get_data( "database/{$table->value}" );
	}

	/** Geeft informatie over kolommen terug */
	public function get_columns() : array {
		return $this->columns;
	}
	/**
	 * get_showcolumns
	 * geeft de kolommen terug die getoond moeten worden met de titel die boven de kolom getoond moet worden.
	 *
	 * @return array(kolom,array(kolom,kop)......)
	 */
	public function get_showcolumns() : array {
		$columns = $this->columns;
		$showcolumns = array();
		foreach($columns as $column)
		{
			if(array_key_exists("show",$column))
			{
				$showcolumns=array_merge($showcolumns,[$column['name'] => $column['show']]);
			}
		}
		return($showcolumns);
	}
	/**
	 * get_sortcolumns
	 * kolommen waarop gesorteerd moet kunnen worden
	 *
	 * @return array(kolom,array(kolom,true)......)
	 */
	public function get_sortcolumns() : array {		
		$sort_columns = wp_list_filter( $this->columns, [ 'sort' => true ] );
		$sort_columns = wp_list_pluck(  $sort_columns, 'name');
		$sortcolumns = array();
		foreach($sort_columns as $sort)
		{
			$sortcolumns=array_merge($sortcolumns,[$sort => array ( $sort,TRUE) ]);
		}
		return($sortcolumns);
	}
	/**
	 * get_searchcolumns
	 * kolommen waarin wordt gezocht naar de waarde die is ingevuld in het zoekveld
	 *
	 * @return array(kolom,kolom,.....)
	 */
	public function get_searchcolumns() : array {
		$search_columns = wp_list_filter( $this->columns, [ 'search' => true ] );
		$search_columns = wp_list_pluck(  $search_columns, 'name');
		return($search_columns);
	}
	/**
	 * get_primary_key
	 * geeft de naam van het veld met de primary key terug
	 *
	 * @return string
	 */
	public function get_primary_key() : string {
		$primary_keys = wp_list_filter( $this->columns, [ 'primary_key' => true ] );
		$primary_keys = wp_list_pluck(  $primary_keys, 'name');
		$primary_key = reset($primary_keys);
		return($primary_key);
	}
	
	/** Truncate tabel */
	public function truncate() : bool {
		return (bool) $this->wpdb->query( "TRUNCATE TABLE {$this->table}");
	}
	
	/** Insert data */
	public function insert( array $data ) : bool {

		//Alleen data van bestaande kolommen gebruiken
		$data = wp_array_slice_assoc( $data, wp_list_pluck( $this->columns, 'name' ) );
		
		$column_types = $this->get_column_data_types();

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

			if ( is_null( $value ) ) {
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
		$data_types = $this->get_column_data_types();
		array_walk(
			$data,
			fn( &$value, $key, $data_types ) => $value = $this->typecast_value( $value, $data_types[ $key ] ),
			$data_types
		);
		return $data;
	}

	/** Haal kolom uit database (o.b.v. where-array met `column => value` ) */
	public function get_col( string $col, array $where = [] ) : array {
		if ( ! in_array( $col, wp_list_pluck( $this->columns, 'name' ) ) ) {
			return [];
		}

		//Where clause opbouwen TODO: losse functie
		foreach ( $where as $field => $value ) {
			if ( ! in_array( $field, wp_list_pluck( $this->columns, 'name' ) ) ) {
				continue;
			}

			if ( is_null( $value ) ) {
				$conditions[] = "`$field` IS NULL";
				continue;
			}
			$conditions[] = "`$field` = %s"; //TODO: juiste placeholder gebruiken
			$values[]     = $value;
		}

		if ( ! empty( $where ) ) {
			$where_clause = implode( ' AND ', $conditions );
		}
		else {
			$where_clause = '1 = %d';
			$values[] = 1;
		}

		$data = $this->wpdb->get_col(
			$this->wpdb->prepare(
				"SELECT {$col} FROM {$this->table} WHERE $where_clause",
				$values
			)
		);

		//TODO: typecasten
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

	/** Voeg foreign key toe */
	public function add_foreign_key( Database_Table $referenced_table, array $referenced_fields, array $fields ) : bool {
		//TODO: parameter voor on delete/ on update
		//TODO: checks op velden en tabel
		$referenced_table_full_name = $this->get_full_table_name( $referenced_table->value );


		//Eerst kijken of de foreign key al bestaat. Zo ja, dan afbreken
		$check_query = [];
		$check_query[] = "SELECT CONSTRAINT_NAME";
		$check_query[] = "FROM information_schema.TABLE_CONSTRAINTS";
		$check_query[] = sprintf( "WHERE CONSTRAINT_SCHEMA = '%s'", $this->wpdb->dbname );
		$check_query[] = sprintf("AND CONSTRAINT_NAME = 'fk_%s'", $referenced_table_full_name );
		$check_query[] = "AND CONSTRAINT_TYPE = 'FOREIGN KEY'";
		$check_query[] = sprintf( "AND TABLE_NAME = '%s'", $this->table );
		

		if ( 0 !== $this->wpdb->query( implode( PHP_EOL, $check_query )  ) ) {
			return false;
		}

		$fk_query = [];
		$fk_query[] = sprintf( "ALTER TABLE `%s`", $this->table );
		$fk_query[] = sprintf( "ADD CONSTRAINT `fk_%s`", $referenced_table_full_name );
		$fk_query[] = sprintf( "FOREIGN KEY (%s)", implode( ',', $fields ) );
		$fk_query[] = sprintf( "REFERENCES `%s` (%s)", $referenced_table_full_name, implode( ',', $referenced_fields ) );
		$fk_query[] = "ON DELETE CASCADE;";
		
		return (bool) $this->wpdb->query( implode( PHP_EOL, $fk_query ) );
	}

	/** Typecase waarde o.b.v. type */
	protected function typecast_value( $value, string $type ) {
		switch ( $type ) {
			case 'CHAR': 
			case 'VARCHAR':
			case 'TEXT':
			case 'DATE':
				$value = trim( strval( $value ) );
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
				$value = trim( strval( $value ) );
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

	/** Geeft volledige tabelnaam terug */
	protected function get_full_table_name( string $table_name ) : string {
		return $this->wpdb->prefix . 'siw_'. $table_name;
	}

	/** Geeft data typer kolom terug */
	protected function get_column_data_types() : array {
		return wp_list_pluck( $this->columns, 'type', 'name' );
	}

	/**
	 * count_rows
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */

	public function count_rows()
	{
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM $this->table";

		return $wpdb->get_var( $sql );
	}
	# get-rows
	# $args['table'] - databasetable
	# $args['sort'] - column to be sorted
	# $args['prefilter'] - overall filter
	# $args['filters'] - Array ( [column1] => value [column2] => value ........ ) 
	# 					value may be preceded by:
	#					# : search on full content
	#					< : content should be <= value
	#					> : content should be >= value
	# $args["search'] - array(array ('column1','column2' ....),$value)
	#					- match $value in the given columns
	# $args['page'} - current pagenumber
	# $args['maxlines'] - maxlines per page
	# $args['output'] - (string) (Optional) Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants. default=OBJECT
	public function get_rows($args)
	{
		global $wpdb;
		$defaults = array(
			'sort' => '',
			'prefilter' => '',
			'filters' => '',
			'search' => '',
			'page' => '',
			'maxlines' => '',
			'output' => 'OBJECT',
		  );
		$args = wp_parse_args( $args, $defaults );
		$values=[];
		#
		# make conditions for the query
		#
		$conditions='';
		#
		# translate filters to query conditions
		#
		#
		# first check prefilter
		#
		if($args['prefilter'])
		{
			foreach($args['prefilter'] as $i => $value) 
			{
				if($conditions) {$conditions .= ' and '; }
				$conditions .= '('. $i . '="' . $value . '")';
			}
		}
		#
		# search value in given columns
		#
		if($args['search'])
		{
			$columns = $args['search'][0];
			$value = $args['search'][1];
			
			foreach ($columns as $f)
			{
				$key = "%" . $value . "%"; #match on content
				if($conditions) {$conditions .= ' or '; }
				$conditions .= '('. $f . ' LIKE "' . $key . '")';
			}
		}
		if($args['filters'])
		{
			foreach($args['filters'] as $f => $value)
			{
				if($conditions) {$conditions .= ' and '; }
				#
				# If < or > before value search on <= resp >=
				#
				if(preg_match('/^>(.*)/',$value,$match))   
				{
					$value = $match[1];
					$conditions .= '('. $f . ' >= "' . $value . '")';
				}
				#
				# when prefix of filter is max_ then the key  the maximum value of a field.
				#
				elseif(preg_match('/^<(.*)/',$value,$match))   
				{
					$value = $match[1];
					$conditions .= '('. $f . ' <= "' . $value . '")';
				}
				# if key numerical search on full field or word in field
				#
				#
				elseif(is_numeric($value))
				{
					$conditions .= '('. $f . ' = "' . $value . '"';
					$conditions .= ' or ';
					$key = '"' . $value . '" ';
					$conditions .= $f . ' LIKE ' . $key;
					$conditions .= " or ";
					$key = ' "' . $value . '" ';
					$conditions .= $f . " LIKE " . $key;
					$conditions .= " or ";
					$key = ' "' . $value . '"';
					$conditions .= $f . ' LIKE ' . $key . ')';
				}
				else
				{
					if(preg_match("/#/",$value))
					{
						$key=substr($value,1);   #search on full content
					}
					else
					{
						$key = "%" . $value . "%"; #match on content
					}
					$conditions .= '('. $f . ' LIKE "' . $key . '")';
				}
			}
		}
		#
		# start the query
		#
		$query='SELECT * FROM '. $this->table;
		if($conditions) { $query .= ' WHERE ' . $conditions;}
		#
		# sort argument
		# translate to query sort field
		#
		if($args['sort'] &&  $args['sort'] != "no")
		{
			$query .= ' ORDER BY ' . $args['sort'];
		}
		#
		# $limit is maximum number of rows to be displayed
		# $page = current pagenumber
		# so calculate offset
		#
		if($args['maxlines'])
		{
			$offset=0;
			if(is_numeric($args['maxlines'])) { $offset=($args['page']-1)*$args['maxlines']; }
			$query .= ' LIMIT %d,%d';
			$values[] = $offset;
			$values[] = $args['maxlines'];
		}
		#
		$query =  $wpdb->prepare($query,$values);
		$rows=$wpdb->get_results( $query , $args['output'] );
		return($rows);
	}
	/**
	* column_names
	* get the column names of the table
	*/
	public function column_names() {
		global $wpdb;
		return($wpdb->get_col("DESC {$this->table}", 0));
	}
}
