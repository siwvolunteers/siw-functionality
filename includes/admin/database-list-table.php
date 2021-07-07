<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Database_Table;
use SIW\Helpers\Database;

/**
 * Lijstweergave van database tabel
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Database_List_Table extends \WP_List_Table {

	/** Default aantal items per pagina */
	const DEFAULT_ITEMS_PER_PAGE = 25;

	/** Database helper */
	protected Database $database;

	/** Tabelnaam */
	protected string $table_name;

	/** {@inheritDoc} */
	function __construct( Database_Table $database_table ) {

		$this->database = new Database( $database_table );
		$this->table_name = $database_table->value;
		parent::__construct( [
			'singular' => $database_table->value,
			'plural'   => "{$database_table->value}s",
		] );
	}

	/** {@inheritDoc} */
	public function get_columns() : array {

		$columns = wp_list_pluck( $this->database->get_columns(), 'name', 'name' );
		$columns = array_slice( $columns, 0, 10 ); //Tijdelijk alleen eerste 10 kolommen tonen, moet eigenschap van kolom worden

		return array_merge(
			$columns,
			['view_details' => __( 'Details', 'siw' )] //TODO: alleen toevoegen als niet alle kolommen getoond worden
		);
	}

	/** {@inheritDoc} */
	public function get_sortable_columns() : array {
		//TODO: eigenschap van kolomdefinitie maken
		return [];
	}

	/** {@inheritDoc} */
	function column_default( $item, $column_name ) : string {
		return isset( $item->$column_name ) ? $item->$column_name : ''; //TODO: aparte weergave voor booleans en eventueel andere datatypes
	}

	/** Kolom met knop om details te bekijken TODO: mooier formatteren*/
	function column_view_details( $item ) {

		$id = wp_unique_id(); 
		?>
		<div id="details-<?php echo $id;?>" style="display:none;">
			<table class="form-table">
				<tbody>
				<?php foreach( $item as $prop_name => $prop_val ) : ?>
					<tr>
						<th scope="row"><?php echo $prop_name; ?></th>
						<td><?php echo $prop_val; ?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>
		<?php 
		return sprintf(
			'<a href="%s" class="button button-secondary thickbox" title="%s">%s</a>',
			"#TB_inline?&inlineId=details-{$id}",
			__( 'Details', 'siw' ),
			__( 'Details', 'siw' )
		);
	}

	//TODO: verplaatsen naar Database helper + search toevoegen
	public function get_items( $args = [] ) {
		global $wpdb;

		$defaults = [
			'number'     => self::DEFAULT_ITEMS_PER_PAGE,
			'offset'     => 0,
			'orderby'    => array_key_first( $this->get_columns() ),
			'order'      => 'ASC',
		];

		$args      = wp_parse_args( $args, $defaults );
		$items = $wpdb->get_results(
			sprintf(
				'SELECT * FROM %s ORDER BY %s %s LIMIT %d OFFSET %d',
				$wpdb->prefix . 'siw_' . $this->table_name,
				$args['orderby'],
				$args['order'],
				$args['number'],
				$args['offset'] 
			),
		);
		return $items;
	}

	/** {@inheritDoc} */
	public function prepare_items() {
	
		$per_page     = $this->get_items_per_page( "{$this->table_name}_per_page", self::DEFAULT_ITEMS_PER_PAGE );
		$current_page = $this->get_pagenum();
		$offset       = ( $current_page -1 ) * $per_page;
		$total_items  = $this->get_record_count();

		$args = [
			'offset' => $offset,
			'number' => $per_page,
		];

		if ( ! empty( $_GET['s'] ) ) {
			$s = sanitize_text_field( $_GET['s'] );
			$args['search'] = $s;
		}

		if ( isset( $_GET['orderby'] ) && isset( $_GET['order'] ) ) {
			$args['orderby'] = sanitize_text_field( $_GET['orderby'] );
			$args['order']   = sanitize_text_field( $_GET['order'] ) ;
		}

		$this->items = $this->get_items( $args );

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page
		] );
	}

	//TODO: verplaatsen naar Database helper
	public function get_record_count() {
		global $wpdb;
		return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'siw_' . $this->table_name );
	}
}
