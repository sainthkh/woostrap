import { login, clickPublish } from '@woostrap/playwright-utils';

beforeAll( async () => {
	await login( 'admin', 'password' );
} );

beforeEach( async () => {
	await page.goto(
		'http://localhost:8889/wp-admin/customize.php?reset-customize'
	);
	await page.click( '#accordion-section-header_image' );
} );

describe( 'header', () => {
	describe( 'navbar', () => {
		it( 'background color', async () => {
			await page.click(
				'#customize-control-woostrap_navbar_background_color button.wp-color-result'
			);
			await page.click( '.iris-square', {
				position: {
					x: 20,
					y: 10,
				},
			} );
			await clickPublish();

			await page.waitForSelector( 'nav.navbar' );

			const style = await page.getAttribute( 'nav.navbar', 'style' );

			expect( style ).toContain( '#f2c66f' );
		} );

		it( 'background color - alpha', async () => {
			await page.click(
				'#customize-control-woostrap_navbar_background_color button.wp-color-result'
			);
			await page.click( '.iris-square', {
				position: {
					x: 20,
					y: 10,
				},
			} );

			// Check if the background color of alpha changes.
			const style0 = await page.getAttribute( '.transparency', 'style' );

			expect( style0 ).toContain( 'rgb(242, 198, 111)' );

			await page.click( '.alpha-slider', {
				position: {
					x: 50,
					y: 5,
				},
			} );

			await clickPublish();

			const style = await page.getAttribute( 'nav.navbar', 'style' );

			expect( style ).toContain( 'rgba(242,198,111,0.24)' );
		} );

		it( 'background color - default button', async () => {
			await page.click(
				'#customize-control-woostrap_navbar_background_color button.wp-color-result'
			);
			await page.click( '.iris-square', {
				position: {
					x: 20,
					y: 10,
				},
			} );
			await page.click( 'input[value="Default"]' );
			await clickPublish();

			await page.waitForSelector( 'nav.navbar' );

			const style = await page.getAttribute( 'nav.navbar', 'style' );

			expect( style ).toContain( '#7952b3' );
		} );

		it( 'navbar style', async () => {
			await page.click(
				'#_customize-input-woostrap_navbar_text_style-radio-dark'
			);
			await clickPublish();

			await page.waitForSelector( 'nav.navbar' );

			const classNames = await page.getAttribute( 'nav.navbar', 'class' );

			expect( classNames ).toContain( 'navbar-light' );
		} );

		it( 'button colors - light', async () => {
			// If light is not clicked, click it.
			const checked = await page.$eval(
				'#_customize-input-woostrap_navbar_text_style-radio-light',
				( el: HTMLInputElement ) => el.checked
			);

			if ( ! checked ) {
				await page.click(
					'#_customize-input-woostrap_navbar_text_style-radio-light'
				);
			}

			await clickPublish( checked );

			await page.waitForSelector( 'nav.navbar' );

			const searchIconColor = await page.$eval(
				'nav.navbar .fa-search',
				( el ) => window.getComputedStyle( el ).color
			);

			expect( searchIconColor ).toBe( 'rgb(255, 255, 255)' );

			await page.click( 'nav.navbar .has-search' );

			const clickedSearchIconColor = await page.$eval(
				'nav.navbar .fa-search',
				( el ) => window.getComputedStyle( el ).color
			);

			expect( clickedSearchIconColor ).toBe( 'rgba(0, 0, 0, 0.9)' );

			const cartIconColor = await page.$eval(
				'nav.navbar .fa-shopping-cart',
				( el ) => window.getComputedStyle( el ).color
			);

			expect( cartIconColor ).toBe( 'rgb(255, 255, 255)' );
		} );

		it( 'button colors - dark', async () => {
			// If dark is not clicked, click it.
			const checked = await page.$eval(
				'#_customize-input-woostrap_navbar_text_style-radio-dark',
				( el: HTMLInputElement ) => el.checked
			);

			if ( ! checked ) {
				await page.click(
					'#_customize-input-woostrap_navbar_text_style-radio-dark'
				);
			}

			await clickPublish( checked );

			await page.waitForSelector( 'nav.navbar' );

			const searchIconColor = await page.$eval(
				'nav.navbar .fa-search',
				( el ) => window.getComputedStyle( el ).color
			);

			expect( searchIconColor ).toBe( 'rgba(0, 0, 0, 0.9)' );

			await page.click( 'nav.navbar .has-search' );

			const clickedSearchIconColor = await page.$eval(
				'nav.navbar .fa-search',
				( el ) => window.getComputedStyle( el ).color
			);

			expect( clickedSearchIconColor ).toBe( 'rgba(0, 0, 0, 0.9)' );

			const cartIconColor = await page.$eval(
				'nav.navbar .fa-shopping-cart',
				( el ) => window.getComputedStyle( el ).color
			);

			expect( cartIconColor ).toBe( 'rgba(0, 0, 0, 0.9)' );
		} );
	} );
} );

afterAll( async () => {
	await page.goto( 'http://localhost:8889?reset-customize&logout' );
} );
