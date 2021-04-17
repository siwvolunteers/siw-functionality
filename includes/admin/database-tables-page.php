<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Admin\Database_List_Table;
use SIW\Database_Table;

/**
 * Admin pagina voor database tabellen
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Database_Tables_Page {

	/** Slug voor menu-pagina */
	const MENU_SLUG = 'siw-database-tables';

	protected array $tables;

	protected Database_List_Table $list_table;

	/** Init */
	public static function init() {
		$self = new self();
		$self->tables = Database_Table::toArray();
		add_action( 'admin_menu', [ $self, 'admin_menu' ] );
		add_filter( 'set-screen-option', [ $self, 'set_screen_option'], 10, 3);
		add_action('admin_enqueue_scripts', [ $self, 'enqueue_modal_window_assets']);
	}


	function enqueue_modal_window_assets()
	{
	  // Check that we are on the right screen
	  if (get_current_screen()->id == 'my_menu_page') {
		// Enqueue the assets
		wp_enqueue_style('thickbox');
		wp_enqueue_script('plugin-install');
	  }
	}

	/** Toevoegen submenu */
	public function admin_menu() {
		$page_hook = add_management_page(
			__( 'SIW Database tabellen', 'siw' ),
			__( 'SIW Database tabellen', 'siw' ),
			'manage_options',
			self::MENU_SLUG,
			[ $this, 'render_page'],
		);
		add_action( "load-{$page_hook}", [$this, 'add_screen_options' ] );
	}

	/** Sla schermoptie op */
	function set_screen_option( $keep, $option, $value ) {
		foreach ( $this->tables as $table => $name ) {
			if ( $option === "{$table}_per_page" ) {
				$keep = $value;
			}
		}
		return $keep;
	}

	/** Haal huidige tabel op */
	protected function get_current_table() : string {
		return isset( $_GET['table'] ) ? sanitize_text_field( $_GET['table'] ) : array_key_first( $this->tables );
	}

	/** Voegt scherm opties toe */
	function add_screen_options() {

		//Initialiseer List_Table zodat de kolommen beschikbaar zijn voor de schermopties
		$table = Database_Table::make( $this->get_current_table() );
		$this->list_table = new Database_List_Table( $table );

		$args = [
			'label'   => __( 'Items', 'siw' ),
			'default' => Database_List_Table::DEFAULT_ITEMS_PER_PAGE,
			'option'  => "{$this->get_current_table()}_per_page",
		];
		add_screen_option( 'per_page', $args );
	}

	/** Rendert de pagina */
	public function render_page() {
		add_thickbox();
		?>
		<!-- Naviagatie-tabs -->
		<nav class="nav-tab-wrapper">
			<?php
			foreach ( $this->tables as $table => $name ) {
				printf(
					'<a href="%s" class="nav-tab %s">%s</a>',
					add_query_arg( [
						'page'  => self::MENU_SLUG,
						'table' => $table,
					], 'tools.php' ),
					( $this->get_current_table() == $table ) ? 'nav-tab-active' : '',
					$name,
				);
			}
			?>
		</nav>

		<!-- Tabel -->
		<div class="wrap">
			<h2><?php echo esc_html( $this->tables[ $this->get_current_table()] ); ?> </h2>
			<?php $this->list_table->prepare_items(); ?>
			<form method="get">
				<input type="hidden" name="page" value="<?php echo self::MENU_SLUG;?>"/>
				<input type="hidden" name="table" value="<?php echo $this->get_current_table();?>" />
				<?php $this->list_table->search_box( esc_attr__( 'Zoeken', 'siw' ), 'search' ); ?>
			</form>
			<?php $this->list_table->display(); ?>
		</div>

	<?php
	}
}
