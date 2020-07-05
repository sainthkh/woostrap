beforeAll( () => {
	page.goto( 'http://localhost:8889/' );
} );

describe( 'navbar links', () => {
	it( 'cart', async () => {
		await page.click( `//a[contains(text(), 'Cart')]` );
		await page.waitForResponse( 'http://localhost:8889/cart' );

		const href = await page.evaluate( () => window.location.href );

		expect( href ).toBe( '/cart' );
	} );
} );
