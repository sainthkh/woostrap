/* eslint-disable @typescript-eslint/no-var-requires */
const fs = require( 'fs-extra' );
const path = require( 'path' );

function copy( name ) {
	const dest = path.join( __dirname, `../theme/asset/${ name }` );
	fs.ensureDirSync( path.dirname( dest ) );
	fs.copyFileSync( path.join( __dirname, `../build/${ name }` ), dest );
	/* eslint-disable-next-line no-console */
	console.log( `copied ${ name }` );
}

const jsFiles = [ 'front.js', 'customizer.js', 'customizer.css' ];
jsFiles.forEach( ( file ) => copy( file ) );

// copy style.css
const styleDest = path.join( __dirname, '../theme/style.css' );
fs.ensureDirSync( path.dirname( styleDest ) );
fs.copyFileSync( path.join( __dirname, '../build/style.css' ), styleDest );
/* eslint-disable-next-line no-console */
console.log( 'copied style.css' );
