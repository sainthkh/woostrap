<?php
/**
 * Custom template tags for this theme.
 *
 * @package woostrap
 */

/**
 * Displays the site logo, either text or image.
 * Credits to twentytwenty theme.
 *
 * @param array   $args Arguments for displaying the site logo either as an image or text.
 * @param boolean $echo Echo or return the HTML.
 *
 * @return string $html Compiled HTML based on our arguments.
 */
function woostrap_site_logo( $args = array(), $echo = true ) {
	$logo       = get_custom_logo();
	$site_title = get_bloginfo( 'name' );
	$contents   = '';
	$classname  = '';

	$defaults = array(
		'logo'        => '%1$s<span class="screen-reader-text">%2$s</span>',
		'logo_class'  => 'site-logo',
		'title'       => '<a href="%1$s">%2$s</a>',
		'title_class' => 'site-title',
		'home_wrap'   => '<h1 class="%1$s">%2$s</h1>',
		'single_wrap' => '<div class="%1$s faux-heading">%2$s</div>',
		'condition'   => ( is_front_page() || is_home() ) && ! is_page(),
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Filters the arguments for `woostrap_site_logo()`.
	 *
	 * @param array  $args     Parsed arguments.
	 * @param array  $defaults Function's default arguments.
	 */
	$args = apply_filters( 'woostrap_site_logo_args', $args, $defaults );

	if ( has_custom_logo() ) {
		$contents  = sprintf( $args['logo'], $logo, esc_html( $site_title ) );
		$classname = $args['logo_class'];
	} else {
		$contents  = sprintf( $args['title'], esc_url( get_home_url( null, '/' ) ), esc_html( $site_title ) );
		$classname = $args['title_class'];
	}

	$wrap = $args['condition'] ? 'home_wrap' : 'single_wrap';

	$html = sprintf( $args[ $wrap ], $classname, $contents );

	/**
	 * Filters the arguments for `woostrap_site_logo()`.
	 *
	 * @param string $html      Compiled html based on our arguments.
	 * @param array  $args      Parsed arguments.
	 * @param string $classname Class name based on current view, home or single.
	 * @param string $contents  HTML for site title or logo.
	 */
	$html = apply_filters( 'woostrap_site_logo', $html, $args, $classname, $contents );

	if ( ! $echo ) {
		return $html;
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

}

/**
 * Search form for products.
 */
function woostrap_search_form() { ?>
	<form role="search" method="get" class="d-none d-md-block form-inline my-2 my-lg-0 woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
		<div class="form-group has-search">
			<i class="fas fa-search form-control-feedback"></i>
			<input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="form-control mr-sm-2 search-field" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		</div>
		<button type="submit" class="btn btn-outline-success my-2 my-sm-0" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>"><?php echo esc_html_x( 'Search', 'submit button', 'woocommerce' ); ?></button>
		<input type="hidden" name="post_type" value="product" />
	</form>
	<?php
}

function woostrap_cart_button() { ?>
	<div class="d-none d-md-block shopping-cart">
		<a class="shopping-cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'woostrap' ); ?>">
			<i class="fas fa-shopping-cart"></i>
		</a>
		<?php

		$item_count = WC()->cart->get_cart_contents_count();

		if ($item_count > 0): ?>
			<span class="badge badge-dark"><?php echo wp_kses_data( $item_count ) ?></span>
		<?php endif; ?>
	</div>
	<?php
}