<?php
/**
 * Plugin Name: WooCommerce Playwright Utils
 * 
 * @package woostrap
 */

add_action( 'init', 'e2e_util_woocommerce_clear_cart_url' );

/**
 * Clear cart with ?clear-cart in the url.
 * 
 * Credits to https://gist.github.com/kloon/4541017
 */ 
function e2e_util_woocommerce_clear_cart_url() {
	if ( isset( $_GET['clear-cart'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		WC()->cart->empty_cart();
	}
}

add_action('init', 'e2e_util_reset_customize');

/**
 * Reset wp_customize 
 */
function e2e_util_reset_customize() {
	if ( isset( $_GET['reset-customize'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		remove_theme_mods();
	}
}
