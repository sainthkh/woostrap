<?php
/**
 * Customizer Separator Control settings for this theme. Credits to TwentyTwenty theme.
 *
 * @package Woostrap
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	if ( ! class_exists( 'Woostrap_Separator_Control' ) ) {
		/**
		 * Separator Control.
		 */
		class Woostrap_Separator_Control extends WP_Customize_Control {
			/**
			 * Render the hr.
			 */
			public function render_content() {
				echo '<hr/>';
			}

		}
	}
}
