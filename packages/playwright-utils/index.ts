const loggedManually: string[] = [];

export async function login(
	username: string,
	password: string
): Promise< void > {
	// You need to login with the form at least once.
	if ( ! loggedManually.includes( username ) ) {
		await page.goto( 'http://localhost:8889/wp-login.php' );
		await page.type( '#user_login', username );
		await page.type( '#user_pass', password );
		await page.click( '#wp-submit' );
		await page.waitForNavigation();

		loggedManually.push( username );
	} else {
		await page.goto( `http://localhost:8889/?login=${ username }` );
	}
}
