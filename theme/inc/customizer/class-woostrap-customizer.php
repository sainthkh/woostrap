<?php
/**
 * Storefront Customizer Class
 *
 * @package  storefront
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woostrap_Customizer' ) ) :

	/**
	 * The Storefront Customizer class
	 */
	class Woostrap_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ), 10 );
			add_filter( 'body_class', array( $this, 'layout_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css' ), 130 );
			add_action( 'customize_controls_print_styles', array( $this, 'customizer_custom_control_css' ) );
			add_action( 'customize_register', array( $this, 'edit_default_customizer_settings' ), 99 );
			add_action( 'enqueue_block_assets', array( $this, 'block_editor_customizer_css' ) );
			add_action( 'init', array( $this, 'default_theme_mod_values' ), 10 );
		}

		/**
		 * Returns an array of the desired default Storefront Options
		 *
		 * @return array
		 */
		public function get_storefront_default_setting_values() {
			return apply_filters(
				'storefront_setting_default_values',
				$args = array(
					'storefront_header_background_color' => '#ffffff',
					'storefront_header_text_color'       => '#404040',
					'storefront_header_link_color'       => '#333333',
					'storefront_footer_background_color' => '#f0f0f0',
					'storefront_footer_heading_color'    => '#333333',
					'storefront_footer_text_color'       => '#6d6d6d',
					'storefront_footer_link_color'       => '#333333',
					'background_color'                   => 'ffffff',
				)
			);
		}

		/**
		 * Adds a value to each Storefront setting if one isn't already present.
		 *
		 * @uses get_storefront_default_setting_values()
		 */
		public function default_theme_mod_values() {
			foreach ( $this->get_storefront_default_setting_values() as $mod => $val ) {
				add_filter( 'theme_mod_' . $mod, array( $this, 'get_theme_mod_value' ), 10 );
			}
		}

		/**
		 * Get theme mod value.
		 *
		 * @param string $value Theme modification value.
		 * @return string
		 */
		public function get_theme_mod_value( $value ) {
			$key = substr( current_filter(), 10 );

			$set_theme_mods = get_theme_mods();

			if ( isset( $set_theme_mods[ $key ] ) ) {
				return $value;
			}

			$values = $this->get_storefront_default_setting_values();

			return isset( $values[ $key ] ) ? $values[ $key ] : $value;
		}

		/**
		 * Set Customizer setting defaults.
		 * These defaults need to be applied separately as child themes can filter storefront_setting_default_values
		 *
		 * @param  array $wp_customize the Customizer object.
		 * @uses   get_storefront_default_setting_values()
		 */
		public function edit_default_customizer_settings( $wp_customize ) {
			foreach ( $this->get_storefront_default_setting_values() as $mod => $val ) {
				$wp_customize->get_setting( $mod )->default = $val;
			}
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since  1.0.0
		 */
		public function customize_register( $wp_customize ) {
			/* -------------------------------------------------------------------------- */

			/* Modify default sections and controls.	
			/* -------------------------------------------------------------------------- */
			
			// Move background color setting alongside background image.
			$wp_customize->get_control( 'background_color' )->section  = 'background_image';
			$wp_customize->get_control( 'background_color' )->priority = 20;

			// Change background image section title & priority.
			$wp_customize->get_section( 'background_image' )->title    = __( 'Background', 'woostrap' );
			$wp_customize->get_section( 'background_image' )->priority = 30;

			// Change header image section title & priority.
			$wp_customize->get_section( 'header_image' )->title    = __( 'Header', 'woostrap' );
			$wp_customize->get_section( 'header_image' )->priority = 25;

			/**
			 * Selective refresh.
			 */ 
			$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

			$wp_customize->selective_refresh->add_partial(
				'custom_logo',
				array(
					'selector'        => '.site-branding [class*=site-]:not(.site-description)',
					'render_callback' => array( $this, 'get_site_logo' ),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'retina_logo',
				array(
					'selector'        => '.site-branding [class*=site-]:not(.site-description)',
					'render_callback' => array( $this, 'get_site_logo' ),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => array( $this, 'get_site_name' ),
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => array( $this, 'get_site_description' ),
				)
			);

			$wp_customize->add_setting(
				'retina_logo',
				array(
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'woostrap_sanitize_checkbox',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'retina_logo',
				array(
					'type'        => 'checkbox',
					'section'     => 'title_tagline',
					'priority'    => 10,
					'label'       => __( 'Retina logo', 'woostrap' ),
					'description' => __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'woostrap' ),
				)
			);

			/**
			 * Load custom controls
			 */
			require_once dirname( __FILE__ ) . '/class-storefront-customizer-control-radio-image.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require_once dirname( __FILE__ ) . '/class-storefront-customizer-control-arbitrary.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound


			/* -------------------------------------------------------------------------- */

			/* Header Section
			/* -------------------------------------------------------------------------- */

			/**
			 * Header Background
			 */
			$wp_customize->add_setting(
				'storefront_header_background_color',
				array(
					'default'           => apply_filters( 'storefront_default_header_background_color', '#2c2d33' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'storefront_header_background_color',
					array(
						'label'    => __( 'Background color', 'woostrap' ),
						'section'  => 'header_image',
						'settings' => 'storefront_header_background_color',
						'priority' => 15,
					)
				)
			);

			/**
			 * Header text color
			 */
			$wp_customize->add_setting(
				'storefront_header_text_color',
				array(
					'default'           => apply_filters( 'storefront_default_header_text_color', '#9aa0a7' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'storefront_header_text_color',
					array(
						'label'    => __( 'Text color', 'woostrap' ),
						'section'  => 'header_image',
						'settings' => 'storefront_header_text_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Header link color
			 */
			$wp_customize->add_setting(
				'storefront_header_link_color',
				array(
					'default'           => apply_filters( 'storefront_default_header_link_color', '#d5d9db' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'storefront_header_link_color',
					array(
						'label'    => __( 'Link color', 'woostrap' ),
						'section'  => 'header_image',
						'settings' => 'storefront_header_link_color',
						'priority' => 30,
					)
				)
			);

			/* -------------------------------------------------------------------------- */

			/* Footer Section
			/* -------------------------------------------------------------------------- */

			/**
			 * Add footer section
			 */
			$wp_customize->add_section(
				'storefront_footer',
				array(
					'title'       => __( 'Footer', 'woostrap' ),
					'priority'    => 28,
					'description' => __( 'Customize the look & feel of your website footer.', 'woostrap' ),
				)
			);

			/**
			 * Footer Background
			 */
			$wp_customize->add_setting(
				'storefront_footer_background_color',
				array(
					'default'           => apply_filters( 'storefront_default_footer_background_color', '#f0f0f0' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'storefront_footer_background_color',
					array(
						'label'    => __( 'Background color', 'woostrap' ),
						'section'  => 'storefront_footer',
						'settings' => 'storefront_footer_background_color',
						'priority' => 10,
					)
				)
			);

			/**
			 * Footer heading color
			 */
			$wp_customize->add_setting(
				'storefront_footer_heading_color',
				array(
					'default'           => apply_filters( 'storefront_default_footer_heading_color', '#494c50' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'storefront_footer_heading_color',
					array(
						'label'    => __( 'Heading color', 'woostrap' ),
						'section'  => 'storefront_footer',
						'settings' => 'storefront_footer_heading_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Footer text color
			 */
			$wp_customize->add_setting(
				'storefront_footer_text_color',
				array(
					'default'           => apply_filters( 'storefront_default_footer_text_color', '#61656b' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'storefront_footer_text_color',
					array(
						'label'    => __( 'Text color', 'woostrap' ),
						'section'  => 'storefront_footer',
						'settings' => 'storefront_footer_text_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Footer link color
			 */
			$wp_customize->add_setting(
				'storefront_footer_link_color',
				array(
					'default'           => apply_filters( 'storefront_default_footer_link_color', '#2c2d33' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'storefront_footer_link_color',
					array(
						'label'    => __( 'Link color', 'woostrap' ),
						'section'  => 'storefront_footer',
						'settings' => 'storefront_footer_link_color',
						'priority' => 40,
					)
				)
			);
		}

		/**
		 * Get all of the Storefront theme mods.
		 *
		 * @return array $storefront_theme_mods The Storefront Theme Mods.
		 */
		public function get_storefront_theme_mods() {
			$storefront_theme_mods = array(
				'background_color'            => storefront_get_content_background_color(),
				'header_background_color'     => get_theme_mod( 'storefront_header_background_color' ),
				'header_link_color'           => get_theme_mod( 'storefront_header_link_color' ),
				'header_text_color'           => get_theme_mod( 'storefront_header_text_color' ),
				'footer_background_color'     => get_theme_mod( 'storefront_footer_background_color' ),
				'footer_link_color'           => get_theme_mod( 'storefront_footer_link_color' ),
				'footer_heading_color'        => get_theme_mod( 'storefront_footer_heading_color' ),
				'footer_text_color'           => get_theme_mod( 'storefront_footer_text_color' ),
			);

			return apply_filters( 'storefront_theme_mods', $storefront_theme_mods );
		}

		/**
		 * Get Customizer css.
		 *
		 * @see get_storefront_theme_mods()
		 * @return array $styles the css
		 */
		public function get_css() {
			$storefront_theme_mods = $this->get_storefront_theme_mods();
			$brighten_factor       = apply_filters( 'storefront_brighten_factor', 25 );
			$darken_factor         = apply_filters( 'storefront_darken_factor', -25 );

			$styles = '
			.main-navigation ul li a,
			.site-title a,
			ul.menu li a,
			.site-branding h1 a,
			button.menu-toggle,
			button.menu-toggle:hover,
			.handheld-navigation .dropdown-toggle {
				color: ' . $storefront_theme_mods['header_link_color'] . ';
			}

			button.menu-toggle,
			button.menu-toggle:hover {
				border-color: ' . $storefront_theme_mods['header_link_color'] . ';
			}

			.main-navigation ul li a:hover,
			.main-navigation ul li:hover > a,
			.site-title a:hover,
			.site-header ul.menu li.current-menu-item > a {
				color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['header_link_color'], 65 ) . ';
			}

			table:not( .has-background ) th {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -7 ) . ';
			}

			table:not( .has-background ) tbody td {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -2 ) . ';
			}

			table:not( .has-background ) tbody tr:nth-child(2n) td,
			fieldset,
			fieldset legend {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -4 ) . ';
			}

			.site-header,
			.secondary-navigation ul ul,
			.main-navigation ul.menu > li.menu-item-has-children:after,
			.secondary-navigation ul.menu ul,
			.storefront-handheld-footer-bar,
			.storefront-handheld-footer-bar ul li > a,
			.storefront-handheld-footer-bar ul li.search .site-search,
			button.menu-toggle,
			button.menu-toggle:hover {
				background-color: ' . $storefront_theme_mods['header_background_color'] . ';
			}

			p.site-description,
			.site-header,
			.storefront-handheld-footer-bar {
				color: ' . $storefront_theme_mods['header_text_color'] . ';
			}

			button.menu-toggle:after,
			button.menu-toggle:before,
			button.menu-toggle span:before {
				background-color: ' . $storefront_theme_mods['header_link_color'] . ';
			}

			.pagination .page-numbers li .page-numbers.current {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], $darken_factor ) . ';
			}

			#comments .comment-list .comment-content .comment-text {
				background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -7 ) . ';
			}

			.site-footer {
				background-color: ' . $storefront_theme_mods['footer_background_color'] . ';
				color: ' . $storefront_theme_mods['footer_text_color'] . ';
			}

			.site-footer a:not(.button):not(.components-button) {
				color: ' . $storefront_theme_mods['footer_link_color'] . ';
			}

			.site-footer .storefront-handheld-footer-bar a:not(.button):not(.components-button) {
				color: ' . $storefront_theme_mods['header_link_color'] . ';
			}

			.site-footer h1, .site-footer h2, .site-footer h3, .site-footer h4, .site-footer h5, .site-footer h6, .site-footer .widget .widget-title, .site-footer .widget .widgettitle {
				color: ' . $storefront_theme_mods['footer_heading_color'] . ';
			}

			@media screen and ( min-width: 768px ) {
				.secondary-navigation ul.menu a:hover {
					color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['header_text_color'], $brighten_factor ) . ';
				}

				.secondary-navigation ul.menu a {
					color: ' . $storefront_theme_mods['header_text_color'] . ';
				}

				.main-navigation ul.menu ul.sub-menu,
				.main-navigation ul.nav-menu ul.children {
					background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['header_background_color'], -15 ) . ';
				}

				.site-header {
					border-bottom-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['header_background_color'], -15 ) . ';
				}
			}';

			return apply_filters( 'storefront_customizer_css', $styles );
		}

		/**
		 * Get Gutenberg Customizer css.
		 *
		 * @see get_storefront_theme_mods()
		 * @return array $styles the css
		 */
		public function gutenberg_get_css() {
			$storefront_theme_mods = $this->get_storefront_theme_mods();
			$darken_factor         = apply_filters( 'storefront_darken_factor', -25 );

			// Gutenberg.
			$styles = '
				.wp-block-table:not( .has-background ):not( .is-style-stripes ) tbody tr:nth-child(2n) td {
					background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -2 ) . ';
				}
			';

			return apply_filters( 'storefront_gutenberg_customizer_css', $styles );
		}

		/**
		 * Enqueue dynamic colors to use editor blocks.
		 *
		 * @since 2.4.0
		 */
		public function block_editor_customizer_css() {
			$storefront_theme_mods = $this->get_storefront_theme_mods();

			$styles = '';

			if ( is_admin() ) {
				$styles .= '
				.editor-styles-wrapper {
					background-color: ' . $storefront_theme_mods['background_color'] . ';
				}

				.editor-styles-wrapper table:not( .has-background ) th {
					background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -7 ) . ';
				}

				.editor-styles-wrapper table:not( .has-background ) tbody td {
					background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -2 ) . ';
				}

				.editor-styles-wrapper table:not( .has-background ) tbody tr:nth-child(2n) td,
				.editor-styles-wrapper fieldset,
				.editor-styles-wrapper fieldset legend {
					background-color: ' . storefront_adjust_color_brightness( $storefront_theme_mods['background_color'], -4 ) . ';
				}

				body.post-type-post .editor-post-title__block::after {
					content: "";
				}';
			}

			$styles .= $this->gutenberg_get_css();

			wp_add_inline_style( 'storefront-gutenberg-blocks', apply_filters( 'storefront_gutenberg_block_editor_customizer_css', $styles ) );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_customizer_css() {
			wp_add_inline_style( 'storefront-style', $this->get_css() );
		}

		/**
		 * Layout classes
		 * Adds 'right-sidebar' and 'left-sidebar' classes to the body tag
		 *
		 * @param  array $classes current body classes.
		 * @return string[]          modified body classes
		 * @since  1.0.0
		 */
		public function layout_class( $classes ) {
			$left_or_right = get_theme_mod( 'storefront_layout' );

			$classes[] = $left_or_right . '-sidebar';

			return $classes;
		}

		/**
		 * Add CSS for custom controls
		 *
		 * This function incorporates CSS from the Kirki Customizer Framework
		 *
		 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
		 * is licensed under the terms of the GNU GPL, Version 2 (or later)
		 *
		 * @link https://github.com/reduxframework/kirki/
		 * @since  1.5.0
		 */
		public function customizer_custom_control_css() {
			?>
			<style>
			.customize-control-radio-image input[type=radio] {
				display: none;
			}

			.customize-control-radio-image label {
				display: block;
				width: 48%;
				float: left;
				margin-right: 4%;
			}

			.customize-control-radio-image label:nth-of-type(2n) {
				margin-right: 0;
			}

			.customize-control-radio-image img {
				opacity: .5;
			}

			.customize-control-radio-image input[type=radio]:checked + label img,
			.customize-control-radio-image img:hover {
				opacity: 1;
			}

			</style>
			<?php
		}

		/**
		 * Get site logo
		 */
		public function get_site_logo() {
			woostrap_site_logo();
		}

		/**
		 * Get site name.
		 */
		public function get_site_name() {
			bloginfo( 'name', 'display' );
		}

		/**
		 * Get site description.
		 */
		public function get_site_description() {
			bloginfo( 'description', 'display' );
		}

		/**
		 * Check if current page is using the Homepage template.
		 *
		 * @since 2.3.0
		 * @return bool
		 */
		public function is_homepage_template() {
			$template = get_post_meta( get_the_ID(), '_wp_page_template', true );

			if ( ! $template || 'template-homepage.php' !== $template || ! has_post_thumbnail( get_the_ID() ) ) {
				return false;
			}

			return true;
		}
	}

endif;

return new Woostrap_Customizer();
