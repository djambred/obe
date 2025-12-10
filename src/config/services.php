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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'metabase' => [
        'url' => env('METABASE_URL', 'http://localhost:3000'),
        'username' => env('METABASE_USERNAME', 'admin@metabase.local'),
        'password' => env('METABASE_PASSWORD', 'metabase'),
        'database_id' => env('METABASE_DATABASE_ID', 2),
        'embedding_secret_key' => env('METABASE_EMBEDDING_SECRET_KEY', 'your-secret-key-here'),

        // OBE Mapping Dashboard Configuration
        'queries' => [
            'pl_overview' => env('METABASE_QUERY_PL_OVERVIEW'),
            'cpl_mapping' => env('METABASE_QUERY_CPL_MAPPING'),
            'bk_distribution' => env('METABASE_QUERY_BK_DISTRIBUTION'),
            'cpmk_detail' => env('METABASE_QUERY_CPMK_DETAIL'),
            'mk_overview' => env('METABASE_QUERY_MK_OVERVIEW'),
            'cpmk_cpl_matrix' => env('METABASE_QUERY_CPMK_CPL_MATRIX'),
            'coverage_flow' => env('METABASE_QUERY_COVERAGE_FLOW'),
            'rps_status' => env('METABASE_QUERY_RPS_STATUS'),
            'obe_summary' => env('METABASE_QUERY_OBE_SUMMARY'),
            'sankey_data' => env('METABASE_QUERY_SANKEY_DATA'),
        ],

        'dashboards' => [
            'obe_main' => env('METABASE_DASHBOARD_OBE_MAIN'),
            'obe_detailed' => env('METABASE_DASHBOARD_OBE_DETAILED'),
            'obe_compliance' => env('METABASE_DASHBOARD_OBE_COMPLIANCE'),
        ],
    ],

];
