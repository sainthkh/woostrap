<?php
/**
 * Alpha Color Picker Customizer Control
 *
 * This control adds a second slider for opacity to the stock WordPress color picker,
 * and it includes logic to seamlessly convert between RGBa and Hex color values as
 * opacity is added to or removed from a color.
 *
 * Credits to https://github.com/BraadMartin/components/tree/master/customizer/alpha-color-picker
 * 
 * This Alpha Color Picker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this Alpha Color Picker. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @package Woostrap
 */

/**
 * Alpha Color Picker Customizer Control
 */
class Customize_Alpha_Color_Control extends WP_Customize_Control {

	/**
	 * Official control name.
	 * 
	 * @var string
	 */
	public $type = 'alpha-color';

	/**
	 * Add support for palettes to be passed in.
	 *
	 * Supported palette values are true, false, or an array of RGBa and Hex colors.
	 * 
	 * @var array
	 */
	public $palette;

	/**
	 * Add support for showing the opacity value on the slider handle.
	 * 
	 * @var bool
	 */
	public $show_opacity;

	/**
	 * Enqueue scripts and styles.
	 *
	 * Ideally these would get registered and given proper paths before this control object
	 * gets initialized, then we could simply enqueue them here, but for completeness as a
	 * stand alone class we'll register and enqueue them here.
	 */
	public function enqueue() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script(
			'alpha-color-picker',
			get_template_directory_uri() . '/assets/js/admin/alpha-color-picker' . $suffix . '.js',
			array( 'jquery', 'wp-color-picker' ),
			'1.0.0',
			true
		);
		wp_enqueue_style(
			'alpha-color-picker',
			get_template_directory_uri() . '/assets/css/admin/alpha-color-picker.css',
			array( 'wp-color-picker' ),
			'1.0.0'
		);
	}
	
	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['defaultValue'] = $this->setting->default;
	}

	/**
	 * Render the control.
	 */
	public function render_content() {}
	
	/**
	 * Render the content template.
	 */
	public function content_template() {
		// Process the palette.
		if ( is_array( $this->palette ) ) {
			$palette = implode( '|', $this->palette );
		} else {
			// Default to true.
			$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
		}

		// Support passing show_opacity as string or boolean. Default to true.
		$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';

		// Begin the output. ?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<div class="customize-control-content">
			<label><span class="screen-reader-text">{{{ data.label }}}</span>
			<input 
				class="alpha-color-control" 
				type="text" 
				data-show-opacity="<?php echo wp_kses_data( $show_opacity ); ?>" 
				data-palette="<?php echo esc_attr( $palette ); ?>" 
				data-default-color="{{ data.defaultValue }}" 
				<?php $this->link(); ?>  
			/>
			</label>
		</div>
		<?php
	}
}
