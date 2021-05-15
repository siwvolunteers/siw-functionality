<?php
namespace SIW\Admin;

use SIW\Admin\Database_List_Table;
use SIW\Helpers\dbio;
use SIW\Database_Table;
use SIW\Helpers\Database;

class Tableview_Page
{
	public $displayclass;

	protected array $dbtables;
	public array $tables;
	public array $names;

	function init() {
		$self = new self();
		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		$self->dbtables = Database_Table::toArray();  #[plato_project_free_places] => PLATO_PROJECT_FREE_PLACES [plato_project_images] => PLATO_PROJECT_IMAGES )		
		// create custom plugin settings menu
		#add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_filter( 'set-screen-option', [$self,'set_option'], 10, 3 );
		add_action('admin_menu', array($self,'MakePage') );
	}
	function MakePage() {
		//create new top-level menu
		
		$self = new self();
		$hook=add_menu_page (
			'plato table view',
			'plato table view',
			'manage_options',
			'platoprojects_slug',
			array($self, 'DisplayTable'),
			plugins_url('/images/icon.png', __FILE__) 
		);
		$self->tables[$hook] = $table;		#save table for function Displaytable
		$self->names[$hook] = $name;		#save name for function Displaytable
		add_action( "load-$hook", [ $self, 'add_screen_options' ] );
		foreach ($this->dbtables as $table => $name) {
			#$args = array('table'=>$name);
			$hook=add_submenu_page
			(
				'platoprojects_slug',
				$table,
				$table,
				'administrator',
				$table.'_slug',
				[ $self, 'DisplayTable' ],
				plugins_url('/images/icon.png', __FILE__) 
			);
			$self->tables[$hook] = $table;		#save table for function Displaytable
			$self->names[$hook] = $name;		#save name for function Displaytable
			$self->names["load-$hook"] = $name;		#save name for function Displaytable
			$self->tables["load-$hook"] = $table;		#save name for function Displaytable
			add_action( "load-$hook", [ $self, 'add_screen_options' ] );
		}
	}
	/**
	 * Display the table
	 */
	public function DisplayTable() {
		global $title;
		$displayrecords = $this->displayclass;
		$table = $this->tables[current_filter()];
		$name = $this->names[current_filter()];
		$html = '';
		$html .= '<h2>' . __('tabel: ','siw') . $name . '</h2>';

		$html .= '<div class="wrap">';

		$hmtl .= '<div id="poststuff">';
		$html .= 	'<div id="post-body" class="metabox-holder columns-2">';
		$html .= 		'<div id="post-body-content">';
		$html .=			'<div class="meta-box-sortables ui-sortable">';
		$html .=				'<form method="post">';
		echo $html;
		$this->TableOptions();
		$displayrecords->prepare_items();
		$displayrecords->search_box('search', 'search_id');
		$displayrecords->display();
		$html .= '</form></div></div></div><br class="clear"></div></div>';
		echo $html;
	}
	public function TableOptions() {
		$displayrecords = $this->displayclass;
		$currenttable = $this->tables[current_filter()];
		$currentname = $this->names[current_filter()];
		$displayrecords->current_table="siw_" . $currenttable;
		$table = Database_Table::make($currentname);
		$dbtable = new Database( $table);
		$displayrecords->dbtable=$dbtable;
		$displayrecords->single_name = $currenttable; //singular name of the listed records
		$displayrecords->plural_name = "{$currenttable}s"; //plural name of the listed records
		$displayrecords->show_columns=$dbtable->get_showcolumns();
	}
	/**
	 * All core backend pages containing a WP_List_Table provide a “Screen Options” slide-
	 * in where the user can adjust the columns to be shown and the number of rows to be displayed.
	 * To add options to your plugin you need to change your current code. 
	 * First you have to make sure that the screen options are displayed only on the current page:
	 * Screen options called when menu is loaded (see adminpage.php)
	 */
	public function add_screen_options() {
		$this->displayclass = new DisplayRecords();
		#
		# Database table object bepalen
		#
		$currentname = $this->names[current_filter()];
		$currenttable = $this->tables[current_filter()];
		$option = 'per_page';
		$args   = [
			'label'   => __('records','siw'),
			'default' => 10,
			'option'  => 'records_per_page'
		];
		add_screen_option( $option, $args ); #Register and configure an admin screen option
		$table = Database_Table::make($currentname);
		$this->displayclass->dbtable=new Database( $table);
	}
	function set_option( $status, $option, $value ) {
		return($value);
	}
}
?>