<?php

return [
	/*
	|--------------------------------------------------------------------------
	| Paystack API configuration
	|--------------------------------------------------------------------------
	|
	| You can publish this configuration to your application's config folder
	| and set PAYSTACK_SECRET and PAYSTACK_BASE_URL in your .env file.
	|
	*/
	'paystack' => [
		// Base url for Paystack API
		'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),

		// Secret key (set in .env as PAYSTACK_SECRET)
		'secret' => env('PAYSTACK_SECRET', ''),

		// Request timeout in seconds
		'timeout' => env('PAYSTACK_TIMEOUT', 10),
	],
];