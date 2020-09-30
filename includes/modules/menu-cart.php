<?php declare(strict_types=1);

namespace SIW\Modules;

use SIW\Elements;

/**
 * Voegt cart toe aan menu
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Menu_Cart {

	/**
	 * Menu-locaties
	 *
	 * @var array
	 * 
	 * @todo verplaatsen naar configuratie
	 */
	protected $menu_locations = [
		'primary',
		'slideout'
	];

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'wp_nav_menu_items', [ $self, 'add_cart_to_menu'], 10, 2 );
		add_filter( 'woocommerce_add_to_cart_fragments', [ $self, 'update_cart'] );
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_scripts' ], PHP_INT_MAX );
	}

	/**
	 * Voegt cart toe aan menu
	 *
	 * @param string $items
	 * @param \stdClass $args
	 * @return string
	 */
	public function add_cart_to_menu( string $items, \stdClass $args ) : string {
		if ( ! in_array( $args->theme_location, $this->menu_locations ) ) {
			return $items;
		}
		$items .= '<li class="menu-item menu-cart">' . $this->render_cart() . '</li>';
		return $items;
	}

	/**
	 * Rendert cart
	 *
	 * @return string
	 */
	protected function render_cart() : string {
		$cart_count = WC()->cart->get_cart_contents_count();
		ob_start();
		?>
		<a class="siw-cart" href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_attr_e( 'Winkelmand', 'siw') ?>">
			<span class="hide-on-desktop hide-on-tablet"><?php esc_html_e( 'Je winkelmand', 'siw' );?></span>
			<?php echo Elements::generate_icon( 'siw-icon-suitcase');?>
			<span class="siw-cart-count"><?php echo $cart_count; ?></span>
		</a>
		<?php
		return ob_get_clean();
	}

	/**
	 * Werkt cart bij 
	 *
	 * @param array $fragments
	 * @return array
	 */
	public function update_cart( array $fragments ) : array {
		$cart_count = WC()->cart->get_cart_contents_count();
		$fragments['span.siw-cart-count'] = '<span class="siw-cart-count">' . $cart_count . '</span>';
		return $fragments;
	}

	/**
	 * Voegt scripts toe
	 */
	public function enqueue_scripts() {
		wp_register_script( 'siw-menu-cart', SIW_ASSETS_URL . 'js/modules/siw-menu-cart.js', [ 'jquery', 'js-cookie' ], SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-menu-cart' );
	}
}
