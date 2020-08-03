<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package woostrap
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php if ( ! is_page_template( 'blank-page.php' ) && ! is_page_template( 'blank-page-with-container.php' ) ) : ?>

	<header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-expand-md p-3 <?php woostrap_navbar_classes(); ?>" <?php woostrap_navbar_styles(); ?>>
		<div class="container">
			<div class="navbar-brand site-branding">
			<?php woostrap_site_logo(); ?>
			</div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#site-navigation" aria-controls="" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				<span><?php esc_html_e( 'menu', 'woostrap' ); ?></span>
			</button>
			<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container'       => 'div',
						'container_id'    => 'site-navigation',
						'container_class' => 'collapse navbar-collapse justify-content-end',
						'menu_id'         => false,
						'menu_class'      => 'navbar-nav',
						'depth'           => 3,
						'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
						'walker'          => new wp_bootstrap_navwalker(),
					)
				);
			?>
			<?php 
			if ( woostrap_is_woocommerce_activated() ) {
				woostrap_search_form();
				woostrap_cart_button();
			} 
			?>
		</div>
		</nav>
		<?php if ( is_front_page() && get_theme_mod( 'woostrap_hero_area_visibility' ) ) : ?>
		<div id="hero-area" class="jumbotron">
			<div class="container">
				<h1 class="hero-area-title display-4">
					<?php
					if ( get_theme_mod( 'woostrap_hero_area_title_text' ) ) {
						echo esc_html( get_theme_mod( 'woostrap_hero_area_title_text' ) );
					} else {
						echo 'WooCommerce + Bootstrap';
					}
					?>
				</h1>
				<p class="hero-area-tagline lead">
					<?php
					if ( get_theme_mod( 'hero_area_tagline_text' ) ) {
						echo esc_html( get_theme_mod( 'hero_area_tagline_text' ) );
					} else {
						esc_html_e( 'To customize the contents of this header banner and other elements of your site, go to Dashboard > Appearance > Customize', 'woostrap' );
					}
					?>
				</p>
			</div>
		</div>
		<?php endif; ?>
	</header><!-- #masthead -->

<?php endif; ?>

	<?php if ( is_active_sidebar( 'header-1' ) ) : ?>
		<div class="header-widget-region" role="complementary">
			<div class="col-full">
				<?php dynamic_sidebar( 'header-1' ); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php 
	if ( woostrap_is_woocommerce_activated() ) {
		woocommerce_breadcrumb();
	} 
	?>

	<?php do_action( 'woostrap_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		do_action( 'storefront_content_top' );
