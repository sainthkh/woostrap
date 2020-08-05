const $ = jQuery;

$( document ).ready( function () {
	wp.customize.control( 'woostrap_use_overlay', function ( control ) {
		control.setting.bind( function ( value ) {
			const color = wp.customize.control(
				'woostrap_header_overlay_color'
			);

			if ( value === true ) {
				color.activate();
			} else {
				color.deactivate();
			}
		} );
	} );
} );
