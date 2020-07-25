module.exports = {
	preset: 'jest-playwright-preset',
	testMatch: [ '**/packages/e2e-tests/src/**/*.[jt]s?(x)' ],
	testPathIgnorePatterns: [
		'/node_modules/',
		'/wordpress/',
		'/vendor',
		'index.d.ts',
	],
	setupFilesAfterEnv: [
		'<rootDir>/packages/e2e-tests/config/setup-test-framework.js',
	],
	transform: {
		'^.+\\.ts$': 'ts-jest',
	},
};
