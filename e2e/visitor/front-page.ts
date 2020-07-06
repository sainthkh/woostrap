beforeAll( () => {
	page.goto( 'http://localhost:8889/' );
} );

describe( 'navbar links', () => {
	it( 'cart', async () => {
		await page.click( `//a[contains(text(), 'Cart')]` );

		expect( page.url() ).toBe( 'http://localhost:8889/cart/' );
	} );

	it( 'cart icon', async () => {
		await page.click( '.cart-contents' );

		expect( page.url() ).toBe( 'http://localhost:8889/cart/' );
	} );
} );
