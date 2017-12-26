<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Don't track requests with the following field values
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
    | - browser_language
    |
    */

    'dont_track' => [
        // Example 1:
        // ['ip' => '127.0.0.1'],
        //
        // Example 2:
        // [
        //     'method' => 'POST',
        //     'is_ajax' => true,
        //      ...
        // ]
        ['ip' => '127.0.0.1'],
    ],
];
