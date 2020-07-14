/* eslint-disable @typescript-eslint/no-var-requires */
const base = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
const { exec } = require( 'child_process' );

module.exports = {
	...base,
	entry: {
		front: path.resolve( __dirname, 'theme/assets/js/front.js' ),
	},
	plugins: [
		...base.plugins,
		{
			apply: ( compiler ) => {
				compiler.hooks.afterEmit.tap( 'AfterEmitPlugin', () => {
					exec(
						'node scripts/copy-built-assets.js',
						( err, stdout, stderr ) => {
							if ( stdout ) process.stdout.write( stdout );
							if ( stderr ) process.stderr.write( stderr );
						}
					);
				} );
			},
		},
	],
};
