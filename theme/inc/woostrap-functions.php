<?php
/**
 * Storefront functions.
 *
 * @package woostrap
 */

if ( ! function_exists( 'woostrap_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function woostrap_is_woocommerce_activated() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}
}

/**
 * Call a shortcode function by tag name.
 *
 * @since  1.4.6
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function storefront_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

/**
 * Get the content background color
 * Accounts for the Storefront Designer and Storefront Powerpack content background option.
 *
 * @since  1.6.0
 * @return string the background color
 */
function storefront_get_content_background_color() {
	if ( class_exists( 'Storefront_Designer' ) ) {
		$content_bg_color = get_theme_mod( 'sd_content_background_color' );
		$content_frame    = get_theme_mod( 'sd_fixed_width' );
	}

	if ( class_exists( 'Storefront_Powerpack' ) ) {
		$content_bg_color = get_theme_mod( 'sp_content_frame_background' );
		$content_frame    = get_theme_mod( 'sp_content_frame' );
	}

	$bg_color = str_replace( '#', '', get_theme_mod( 'background_color' ) );

	if ( class_exists( 'Storefront_Powerpack' ) || class_exists( 'Storefront_Designer' ) ) {
		if ( $content_bg_color && ( 'true' === $content_frame || 'frame' === $content_frame ) ) {
			$bg_color = str_replace( '#', '', $content_bg_color );
		}
	}

	return '#' . $bg_color;
}

/**
 * Apply inline style to the Storefront header.
 *
 * @uses  get_header_image()
 * @since  2.0.0
 */
function storefront_header_styles() {
	$is_header_image = get_header_image();
	$header_bg_image = '';

	if ( $is_header_image ) {
		$header_bg_image = 'url(' . esc_url( $is_header_image ) . ')';
	}

	$styles = array();

	if ( '' !== $header_bg_image ) {
		$styles['background-image'] = $header_bg_image;
	}

	$styles = apply_filters( 'storefront_header_styles', $styles );

	foreach ( $styles as $style => $value ) {
		echo esc_attr( $style . ': ' . $value . '; ' );
	}
}

/**
 * Apply inline style to the Storefront homepage content.
 *
 * @uses  get_the_post_thumbnail_url()
 * @since  2.2.0
 */
function storefront_homepage_content_styles() {
	$featured_image   = get_the_post_thumbnail_url( get_the_ID() );
	$background_image = '';

	if ( $featured_image ) {
		$background_image = 'url(' . esc_url( $featured_image ) . ')';
	}

	$styles = array();

	if ( '' !== $background_image ) {
		$styles['background-image'] = $background_image;
	}

	$styles = apply_filters( 'storefront_homepage_content_styles', $styles );

	foreach ( $styles as $style => $value ) {
		echo esc_attr( $style . ': ' . $value . '; ' );
	}
}

/**
 * Adjust a hex color brightness
 * Allows us to create hover styles for custom link colors
 *
 * @param  strong  $hex   hex color e.g. #111111.
 * @param  integer $steps factor by which to brighten/darken ranging from -255 (darken) to 255 (brighten).
 * @return string        brightened/darkened hex color
 */
function storefront_adjust_color_brightness( $hex, $steps ) {
	// Steps should be between -255 and 255. Negative = darker, positive = lighter.
	$steps = max( -255, min( 255, $steps ) );

	// Format the hex color string.
	$hex = str_replace( '#', '', $hex );

	if ( 3 === strlen( $hex ) ) {
		$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	}

	// Get decimal values.
	$r = hexdec( substr( $hex, 0, 2 ) );
	$g = hexdec( substr( $hex, 2, 2 ) );
	$b = hexdec( substr( $hex, 4, 2 ) );

	// Adjust number of steps and keep it inside 0 to 255.
	$r = max( 0, min( 255, $r + $steps ) );
	$g = max( 0, min( 255, $g + $steps ) );
	$b = max( 0, min( 255, $b + $steps ) );

	$r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
	$g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
	$b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

	return '#' . $r_hex . $g_hex . $b_hex;
}

/**
 * Sanitizes choices (selects / radios)
 * Checks that the input matches one of the available choices
 *
 * @param array $input the available choices.
 * @param array $setting the setting object.
 * @since  1.3.0
 */
function storefront_sanitize_choices( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Checkbox sanitization callback.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function woostrap_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

function woostrap_sanitize_navbar_text_style( $value ) {
	if ($value === 'dark' || $value === 'light') {
		return $value;
	}

	return 'light';
}

/**
 * Get the information about the logo.
 * 
 * Credits to twentytwenty theme.
 *
 * @param string $html The HTML output from get_custom_logo (core function).
 *
 * @return string $html
 */
function woostrap_get_custom_logo( $html ) {

	$logo_id = get_theme_mod( 'custom_logo' );

	if ( ! $logo_id ) {
		return $html;
	}

	$logo = wp_get_attachment_image_src( $logo_id, 'full' );

	if ( $logo ) {
		// For clarity.
		$logo_width  = esc_attr( $logo[1] );
		$logo_height = esc_attr( $logo[2] );

		// If the retina logo setting is active, reduce the width/height by half.
		if ( get_theme_mod( 'retina_logo', false ) ) {
			$logo_width  = floor( $logo_width / 2 );
			$logo_height = floor( $logo_height / 2 );

			$search = array(
				'/width=\"\d+\"/iU',
				'/height=\"\d+\"/iU',
			);

			$replace = array(
				"width=\"{$logo_width}\"",
				"height=\"{$logo_height}\"",
			);

			// Add a style attribute with the height, or append the height to the style attribute if the style attribute already exists.
			if ( strpos( $html, ' style=' ) === false ) {
				$search[]  = '/(src=)/';
				$replace[] = "style=\"height: {$logo_height}px;\" src=";
			} else {
				$search[]  = '/(style="[^"]*)/';
				$replace[] = "$1 height: {$logo_height}px;";
			}

			$html = preg_replace( $search, $replace, $html );

		}
	}

	return $html;

}

add_filter( 'get_custom_logo', 'woostrap_get_custom_logo' );

function woostrap_navbar_styles() {
	$bg_color = get_theme_mod('woostrap_navbar_background_color');

	if ($bg_color) {
		echo 'style="background:' . $bg_color . ';"';
	}
}

function woostrap_navbar_classes() {
	$text_style = get_theme_mod('woostrap_navbar_text_style');

	if ($text_style === 'light') {
		echo 'navbar-dark ';
	} else {
		echo 'navbar-light';
	}
}
