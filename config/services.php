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

    'supabase' => [
        'url' => env('VITE_SUPABASE_URL'),
        'anon_key' => env('VITE_SUPABASE_ANON_KEY'),
    ],

    'groq' => [
        'key' => env('GROQ_API_KEY'),
    ],

    'openrouter' => [
        'key' => env('OPENROUTER_API_KEY'),
    ],

    'parish' => [
        'office_email' => env('PARISH_OFFICE_EMAIL', 'paroisse.stjoseph.bonoumin@gmail.com'),
        'youtube_channel_id' => env('PARISH_YOUTUBE_CHANNEL_ID', 'https://www.youtube.com/@KEPHATV'),
        'facebook_page_url' => env('PARISH_FACEBOOK_URL', 'https://www.facebook.com/saintjosephartisanrivierabonoumin?locale=fr_FR'),
    ],

    'google' => [
        'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
        'share_email' => env('GOOGLE_SHARE_EMAIL'),
    ],

    'facebook' => [
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'page_id' => env('FACEBOOK_PAGE_ID'),
        'page_access_token' => env('FACEBOOK_PAGE_ACCESS_TOKEN'),
        'webhook_verify_token' => env('FACEBOOK_WEBHOOK_VERIFY_TOKEN'),
    ],

    'paymongo' => [
        'secret_key' => env('PAYMONGO_SECRET_KEY'),
        'public_key' => env('PAYMONGO_PUBLIC_KEY'),
        'webhook_secret' => env('PAYMONGO_WEBHOOK_SECRET'),
    ],

    'analytics' => [
        // GA4 Measurement ID (e.g. G-XXXXXXXXXX). Analytics is only loaded
        // after the visitor explicitly accepts cookies via the consent banner.
        'gtm_id' => env('GTM_ID'),
    ],

];
