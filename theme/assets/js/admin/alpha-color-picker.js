/* global Color */
/**
 * Alpha Color Picker JS
 *
 * This file includes several helper functions and the core control JS.
 */

/**
 * Override the stock color.js toString() method to add support for
 * outputting RGBa or Hex.
 *
 * @param {string} flag if alpha value isn't used, use 'no-alpha'.
 */
Color.prototype.toString = function ( flag ) {
	// If our no-alpha flag has been passed in, output RGBa value with 100% opacity.
	// This is used to set the background color on the opacity slider during color changes.
	if ( 'no-alpha' === flag ) {
		return this.toCSS( 'rgba', '1' ).replace( /\s+/g, '' );
	}

	// If we have a proper opacity value, output RGBa.
	if ( 1 > this._alpha ) {
		return this.toCSS( 'rgba', this._alpha ).replace( /\s+/g, '' );
	}

	if ( this.error ) {
		return '';
	}

	// Proceed with stock color.js hex output.
	let hex = parseInt( this._color, 10 ).toString( 16 );
	if ( hex.length < 6 ) {
		for ( let i = 6 - hex.length - 1; i >= 0; i-- ) {
			hex = '0' + hex;
		}
	}

	return '#' + hex;
};

/**
 * Given an RGBa, RGB, or hex color value, return the alpha channel value.
 *
 * @param {string} color
 */
function getAlphaValueFromColor( color ) {
	let alphaVal;

	// Remove all spaces from the passed in value to help our RGBa regex.
	color = color.replace( / /g, '' );

	if ( color.match( /rgba\(\d+,\d+,\d+,([^)]+)\)/ ) ) {
		alphaVal =
			parseFloat(
				color.match( /rgba\(\d+,\d+,\d+,([^)]+)\)/ )[ 1 ]
			).toFixed( 2 ) * 100;
		alphaVal = parseInt( alphaVal );
	} else {
		alphaVal = 100;
	}

	return alphaVal;
}

/**
 * Force update the alpha value of the color picker object and maybe the alpha slider.
 *
 * @param {string} alpha alpha value
 * @param {Object} $control control jQuery object
 * @param {Object} $alphaSlider alphaSlider jQuery object
 * @param {boolean} updateSlider update slider or not
 */
function updateAlphaValueOnColorControl(
	alpha,
	$control,
	$alphaSlider,
	updateSlider
) {
	const iris = $control.data( 'a8cIris' );
	const colorPicker = $control.data( 'wpWpColorPicker' );

	// Set the alpha value on the Iris object.
	iris._color._alpha = alpha;

	// Store the new color value.
	const color = iris._color.toString();

	// Set the value of the input.
	$control.val( color );

	// Update the background color of the color picker.
	colorPicker.toggler.css( {
		'background-color': color,
	} );

	// Maybe update the alpha slider itself.
	if ( updateSlider ) {
		updateAlphaValueOnAlphaSlider( alpha, $alphaSlider );
	}

	// Update the color value of the color picker object.
	$control.wpColorPicker( 'color', color );
}

/**
 * Update the slider handle position and label.
 *
 * @param {string} alpha alpha value.
 * @param {Object} $alphaSlider jQuery slider object.
 */
function updateAlphaValueOnAlphaSlider( alpha, $alphaSlider ) {
	$alphaSlider.slider( 'value', alpha );
	$alphaSlider.find( '.ui-slider-handle' ).text( alpha.toString() );
}

/**
 * Initialization trigger.
 */
jQuery( document ).ready( function ( $ ) {
	// Loop over each control and transform it into our color picker.
	$( '.alpha-color-control' ).each( function () {
		// Store the control instance.
		const $control = $( this );

		// Get a clean starting value for the option.
		const startingColor = $control.val().replace( /\s+/g, '' );

		// Get some data off the control.
		const showOpacity = $control.attr( 'data-show-opacity' );
		const defaultColor = $control.attr( 'data-default-color' );

		// Create the colorpicker.
		$control.wpColorPicker( {} );

		const $container = $control.parents( '.wp-picker-container:first' );

		// Insert our opacity slider.
		$(
			'<div class="alpha-color-picker-container">' +
				'<div class="min-click-zone click-zone"></div>' +
				'<div class="max-click-zone click-zone"></div>' +
				'<div class="alpha-slider"></div>' +
				'<div class="transparency"></div>' +
				'</div>'
		).appendTo( $container.find( '.wp-picker-holder' ) );

		const $alphaSlider = $container.find( '.alpha-slider' );

		// If starting value is in format RGBa, grab the alpha channel.
		const alphaVal = getAlphaValueFromColor( startingColor );

		// Set up jQuery UI slider() options.
		const sliderOptions = {
			create() {
				const value = $( this ).slider( 'value' );

				// Set up initial values.
				$( this ).find( '.ui-slider-handle' ).text( value );
				$( this )
					.siblings( '.transparency ' )
					.css( 'background-color', startingColor );
			},
			value: alphaVal,
			range: 'max',
			step: 1,
			min: 0,
			max: 100,
			animate: 300,
		};

		// Initialize jQuery UI slider with our options.
		$alphaSlider.slider( sliderOptions );

		// Maybe show the opacity on the handle.
		if ( 'true' === showOpacity ) {
			$alphaSlider.find( '.ui-slider-handle' ).addClass( 'show-opacity' );
		}

		// Bind event handlers for the click zones.
		$container.find( '.min-click-zone' ).on( 'click', function () {
			updateAlphaValueOnColorControl( 0, $control, $alphaSlider, true );
		} );
		$container.find( '.max-click-zone' ).on( 'click', function () {
			updateAlphaValueOnColorControl( 100, $control, $alphaSlider, true );
		} );

		// Bind event handler for clicking on a palette color.
		$container.find( '.iris-palette' ).on( 'click', function () {
			let color = $( this ).css( 'background-color' );
			const alpha = getAlphaValueFromColor( color );

			updateAlphaValueOnAlphaSlider( alpha, $alphaSlider );

			// Sometimes Iris doesn't set a perfect background-color on the palette,
			// for example rgba(20, 80, 100, 0.3) becomes rgba(20, 80, 100, 0.298039).
			// To compensante for this we round the opacity value on RGBa colors here
			// and save it a second time to the color picker object.
			if ( alpha !== 100 ) {
				color = color.replace(
					/[^,]+(?=\))/,
					( alpha / 100 ).toFixed( 2 )
				);
			}

			$control.wpColorPicker( 'color', color );
		} );

		// Bind event handler for clicking on the 'Clear' button.
		$container.find( '.button.wp-picker-clear' ).on( 'click', function () {
			const key = $control.attr( 'data-customize-setting-link' );

			// The #fff color is delibrate here. This sets the color picker to white instead of the
			// defult black, which puts the color picker in a better place to visually represent empty.
			$control.wpColorPicker( 'color', '#ffffff' );

			// Set the actual option value to empty string.
			wp.customize( key, function ( obj ) {
				obj.set( '' );
			} );

			updateAlphaValueOnAlphaSlider( 100, $alphaSlider );
		} );

		// Bind event handler for clicking on the 'Default' button.
		$container
			.find( '.button.wp-picker-default' )
			.on( 'click', function () {
				const alpha = getAlphaValueFromColor( defaultColor );

				updateAlphaValueOnAlphaSlider( alpha, $alphaSlider );
			} );

		// Bind event handler for typing or pasting into the input.
		$control.on( 'input', function () {
			const value = $( this ).val();
			const alpha = getAlphaValueFromColor( value );

			updateAlphaValueOnAlphaSlider( alpha, $alphaSlider );
		} );

		// Update all the things when the slider is interacted with.
		$alphaSlider.slider().on( 'slide', function ( event, ui ) {
			const alpha = parseFloat( ui.value ) / 100.0;

			updateAlphaValueOnColorControl(
				alpha,
				$control,
				$alphaSlider,
				false
			);

			// Change value shown on slider handle.
			$( this ).find( '.ui-slider-handle' ).text( ui.value );
		} );
	} );
} );

( function ( wp ) {
	const api = wp.customize;

	api.controlConstructor[ 'alpha-color' ] = api.Control.extend( {
		ready() {
			const control = this;
			const picker = control.container.find( '.alpha-color-control' );
			let updating = false;

			picker.val( control.setting() ).wpColorPicker( {
				change( event, ui ) {
					updating = true;
					control.setting( picker.wpColorPicker( 'color' ) );

					const transparency = control.container.find(
						'.transparency'
					);
					transparency.css(
						'background-color',
						ui.color.toString( 'no-alpha' )
					);
					updating = false;
				},
			} );

			control.setting.bind( function ( value ) {
				// Bail if the update came from the control itself.
				if ( updating ) {
					return;
				}
				picker.val( value );
				picker.wpColorPicker( 'color', value );
			} );

			// Collapse color picker when hitting Esc instead of collapsing the current section.
			control.container.on( 'keydown', function ( event ) {
				if ( 27 !== event.which ) {
					// Esc.
					return;
				}
				const pickerContainer = control.container.find(
					'.wp-picker-container'
				);
				if ( pickerContainer.hasClass( 'wp-picker-active' ) ) {
					picker.wpColorPicker( 'close' );
					control.container.find( '.wp-color-result' ).focus();
					event.stopPropagation(); // Prevent section from being collapsed.
				}
			} );
		},
	} );
} )( wp );
