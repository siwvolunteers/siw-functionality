<?php declare(strict_types=1);

namespace SIW\Modules;

use SIW\Util\CSS;

/**
 * Mega Menu
 *
 * @copyright 2020-2021 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://docs.generatepress.com/article/building-simple-mega-menu/
 */
class Mega_Menu {

	/** Assets handle */
	const ASSETS_HANDLE = 'siw-mega-menu';

	/** Meta key */
	const META_KEY = 'siw_mega_menu';

	/** Nonce actie */
	const NONCE_ACTION = 'siw_set_mega_menu';

	/** Nonce naam */
	const NONCE_NAME = 'siw_mega_menu_nonce';

	/** Init */
	public static function init() {
		$self = new self();

		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_styles' ] );
		add_action( 'wp_nav_menu_item_custom_fields', [ $self, 'add_nav_menu_item_custom_fields' ], 10, 2 );

		add_filter( 'nav_menu_css_class', [ $self, 'add_nav_menu_item_class' ], 10, 4 );
		add_action( 'wp_update_nav_menu_item', [ $self, 'update_nav_menu_item' ], 10, 2 );
	}

	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		$min_width = CSS::MOBILE_BREAKPOINT + 1;
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/modules/mega-menu.css', [], SIW_PLUGIN_VERSION, "(min-width: {$min_width}px)" );
		// TODO: bug melden omdat wp_maybe_inline_styles() media query niet overneemt
		// wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/modules/mega-menu.css.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	/** Voegt velden voor Mega Menu toe aan nav item */
	public function add_nav_menu_item_custom_fields( int $item_id, \WP_Post $item ) {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
		$mega_menu = get_post_meta( $item_id, self::META_KEY, true );

		$mega_menu_options = [
			''  => __( 'Geen', 'siw' ),
			'2' => __( '2 kolommen', 'siw' ),
			'3' => __( '3 kolommen', 'siw' ),
			'4' => __( '4 kolommen', 'siw' ),
			'5' => __( '5 kolommen', 'siw' ),
		];

		?>
		<p class="field-siw_mega_menu description description-wide">
			<label for="siw_mega_menu_for_<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Mega Menu', 'siw' ); ?></label>
			<br />
			<input type="hidden" class="nav-menu-id" value="<?php echo esc_attr( $item_id ); ?>" />
			<select name="<?php echo esc_attr( self::META_KEY ); ?>[<?php echo esc_attr( $item_id ); ?>]" id="siw_mega_menu_for_<?php echo esc_attr( $item_id ); ?>">
				<?php
				foreach ( $mega_menu_options as $value => $label ) {
					printf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $value, $mega_menu, false ), esc_html( $label ) );
				}
				?>
			</select>
		</p>
		<?php
	}

	/** Gekozen mega menu opslaan bij menu item */
	public function update_nav_menu_item( int $menu_id, int $menu_item_id ) {

		if ( ! isset( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
			return $menu_id;
		}
		if ( isset( $_POST[ self::META_KEY ][ $menu_item_id ] ) && ! empty( $_POST[ self::META_KEY ][ $menu_item_id ] ) ) {
			$sanitized_data = sanitize_text_field( wp_unslash( $_POST[ self::META_KEY ] )[ $menu_item_id ] );
			update_post_meta( $menu_item_id, self::META_KEY, $sanitized_data );
		} else {
			delete_post_meta( $menu_item_id, self::META_KEY );
		}
	}

	/** Voegt css-klasses voor mega menu toe aan menu item */
	public function add_nav_menu_item_class( array $classes, $item, \stdClass $args, int $depth ) : array {

		if ( is_a( $item, \WP_Post::class ) && get_post_meta( $item->ID, self::META_KEY, true ) ) {
			$classes[] = 'mega-menu';
			$classes[] = sprintf( 'mega-menu-col-%s', esc_attr( get_post_meta( $item->ID, self::META_KEY, true ) ) );
		}
		return $classes;
	}
}
