<?php

use SIW\HTML;

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
			printf(
				'<button type="submit" name="add-to-cart" value="%s" class="single_add_to_cart_button button alt" %s>%s</button>',
				esc_attr( $product->get_id() ),
				HTML::generate_attributes( apply_filters( 'siw_woocommerce_add_to_cart_button_attributes', [], $product ) ),
				esc_html( $product->single_add_to_cart_text() ),

			);
		?>
		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
