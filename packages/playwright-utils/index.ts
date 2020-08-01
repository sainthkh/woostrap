const loggedManually: string[] = [];

export async function login(
	username: string,
	password: string
): Promise< void > {
	// You need to login with the form at least once.
	if ( ! loggedManually.includes( username ) ) {
		await page.goto( 'http://localhost:8889/wp-login.php' );
		await page.waitForSelector( '#user_login' );
		await page.click( '#user_login' );
		await page.type( '#user_login', username );
		await page.type( '#user_pass', password );

		const name = await page.$eval(
			'#user_login',
			( el: HTMLInputElement ) => el.value
		);

		expect( name ).toBe( username );

		await page.click( '#wp-submit' );
		await page.waitForNavigation();

		loggedManually.push( username );
	} else {
		await page.goto( `http://localhost:8889/?login=${ username }` );
	}
}

export async function clickPublish(): Promise< void > {
	await page.click( '#save' );
	await page.waitForResponse(
		'http://localhost:8889/wp-admin/admin-ajax.php'
	);
	await page.click( 'a.customize-controls-close' );
}
