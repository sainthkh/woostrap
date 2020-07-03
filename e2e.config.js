module.exports = {
	preset: 'jest-playwright-preset',
	testMatch: [ '**/e2e/**/*.[jt]s?(x)' ],
	testPathIgnorePatterns: [ '/node_modules/', '/wordpress/', '/vendor' ],
};
