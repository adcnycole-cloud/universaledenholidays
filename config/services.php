<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'billplz' => [
        'api_key' => env('BILLPLZ_API_KEY'),
        'collection_id' => env('BILLPLZ_COLLECTION_ID'),
        'x_signature' => env('BILLPLZ_X_SIGNATURE'),
        'sandbox' => env('BILLPLZ_SANDBOX', env('BILLPLZ_SAND', true)),
        'base_url' => env(
            'BILLPLZ_BASE_URL',
            env('BILLPLZ_SANDBOX', env('BILLPLZ_SAND', true))
                ? 'https://www.billplz-sandbox.com'
                : 'https://www.billplz.com'
        ),
        'verify_ssl' => env('BILLPLZ_VERIFY_SSL', true),
    ],

    'google_places' => [
        'api_key' => env('GOOGLE_PLACES_API_KEY'),
        'place_id' => env('GOOGLE_PLACES_PLACE_ID'),
        'place_query' => env('GOOGLE_PLACES_PLACE_QUERY'),
        'reviews_enabled' => env('GOOGLE_PLACES_REVIEWS_ENABLED', false),
        'reviews_limit' => env('GOOGLE_PLACES_REVIEWS_LIMIT', 3),
        'cache_minutes' => env('GOOGLE_PLACES_CACHE_MINUTES', 360),
    ],

];
