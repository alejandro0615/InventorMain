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
    'factus' => [
        'url' => env('FACTUS_URL'),
        'api_version' => env('FACTUS_API_VERSION', 'v1'),
        'client_id' => env('FACTUS_CLIENT_ID'),
        'client_secret' => env('FACTUS_CLIENT_SECRET'),
        'username' => env('FACTUS_USERNAME'),
        'password' => env('FACTUS_PASSWORD'),
        'numbering_range_id' => env('FACTUS_NUMBERING_RANGE_ID'),
        'reference_ids' => [
            'customer_identification_document_id' => env('FACTUS_CUSTOMER_IDENTIFICATION_DOCUMENT_ID', 6),
            'customer_legal_organization_id_company' => env('FACTUS_CUSTOMER_LEGAL_ORGANIZATION_ID_COMPANY', 1),
            'customer_legal_organization_id_person' => env('FACTUS_CUSTOMER_LEGAL_ORGANIZATION_ID_PERSON', 2),
            'customer_tribute_id' => env('FACTUS_CUSTOMER_TRIBUTE_ID', 18),
            'item_tribute_id' => env('FACTUS_ITEM_TRIBUTE_ID', 1),
            'item_unit_measure_id' => env('FACTUS_ITEM_UNIT_MEASURE_ID', 70),
            'item_standard_code_id' => env('FACTUS_ITEM_STANDARD_CODE_ID', 1),
        ],
    ],

];
