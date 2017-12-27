<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Don't record requests with the following field values
    |--------------------------------------------------------------------------
    |
    | The available fields are:
    | - ip
    | - method
    | - is_ajax
    | - url
    | - user_agent
    | - is_mobile
    | - is_bot
    | - bot
    | - os_family
    | - os
    | - browser_family
    | - browser
    | - is_login_attempt
    | - continent
    | - continent_code
    | - country
    | - country_code
    | - city
    | - browser_language_family
    | - browser_language
    |
    */

    'dont_record' => [
        // Example 1:
        // ['ip' => '127.0.0.1'],
        //
        // Example 2 (all the listed fields fields have to have the specified values):
        // [
        //     'method' => 'GET',
        //     'is_ajax' => true,
        // ]
        //
        // Example 3 (at least one of the fields have to have the specified value):
        // ['method' => 'POST'],
        // ['is_ajax' => true],
    ],

    /*
    |--------------------------------------------------------------------------
    | Don't track requests from users with the following field values
    |--------------------------------------------------------------------------
    |
    | Specify any fields your users model has
    |
    */

    'dont_track_users' => [
        // Examples:
        // ['id' => 1],
        // ['email' => 'admin@example.com'],
        // ['is_admin' => true],
        // ['role_id' => 1]
    ],

    /*
    |--------------------------------------------------------------------------
    | Recording login attempts
    |--------------------------------------------------------------------------
    |
    | Describe what a login attempt would look like
    |
    */

    'login_attempt' => [
        'url' => '/login',
        'method' => 'POST',
        'is_ajax' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | geoip
    |--------------------------------------------------------------------------
    |
    | Should the geoip data be collected?
    |
    */

    'geoip_on' => true,
];
