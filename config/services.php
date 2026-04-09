<?php

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // ★ FIXED: pakai env key, bukan hardcode URL/value langsung
    'google_sheets' => [
        'webhook_url' => env('GOOGLE_SHEETS_WEBHOOK_URL'),
        'api_key'     => env('GOOGLE_SHEETS_API_KEY'),
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id'   => env('TELEGRAM_CHAT_ID'),
    ],

    'fonnte' => [
        'url'    => env('FONNTE_URL', 'https://api.fonnte.com/send'),
        'token'  => env('FONNTE_TOKEN'),
        'target' => env('FONNTE_TARGET'),
    ],

    'notifications' => [
        'admin_email' => env('ADMIN_NOTIFICATION_EMAIL', env('MAIL_FROM_ADDRESS')),
    ],

];