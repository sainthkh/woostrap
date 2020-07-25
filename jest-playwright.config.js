const { env } = process;

const getSlowMo = () => {
	if ( env.SLOW_MO ) {
		return env.SLOW_MO;
	}
	return env.HEADLESS === 'true' ? undefined : 50;
};

const getBrowsers = () => {
	if ( env.BROWSERS ) {
		return env.BROWSERS.split( ',' );
	}
	return [ 'chromium' ];
};

module.exports = {
	launchOptions: {
		headless: env.HEADLESS === 'true',
		slowMo: getSlowMo(),
	},
	browsers: getBrowsers(),
};
