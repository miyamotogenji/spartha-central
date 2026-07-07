<?php

return [
    'whatsapp' => [
        'url'          => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v19.0'),
        'token'        => env('WHATSAPP_ACCESS_TOKEN'),
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN', 'asoiinfo_webhook_verify_2026'),
        'phone_1'      => env('WHATSAPP_PHONE_NUMBER_ID_1'),
        'phone_2'      => env('WHATSAPP_PHONE_NUMBER_ID_2'),
    ],

    'meta' => [
        'app_id'                    => env('META_APP_ID', env('FACEBOOK_APP_ID')),
        'app_secret'                => env('META_APP_SECRET', env('FACEBOOK_APP_SECRET')),
        'embedded_signup_config_id' => env('META_EMBEDDED_SIGNUP_CONFIG_ID'),
        'graph_url'                 => env('META_GRAPH_URL', 'https://graph.facebook.com'),
        'graph_version'             => env('META_GRAPH_VERSION', 'v21.0'),
    ],

    'spartha' => [
        'url' => env('SPARTHA_API_URL'),
        'key' => env('SPARTHA_API_KEY'),
    ],

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],
];
