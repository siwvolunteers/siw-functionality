<?php
namespace SIW\Admin;

#use SIW\Helpers\dbio;	#imports the specified namespace (or class) to the current scope


class DisplayRecords extends \WP_List_Table {

	/** Class constructor */
	public $dbtable;			# current Database table object
	public $primary_key;	# primary key of the table
	#public $search_columns;	# columns to be filtered by search box
	public $current_table;	#the current table to be displayed
	#public $show_columns;	# columns te be displayed
	#public $sortable_columns;	# columns te be sorted
	public $single_name;		# singular name of listed records
	public $plural_name;		# plural name of listed records
	#public $per_page_label;		# label voor records per pagina
	#public $per_page_option;	# option voor records per pagina

	public function __construct() {

		parent::__construct( [
			'singular' => $this->single_name, //singular name of the listed records
			'plural'   => $this->plural_name, //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve projects data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public function get_records( $per_page = 5, $page_number = 1 ) 
	{
		add_thickbox();

		$html = '';
		$html .= '<div id="my-content-id" style="display:none;">';
     	$html .= '<p>';
        $html .= ' This is hidden content! It will appear in Popup when the link is clicked.';
     	$html .= '</p>';
		$html .= '</div>';
		echo $html;

		$searchcolumns = $this->dbtable->get_searchcolumns();
		$search = isset($_POST['s']) && $_POST['s'] ? array($searchcolumns,$_POST['s']) : "";
		$args = array(
			"maxlines"=>$per_page,
			"page"=>$page_number,
			"search"=>$search,
			"sort"=>esc_sql($_REQUEST['orderby']),
			"output"=>"ARRAY_A"
		);
		$result = $this->dbtable->ReadRecords($args);	#lees records uit database
		#
		# maak de content om alle velden van een record te kunnen bekijken
		# Dat kan als het veld dat de primary key is van een record wordt getoond.
		# Door hier op te klikken wordt in een popup de inhoud van alle velden getoond
		#
		$detailkey=$this->dbtable->get_primary_key();
		$newresult = array();
		foreach ($result as $record)
		{
			$newrecord = array();
			foreach ($record as $key=>$value)
			{
				if($key == $detailkey)
				{
					$this->HiddenContent($key,$value);
					$linkvalue = '<a href="#TB_inline?&width=1000&height=1000&inlineId=' . $value . '" class="thickbox">' . $value . '</a>';
					$newrecord[$detailkey] = $linkvalue;
				}
				else
				{
					$newrecord[$key] = $value;
				}
			}
			array_push($newresult,$newrecord);
		}
		return $newresult;
	}
	#
	# @param string $key
	# @param string $value
	#
	# Maakt een tabel van de inhoud van alle velden van een record.
	# $key = de naam van het veld met de primary key
	# $value = inhoud van dat veld
	#
	public function HiddenContent($key,$value)
	{
		$args = array(
			"key"=>$key,
			"value"=>$value
		);
		$html = '';
		$html .= '<div id="'. $value . '" style="display:none;">';
     	$html .= '<p>';
        $html .= $this->dbtable->DisplayAllFields($args);
     	$html .= '</p>';
		$html .= '</div>';
		echo $html;
	}
	


	/**
	 * Delete a record.
	 *
	 * @param int $id project ID
	 */
	public static function delete_record( $id ) 
	{
		echo "to be implemented";
		global $wpdb;

	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public function record_count() {
		global $wpdb;

		$wptable = $wpdb->prefix . $this->current_table;
		$sql = "SELECT COUNT(*) FROM $wptable";

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no project data is available */
	public function no_items() {
		_e( 'No records avaliable.', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) 
	{
		if (array_key_exists($column_name,$this->dbtable->get_showcolumns()))		# komt een k0lom voor in de opgegeven te printen kolommen
		{
			return $item[ $column_name ];
		}
		else
		{
			return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item[$this->primary_key]
		);
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
		$cb = [
			'cb'      => '<input type="checkbox" />'
		];
		if(!$columns)
		{
			echo "show_columns is empty";
		}
		$columns=array_merge($cb,$columns);
		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() 
	{
		#return $this->sortable_columns;
		return $this->dbtable->get_sortcolumns();
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() 
	{
		$this->_column_headers = $this->get_column_info();
		#echo "<br>";
		#print_r($this->column_headers);

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'records_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = $this->get_records( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_record' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_record( absint( $_GET['record'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_record( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
	}

}
?>