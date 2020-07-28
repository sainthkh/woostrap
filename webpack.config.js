/* eslint-disable @typescript-eslint/no-var-requires */
const base = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
const { exec } = require( 'child_process' );

const fontOptions = {
	name: '[name].[ext]',
	outputPath: 'assets/fonts',
	emitFile: false,
};

module.exports = {
	...base,
	entry: {
		front: path.resolve( __dirname, 'theme/assets/js/front.js' ),
		customizer: path.resolve( __dirname, 'theme/assets/js/customizer.js' ),
	},
	module: {
		rules: [
			...base.module.rules,
			{
				test: /\.svg$/,
				loader: 'file-loader?mimetype=image/svg+xml',
				options: fontOptions,
			},
			{
				test: /\.woff(\?v=\d+\.\d+\.\d+)?$/,
				loader: 'file-loader?mimetype=application/font-woff',
				options: fontOptions,
			},
			{
				test: /\.woff2(\?v=\d+\.\d+\.\d+)?$/,
				loader: 'file-loader?mimetype=application/font-woff',
				options: fontOptions,
			},
			{
				test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
				loader: 'file-loader?mimetype=application/octet-stream',
				options: fontOptions,
			},
			{
				test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
				loader: 'file-loader',
				options: fontOptions,
			},
		],
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
