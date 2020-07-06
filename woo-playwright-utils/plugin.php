<?php
/**
 * Plugin Name: WooCommerce Playwright Utils
 */

add_action( 'init', 'woocommerce_clear_cart_url' );

// Credits to https://gist.github.com/kloon/4541017
function woocommerce_clear_cart_url() {
	if ( isset( $_GET['clear-cart'] ) ) {
		WC()->cart->empty_cart();
	}
}