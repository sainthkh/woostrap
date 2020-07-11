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
		<nav class="navbar navbar-expand-xl p-3 navbar-light bg-light">
		<div class="container">
			<div class="navbar-brand">
				<?php if ( get_theme_mod( 'wp_bootstrap_starter_logo' ) ): ?>
					<a href="<?php echo esc_url( home_url( '/' )); ?>">
						<img src="<?php echo esc_url(get_theme_mod( 'wp_bootstrap_starter_logo' )); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					</a>
				<?php else : ?>
					<a class="site-title" href="<?php echo esc_url( home_url( '/' )); ?>"><?php esc_url(bloginfo('name')); ?></a>
				<?php endif; ?>
			</div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
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
