<?php
/**
 * Storefront WooCommerce Customizer Class
 *
 * @package  storefront
 * @since    2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Storefront_WooCommerce_Customizer' ) ) :

	/**
	 * The Storefront Customizer class
	 */
	class Storefront_WooCommerce_Customizer extends Woostrap_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 2.4.0
		 * @return void
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ), 10 );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css' ), 130 );
			add_filter( 'storefront_setting_default_values', array( $this, 'setting_default_values' ) );
		}

		/**
		 * Returns an array of the desired default Storefront Options
		 *
		 * @param array $defaults array of default options.
		 * @since 2.4.0
		 * @return array
		 */
		public function setting_default_values( $defaults = array() ) {
			$defaults['storefront_sticky_add_to_cart'] = true;
			$defaults['storefront_product_pagination'] = true;

			return $defaults;
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since 2.4.0
		 */
		public function customize_register( $wp_customize ) {

			/**
			 * Product Page
			 */
			$wp_customize->add_section(
				'storefront_single_product_page',
				array(
					'title'    => __( 'Product Page', 'woostrap' ),
					'priority' => 10,
					'panel'    => 'woocommerce',
				)
			);

			$wp_customize->add_setting(
				'storefront_product_pagination',
				array(
					'default'           => apply_filters( 'storefront_default_product_pagination', true ),
					'sanitize_callback' => 'wp_validate_boolean',
				)
			);

			$wp_customize->add_setting(
				'storefront_sticky_add_to_cart',
				array(
					'default'           => apply_filters( 'storefront_default_sticky_add_to_cart', true ),
					'sanitize_callback' => 'wp_validate_boolean',
				)
			);

			$wp_customize->add_control(
				'storefront_sticky_add_to_cart',
				array(
					'type'        => 'checkbox',
					'section'     => 'storefront_single_product_page',
					'label'       => __( 'Sticky Add-To-Cart', 'woostrap' ),
					'description' => __( 'A small content bar at the top of the browser window which includes relevant product information and an add-to-cart button. It slides into view once the standard add-to-cart button has scrolled out of view.', 'woostrap' ),
					'priority'    => 10,
				)
			);

			$wp_customize->add_control(
				'storefront_product_pagination',
				array(
					'type'        => 'checkbox',
					'section'     => 'storefront_single_product_page',
					'label'       => __( 'Product Pagination', 'woostrap' ),
					'description' => __( 'Displays next and previous links on product pages. A product thumbnail is displayed with the title revealed on hover.', 'woostrap' ),
					'priority'    => 20,
				)
			);
		}

		/**
		 * Get Customizer css.
		 *
		 * @see get_storefront_theme_mods()
		 * @since 2.4.0
		 * @return string $styles the css
		 */
		public function get_css() {
			$storefront_theme_mods = $this->get_storefront_theme_mods();
			$brighten_factor       = apply_filters( 'storefront_brighten_factor', 25 );
			$darken_factor         = apply_filters( 'storefront_darken_factor', -25 );

			$styles = '
			table.cart td.product-remove,
			table.cart td.actions {
				border-top-color: ' . $storefront_theme_mods['background_color'] . ';
			}

			.storefront-handheld-footer-bar ul li.cart .count {
				background-color: ' . $storefront_theme_mods['header_link_color'] . ';
				color: ' . $storefront_theme_mods['header_background_color'] . ';
				border-color: ' . $storefront_theme_mods['header_background_color'] . ';
			}

			.order_details {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -7 ) . ';
			}

			.order_details > li {
				border-bottom: 1px dotted ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -28 ) . ';
			}

			.order_details:before,
			.order_details:after {
				background: -webkit-linear-gradient(transparent 0,transparent 0),-webkit-linear-gradient(135deg,' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -7 ) . ' 33.33%,transparent 33.33%),-webkit-linear-gradient(45deg,' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -7 ) . ' 33.33%,transparent 33.33%)
			}

			#order_review {
				background-color: ' . $storefront_theme_mods['background_color'] . ';
			}

			#payment .payment_methods > li .payment_box,
			#payment .place-order {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -5 ) . ';
			}

			#payment .payment_methods > li:not(.woocommerce-notice) {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -10 ) . ';
			}

			#payment .payment_methods > li:not(.woocommerce-notice):hover {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -15 ) . ';
			}

			.woocommerce-pagination .page-numbers li .page-numbers.current {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], $darken_factor ) . ';
			}';

			if ( ! class_exists( 'Storefront_Product_Pagination' ) ) {
				$styles .= '
				.storefront-product-pagination a {
					background-color: ' . $storefront_theme_mods['background_color'] . ';
				}';
			}

			if ( ! class_exists( 'Storefront_Sticky_Add_to_Cart' ) ) {
				$styles .= '
				.storefront-sticky-add-to-cart {
					background-color: ' . $storefront_theme_mods['background_color'] . ';
				}

				.storefront-sticky-add-to-cart a:not(.button) {
					color: ' . $storefront_theme_mods['header_link_color'] . ';
				}';
			}

			return apply_filters( 'storefront_customizer_woocommerce_css', $styles );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 *
		 * @since 2.4.0
		 * @return void
		 */
		public function add_customizer_css() {
			wp_add_inline_style( 'storefront-woocommerce-style', $this->get_css() );
		}

	}

endif;

return new Storefront_WooCommerce_Customizer();
