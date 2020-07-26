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

add_action( 'init', 'e2e_util_reset_customize' );

/**
 * Reset wp_customize 
 */
function e2e_util_reset_customize() {
	if ( isset( $_GET['reset-customize'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		remove_theme_mods();
	}
}

add_action( 'init', 'e2e_util_logout' );

/**
 * Logout user
 */
function e2e_util_logout() {
	if ( isset( $_GET['logout'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_logout();
	}
}

add_action( 'init', 'e2e_util_login' );

/**
 * Login user
 */
function e2e_util_login() {
	if ( isset( $_GET['login'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$username = wp_kses_data( wp_unslash( $_GET['login'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$user     = get_user_by( 'login', $username );

		if ( ! is_wp_error( $user ) ) {
			wp_clear_auth_cookie();
			wp_set_current_user( $user->ID );
			wp_set_auth_cookie( $user->ID );
		}
	}
}
