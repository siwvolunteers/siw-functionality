<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Admin\Database_List_Table;
use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Database_Table;

/**
 * Database tabel viewer
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Tableview_Page extends Base {

	/** Slug voor menu-pagina */
	const MENU_SLUG = 'siw-database-tables';

	/** Instantie van List table */
	public Database_List_Table $database_list_table;

	/** Huidige database tabel */
	protected Database_Table $current_table;

	/** Array voor afleiden van tabel uit page hook */
	public array $tables;

	#[Action( 'admin_menu' )]
	/** Voegt menupagina's toe */
	public function add_menu_pages() {
		add_menu_page(
			__( 'Database tabellen', 'siw' ),
			__( 'Database tabellen', 'siw' ),
			'manage_options',
			self::MENU_SLUG,
			null,
			'dashicons-database'
		);

		foreach ( Database_Table::toArray() as $table => $name ) {
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

		// verwijder het hoofdmenu als submenu
		remove_submenu_page( self::MENU_SLUG, self::MENU_SLUG );
	}

	/** Toon de tabel */
	public function display_table() {
		add_thickbox();
		?>
		<div class="wrap">
			<h2><?php echo esc_html( $this->current_table->label ); ?> </h2>
			<?php $this->database_list_table->prepare_items(); ?>
			<form method="get">
				<input type="hidden" name="page" value="siw-database-table-<?php echo esc_attr( $this->current_table->value ); ?>"/>
				<?php $this->database_list_table->search_box( esc_attr__( 'Zoeken', 'siw' ), 'search' ); ?>
			</form>
			<?php $this->database_list_table->display(); ?>
		</div>

		<?php
	}

	/** Voegt optie voor aantal recores per pagina toe */
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

	#[Filter( 'set-screen-option' )]
	/** Sla schermoptie op */
	public function set_screen_option( $keep, $option, $value ) {
		foreach ( Database_Table::toValues() as $table ) {
			if ( "{$table}_records_per_page" === $option ) {
				$keep = $value;
			}
		}
		return $keep;
	}
}
