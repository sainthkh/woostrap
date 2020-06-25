/* eslint-disable @typescript-eslint/no-var-requires */
const base = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
const ShellPlugin = require( 'webpack-shell-plugin' );

module.exports = {
	...base,
	entry: {
		front: path.resolve( __dirname, 'theme/js/front.js' ),
	},
	plugins: [
		...base.plugins,
		new ShellPlugin( {
			onBuildEnd: [ 'node scripts/copy-built-assets.js' ],
		} ),
	],
};
