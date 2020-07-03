/* eslint-disable @typescript-eslint/no-var-requires */
const fs = require( 'fs-extra' );
const path = require( 'path' );

// copy front.js
const frontDest = path.join( __dirname, '../theme/asset/front.js' );
fs.ensureDirSync( path.dirname( frontDest ) );
fs.copyFileSync( path.join( __dirname, '../build/front.js' ), frontDest );

// copy style.css
const styleDest = path.join( __dirname, '../theme/style.css' );
fs.ensureDirSync( path.dirname( styleDest ) );
fs.copyFileSync( path.join( __dirname, '../build/style.css' ), styleDest );
