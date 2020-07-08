<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package woostrap
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">

		<div class="col-full">
			<a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_attr_e( 'Skip to navigation', 'woostrap' ); ?></a>
			<a class="skip-link screen-reader-text" href="#content"><?php esc_attr_e( 'Skip to content', 'woostrap' ); ?></a>
			<div class="site-branding">
				<?php woostrap_site_title_or_logo(); ?>
			</div>
			<?php if ( has_nav_menu( 'secondary' ) ): ?>
			<nav class="secondary-navigation" role="navigation" aria-label="<?php esc_html_e( 'Secondary Navigation', 'woostrap' ); ?>">
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'secondary',
							'fallback_cb'    => '',
						)
					);
				?>
			</nav><!-- #site-navigation -->
			<?php endif; ?>
			<?php if ( storefront_is_woocommerce_activated() ): ?>
			<div class="site-search">
				<?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
			</div>
			<?php endif; ?>
		</div>
		<div class="storefront-primary-navigation">
			<div class="col-full">
				<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'woostrap' ); ?>">
					<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span><?php echo esc_attr( apply_filters( 'storefront_menu_toggle_text', __( 'Menu', 'woostrap' ) ) ); ?></span></button>
					<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'container_class' => 'primary-navigation',
						)
					);

					wp_nav_menu(
						array(
							'theme_location'  => 'handheld',
							'container_class' => 'handheld-navigation',
						)
					);
					?>
				</nav><!-- #site-navigation -->
				<?php 
				if ( storefront_is_woocommerce_activated() ) {
					if ( is_cart() ) {
						$class = 'current-menu-item';
					} else {
						$class = '';
					}
				}
				?>
				<ul id="site-header-cart" class="site-header-cart menu">
					<li class="<?php echo esc_attr( $class ); ?>">
						<?php woostrap_site_cart_link(); ?>
					</li>
					<li>
						<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
					</li>
				</ul>
			</div>
		</div>

	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		do_action( 'storefront_content_top' );
