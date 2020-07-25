beforeAll( async () => {
	await page.goto( 'http://localhost:8889/' );
} );

describe( 'navbar links', () => {
	it( 'cart icon', async () => {
		await page.click( '.shopping-cart-link' );

		expect( page.url() ).toBe( 'http://localhost:8889/cart/' );
	} );

	it( 'name & logo', async () => {
		await page.goto( 'http://localhost:8889/cart' );
		await page.click( '.site-title a' );

		expect( page.url() ).toBe( 'http://localhost:8889/' );
	} );
} );

const search = async ( term: string ) => {
	await page.fill( '#woocommerce-product-search-field-0', term );
	await page.press( '#woocommerce-product-search-field-0', 'Enter' );
};

describe( 'search', () => {
	it( 'match multiple', async () => {
		await search( 'shirt' );

		expect( page.url() ).toBe(
			'http://localhost:8889/?s=shirt&post_type=product'
		);

		const headerTitle = await page.innerText(
			'h1.woocommerce-products-header__title'
		);

		expect( headerTitle ).toContain( 'shirt' );
	} );

	it( 'match single', async () => {
		await search( 'belt' );

		expect( page.url() ).toBe( 'http://localhost:8889/product/belt/' );
	} );
} );

const removeCartItems = async () => {
	await page.goto( 'http://localhost:8889/?clear-cart' );
};

describe( 'cart', () => {
	afterEach( async () => {
		await removeCartItems();
	} );

	it( 'initial state', async () => {
		const badge = await page.$( '.shopping-cart .badge' );

		expect( badge ).toBe( null );
	} );

	it( 'add an item', async () => {
		// Add Beanie to cart
		await page.click( '[data-product_id="14"]' );

		const badgeCount = await page.innerText( '.shopping-cart .badge' );

		expect( badgeCount ).toBe( '1' );
	} );
} );
