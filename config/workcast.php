<?php

return [
    'api_key' => env('WORKCAST_API_KEY'),
    'reporting_url' => env('WORKCAST_REPORTING_URL', 'https://repapi.workcast.com/v1.0/'),
    'auth_url' => env('WORKCAST_AUTH_URL', 'https://authapi.workcast.com/v1.0/'),
    'http_reporting_config' => [],
    'http_auth_config' => [],
];
