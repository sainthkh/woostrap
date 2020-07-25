beforeAll( async () => {
	await page.goto( 'http://localhost:8889?login=admin' );
} );

beforeEach( async () => {
	await page.goto( 'http://localhost:8889?reset-customize' );
	await page.goto( 'http://localhost:8889/wp-admin/customize.php' );
} );

describe( 'site identity', () => {
	it( 'retina logo', async () => {
		await page.click( '#accordion-section-title_tagline' );
		await page.click( '//button[text()="Select logo"]' );
		await page.click( '//button[text()="Media Library"]' );
		await page.click( 'ul.attachments li' );

		const originalSize = await page.innerText( '.dimensions' );
		const originalHeight = originalSize.match( /\d+/g )[ 1 ];

		await page.click( '//button[text()="Select"]' );
		await page.click( '//button[text()="Skip Cropping"]' );
		await page.click( '#_customize-input-retina_logo' );
		await page.click( '#save' );
		await page.waitForResponse(
			'http://localhost:8889/wp-admin/admin-ajax.php'
		);
		await page.click( 'a.customize-controls-close' );

		const style = await page.getAttribute(
			'a.custom-logo-link img',
			'style'
		);

		const halfHeight = `height: ${ parseInt( originalHeight ) / 2 }px`;

		expect( style ).toContain( halfHeight );
	} );
} );

afterAll( async () => {
	await page.goto( 'http://localhost:8889?reset-customize&logout' );
} );
