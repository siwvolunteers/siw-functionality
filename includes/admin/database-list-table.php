<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Database_Table;
use SIW\Helpers\Database;

/**
 * List tabel met inhoud van database tabel
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

	/** Init */
	public function __construct( Database_Table $database_table ) {
		$this->database = new Database( $database_table );
		$this->table_name = $database_table->value;
		parent::__construct( [
			'singular' => $database_table->value,
			'plural'   => "{$database_table->value}s",
		] );
	}

	/** Haalt de records op die getoond moeten worden */
	public function get_records( int $per_page, int $page_number = 1 ): ?array {
		$args = [
			'per_page'       => $per_page,
			'page'           => $page_number,
			'search'         => isset( $_REQUEST['s'] ) && $_REQUEST['s'] ? $_REQUEST['s'] : null,
			'search_columns' => isset( $_REQUEST['s'] ) ? $this->get_searchable_columns() : [],
			'orderby'        => $_REQUEST['orderby'] ?? null,
			'order'          => $_REQUEST['order'] ?? null,
			'output'         => OBJECT
		];
		return $this->database->get_rows( $args );
	}
	
	/** {@inheritDoc} */
	public function no_items() {
		esc_html_e( 'Tabel is leeg.', 'siw' );
	}

	/** {@inheritDoc} */
	public function column_default( $item, $column_name ): string {
		return isset( $item->$column_name ) ? $item->$column_name : ''; //TODO: aparte weergave voor booleans en eventueel andere datatypes
	}

	/** Toont kolom met details-knop TODO: Table element gebruiken */
	public function column_view_details( $item ): string {
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
			__( 'Bekijk details', 'siw' ),
			__( 'Bekijk details', 'siw' )
		);
	}

	/** {@inheritDoc} */
	public function get_columns(): array {
		$all_columns = $this->database->get_columns();
		$columns = wp_list_filter( $all_columns, [ 'show' => true ] );
		$columns = wp_list_pluck(  $columns, 'name', 'name' );

		//Als we niet alle kolommen tonen, voeg dan een view details kolom toe
		if ( count( $all_columns ) != count( $columns ) ) {
			$columns = array_merge(
				$columns,
				[ 'view_details' => __( 'Details', 'siw' ) ]
			);
		}
		return $columns;
	}

	/** {@inheritDoc} */
	public function get_sortable_columns(): array {
		$columns = $this->database->get_columns();
		$columns = wp_list_filter( $columns, [ 'sort' => true ] );
		return wp_list_pluck(  $columns, 'name', 'name' );
	}

	/** Geeft doorzoekbare tabellen terug */
	public function get_searchable_columns(): array {
		$columns = $this->database->get_columns();
		$columns = wp_list_filter( $columns, [ 'search' => true ] );
		return wp_list_pluck(  $columns, 'name' );
	}

	/** {@inheritDoc} */
	public function prepare_items() {
		$per_page     = $this->get_items_per_page( $this->table_name . '_records_per_page', self::DEFAULT_ITEMS_PER_PAGE );
		$current_page = $this->get_pagenum();
		$total_items  = $this->database->get_row_count(); //Klopt niet, je moet het aantal records na filtering tellen
		$this->items = $this->get_records( $per_page, $current_page );

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page
		] );
	}
}