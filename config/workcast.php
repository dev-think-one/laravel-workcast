<?php

return [
    'api_key'               => env('WORKCAST_API_KEY'),
    'auth_url'              => env('WORKCAST_AUTH_URL', 'https://authapi.workcast.com/v1.0/'),
    'http_auth_config'      => [],

    'reporting_url'         => env('WORKCAST_REPORTING_URL', 'https://repapi.workcast.com/v1.0/'),
    'http_reporting_config' => [],

    'registration_url'         => env('WORKCAST_REGISTRATION_URL', 'https://regapi.workcast.com/v1.0/'),
    'http_registration_config' => [],

];
