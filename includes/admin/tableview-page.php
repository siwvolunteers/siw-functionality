<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Admin\Database_List_Table;
use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Database_Table;
use SIW\Data\Icons\Dashicons;

class Tableview_Page extends Base {

	private const MENU_SLUG = 'siw-database-tables';

	public Database_List_Table $database_list_table;

	protected Database_Table $current_table;

	public array $tables;

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

		foreach ( Database_Table::list() as $table => $name ) {
			$hook = add_submenu_page(
				self::MENU_SLUG,
				$name,
				$name,
				'manage_options',
				'siw-database-table-' . $table,
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
			<h2><?php echo esc_html( $this->current_table->label() ); ?> </h2>
			<?php $this->database_list_table->prepare_items(); ?>
			<form method="get">
				<input type="hidden" name="page" value="siw-database-table-<?php echo esc_attr( $this->current_table->value ); ?>"/>
				<?php $this->database_list_table->search_box( esc_attr__( 'Zoeken', 'siw' ), 'search' ); ?>
			</form>
			<?php $this->database_list_table->display(); ?>
		</div>

		<?php
	}

	public function add_screen_options() {
		$this->current_table = Database_Table::from( $this->tables[ current_filter() ] );
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
		foreach ( Database_Table::cases() as $table ) {
			if ( "{$table->value}_records_per_page" === $option ) {
				$keep = $value;
			}
		}
		return $keep;
	}
}
