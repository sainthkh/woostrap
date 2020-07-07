module.exports = {
	preset: 'jest-playwright-preset',
	testMatch: [ '**/packages/e2e-tests/**/*.[jt]s?(x)' ],
	testPathIgnorePatterns: [
		'/node_modules/',
		'/wordpress/',
		'/vendor',
		'index.d.ts',
	],
	transform: {
		'^.+\\.ts$': 'ts-jest',
	},
};
