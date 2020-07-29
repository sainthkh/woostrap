<?php
/**
 * Customizer Heading Control settings for this theme.
 *
 * @package Woostrap
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	if ( ! class_exists( 'Woostrap_Heading_Control' ) ) {
		/**
		 * Separator Control.
		 */
		class Woostrap_Heading_Control extends WP_Customize_Control {
			/**
			 * Render the hr.
			 */
			public function render_content() {
				echo '<h2 style="margin-top:0;margin-bottom:0;">' . $this->label . '</h2>';
			}

		}
	}
}
