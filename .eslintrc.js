module.exports = {
	env: {
		browser: true,
		es6: true,
		node: true,
	},
	extends: [
		'eslint:recommended',
		'plugin:react/recommended',
		'plugin:@wordpress/eslint-plugin/recommended',
		'plugin:@typescript-eslint/eslint-recommended',
		'plugin:@typescript-eslint/recommended',
	],
	globals: {
		Atomics: 'readonly',
		SharedArrayBuffer: 'readonly',
		jQuery: 'readonly',
		ajaxurl: 'readonly',
		wp: 'readonly',
		_: 'readonly', // underscorejs
		page: 'readonly',
	},
	parser: '@typescript-eslint/parser',
	parserOptions: {
		ecmaFeatures: {
			jsx: true,
		},
		ecmaVersion: 11,
		sourceType: 'module',
	},
	plugins: [ 'react', '@typescript-eslint' ],
	rules: {
		'@typescript-eslint/no-this-alias': [
			'error',
			{
				allowDestructuring: true,
				allowedNames: [ 'control' ],
			},
		],
	},
};
