<?php
namespace SIW\Admin;

#use SIW\Helpers\dbio;	#imports the specified namespace (or class) to the current scope


class DisplayRecords extends \WP_List_Table {

	/** Class constructor */
	public $dbtable;			# current Database table object
	public $primary_key;	# primary key of the table
	public $current_table;	#the current table to be displayed
	public $single_name;		# singular name of listed records
	public $plural_name;		# plural name of listed records
	public $records_per_page = 20;	# default number of records per page

	public function __construct() {

		parent::__construct( [
			'singular' => $this->single_name, //singular name of the listed records
			'plural'   => $this->plural_name, //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * get_records
	 * Lees de records en kolommen in die getoond moeten worden
	 * rekening houdend met paginannummer, aantal records per pagina, sortering en zoeksleutel
	 * Het veld met de primary key wordt een link naar een popup om de inhoud van het gehele record te tonen.
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public function get_records( $per_page , $page_number = 1 ) {
		$searchcolumns = $this->dbtable->get_searchcolumns();
		$search = isset($_POST['s']) && $_POST['s'] ? array($searchcolumns,$_POST['s']) : "";
		$sort = isset($_REQUEST['orderby']) ? esc_sql($_REQUEST['orderby']) : '';
		$args = array(
			"maxlines"=>$per_page,
			"page"=>$page_number,
			"search"=>$search,
			"sort"=>$sort,
			"output"=>"ARRAY_A"
		);
		$result = $this->dbtable->get_rows($args);	#lees records uit database
		return($result);
	}
	
	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	
	public function record_count() {
		return $this->dbtable->count_rows();
	}

	/** Text displayed when no project data is available */
	public function no_items() {
		_e( 'Tabel is leeg.', 'siw' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
			if($column_name == $this->dbtable->get_primary_key())
			{
				#
				# maak de content om alle velden van een record te kunnen bekijken
				# Dat kan als het veld dat de primary key is van een record wordt getoond.
				# Door hier op te klikken wordt in een popup de inhoud van alle velden getoond
				#
				$this->HiddenContent($column_name,$item[$column_name]);
				$linkvalue = '<a href="#TB_inline?&width=1000&height=1000&inlineId=' . $item[$column_name] . '" class="thickbox">' . $item[$column_name] . '</a>';
				return($linkvalue);
			}
			else
			{
				return $item[ $column_name ];
			}
	}
	#
	# @param string $key
	# @param string $value
	#
	# Maakt een tabel van de inhoud van alle velden van een record.
	# $key = de naam van het veld met de primary key
	# $value = inhoud van dat veld
	#
	public function HiddenContent($key,$value) {
		$args = array(
			"key"=>$key,
			"value"=>$value
		);
		$html = '';
		$html .= '<div id="'. $value . '" style="display:none;">';
     	$html .= '<p>';
        $html .= $this->display_all_fields($args);
     	$html .= '</p>';
		$html .= '</div>';
		echo $html;
	}
	/**
	 * display_all_fields of a record
	 * Maak een tabel van de content van alle velden in een record
	 * @param $args
	 * $args['key'] - name of unique key
	 * $args['value'] - value of unique key
	 *
	 * @return HTML tabel : veldnaam | inhoud
	 */
	public function display_all_fields($args)
	{
		global $wp;
		global $wpdb;
		$html = '';
		#
		# get the column names in the table
		#
		$columns = $this->dbtable->column_names();
		$p=$this->dbtable->get_row([$args['key']=>$args['value']]);
		#
		# display content of all fields
		#
		$html .= '<table>';
		foreach($columns as $c)
		{
			$html .= '<tr>';
			$html .= '<td>'.$c.'</td>';
			$html .= '<td>'.$p[$c].'</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		return($html);
	}
	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_record' );

		$title = '<strong>' . $item['code'] . '</strong>';

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&record=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item[$this->primary_key] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 * The method get_columns() is needed to label the columns on the top and bottom of the table. 
	 * The keys in the array have to be the same as in the data array otherwise the respective columns aren’t displayed.

	 *
	 * @return array
	 */
	function get_columns() 
	{
		$columns = $this->dbtable->get_showcolumns();
		
		if(!$columns)
		{
			echo "show_columns is empty";
		}
		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() 
	{
		return $this->dbtable->get_sortcolumns();
	}
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() 
	{
		$this->_column_headers = $this->get_column_info();


		$per_page     = $this->get_items_per_page( 'records_per_page', $this->records_per_page );
		$current_page = $this->get_pagenum();
		$total_items  = $this->record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = $this->get_records( $per_page, $current_page );
	}
}