beforeAll( async () => {
	await page.goto( 'http://localhost:8889/' );
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
	const buttons = await page.$$( '.remove_from_cart_button' );

	await buttons.forEach( async ( button ) => {
		await button.click( { force: true } );
	} );
};

const getPriceAmount = async () => {
	return await page.innerText( '.woocommerce-Price-amount' );
};

const getItemCount = async () => {
	return await page.innerText( 'span.count' );
};

describe( 'cart', () => {
	afterEach( async () => {
		await removeCartItems();
	} );

	it( 'initial state', async () => {
		const price = await getPriceAmount();

		expect( price ).toBe( '$0.00' );

		const count = await getItemCount();

		expect( count ).toBe( '0 items' );
	} );

	it( 'add', async () => {
		// Add Beanie
		await page.click( '[data-product_id="14"]' );
		await page.waitForResponse(
			'http://localhost:8889/?wc-ajax=add_to_cart'
		);

		const price = await getPriceAmount();

		expect( price ).toBe( '$18.00' );

		const count = await getItemCount();

		expect( count ).toBe( '1 item' );
	} );
} );
