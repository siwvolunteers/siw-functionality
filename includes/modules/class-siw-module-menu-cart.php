<?php

/**
 * Voegt cart toe aan menu
 *
 * @package   SIW\Modules
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

class SIW_Module_Menu_Cart {

	/**
	 * Menu-locaties
	 *
	 * @var array
	 * 
	 * @todo verplaatsen naar configuratie
	 */
	protected $menu_locations = [
		'primary_navigation',
		'mobile_navigation'
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
	 * @param stdClass $args
	 * @return string
	 */
	public function add_cart_to_menu( string $items, stdClass $args ) {
		if ( ! in_array( $args->theme_location, $this->menu_locations ) ) {
			return $items;
		}
		$items .= '<li class="menu-item menu-cart">' . $this->render_cart() . '</li>';
		return $items;
	}

	/**
	 * Rendert cart
	 */
	protected function render_cart() {
		$cart_count = WC()->cart->get_cart_contents_count();
		ob_start();
		?>
		<a class="siw-cart" href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_attr_e( 'Winkelmand', 'siw') ?>">
			<span class="hidden-sm hidden-md hidden-lg"><?php esc_html_e( 'Je winkelmand', 'siw' );?></span>
			<i class="siw-icon-suitcase"></i>
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
	public function update_cart( array $fragments ) {
		$cart_count = WC()->cart->get_cart_contents_count();
		$fragments['span.siw-cart-count'] = '<span class="siw-cart-count">' . $cart_count . '</span>';
		return $fragments;
	}

	/**
	 * Voegt scripts toe
	 */
	public function enqueue_scripts() {
		wp_register_script( 'siw-menu-cart', SIW_ASSETS_URL . 'js/siw-menu-cart.js', [ 'js-cookie' ] , SIW_PLUGIN_VERSION, true );
		wp_enqueue_script( 'siw-menu-cart' );
	}

}