<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Admin\Database_List_Table;
use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Icons\Dashicons;
use SIW\Plato\Database\Table;
use SIW\Plato\Database\Free_Places\Table as Free_Places_Table;
use SIW\Plato\Database\Partners\Table as Partners_Table;
use SIW\Plato\Database\Projects\Table as Projects_Table;

class Tableview_Page extends Base {

	private const MENU_SLUG = 'siw-database-tables';

	public Database_List_Table $database_list_table;

	protected Table $current_table;

	public array $tables;

	/**
	 * @return Table[]
	 * */
	protected function get_tables(): array {
		return [
			new Partners_Table(),
			new Projects_Table(),
			new Free_Places_Table(),
		];
	}

	#[Add_Action( 'admin_menu' )]
	public function add_menu_pages() {
		add_menu_page(
			__( 'Database tabellen', 'siw' ),
			__( 'Database tabellen', 'siw' ),
			'manage_options',
			self::MENU_SLUG,
			null,
			Dashicons::DATABASE->icon_class()
		);

		foreach ( $this->get_tables() as $table ) {
			$hook = add_submenu_page(
				self::MENU_SLUG,
				$table->description,
				$table->description,
				'manage_options',
				'siw-database-table-' . $table->name,
				[ $this, 'display_table' ],
				null
			);
			$this->tables[ "load-$hook" ] = $table;
			add_action( "load-$hook", [ $this, 'add_screen_options' ] );
		}

		remove_submenu_page( self::MENU_SLUG, self::MENU_SLUG );
	}

	public function display_table() {
		add_thickbox();
		?>
		<div class="wrap">
			<h2><?php echo esc_html( $this->current_table->description ); ?> </h2>
			<?php $this->database_list_table->prepare_items(); ?>
			<form method="get">
				<input type="hidden" name="page" value="siw-database-table-<?php echo esc_attr( $this->current_table->name ); ?>"/>
				<?php $this->database_list_table->search_box( esc_attr__( 'Zoeken', 'siw' ), 'search' ); ?>
			</form>
			<?php $this->database_list_table->display(); ?>
		</div>

		<?php
	}

	public function add_screen_options() {
		$this->current_table = $this->tables[ current_filter() ];
		$this->database_list_table = new Database_List_Table( $this->current_table );

		$args = [
			'label'   => __( 'Records per pagina', 'siw' ),
			'default' => Database_List_Table::DEFAULT_ITEMS_PER_PAGE,
			'option'  => $this->current_table->value . '_records_per_page',
		];
		add_screen_option( 'per_page', $args );
	}

	#[Add_Filter( 'set-screen-option' )]
	public function set_screen_option( $keep, $option, $value ) {
		foreach ( $this->get_tables() as $table ) {
			if ( "{$table->name}_records_per_page" === $option ) {
				$keep = $value;
			}
		}
		return $keep;
	}
}
