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

<?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>

	<header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-expand-md p-3 navbar-light bg-light">
		<div class="container">
			<div class="navbar-brand site-branding">
			<?php woostrap_site_logo(); ?>
			</div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				<span><?php echo __('menu', 'woostrap') ?></span>
			</button>
			<?php
                wp_nav_menu(array(
                'theme_location'    => 'primary',
                'container'       => 'div',
                'container_id'    => 'main-nav',
                'container_class' => 'collapse navbar-collapse justify-content-end',
                'menu_id'         => false,
                'menu_class'      => 'navbar-nav',
                'depth'           => 3,
                'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
                'walker'          => new wp_bootstrap_navwalker()
				));
			?>
			<!-- <?php if ( storefront_is_woocommerce_activated() ): ?>
			<div class="site-search">
			<?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
			</div>
			<?php endif; ?> -->
		</div>
		</nav>
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
				</ul>
			</div>
		</div>

	</header><!-- #masthead -->

<?php endif; ?>

	<?php if ( is_active_sidebar( 'header-1' ) ): ?>
		<div class="header-widget-region" role="complementary">
			<div class="col-full">
				<?php dynamic_sidebar( 'header-1' ); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( storefront_is_woocommerce_activated() ) {
		woocommerce_breadcrumb();
	} ?>

	<?php do_action( 'woostrap_before_content' );	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		do_action( 'storefront_content_top' );
