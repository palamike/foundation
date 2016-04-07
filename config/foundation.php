<?php
/**
 * Project : packdev
 * User: palagornp
 * Date: 3/15/2016 AD
 * Time: 3:54 PM
 */

return [
    'route' => [
        'prefix' => '',
    ],

    /**
     *  Logging configurations
     */

    'log' => [
        'global' => env('APP_LOG_GLOBAL', true),
        'access' => env('APP_LOG_ACCESS', true),
        'store' => env('APP_LOG_STORE', true),
        'navigation' => env('APP_LOG_NAVIGATION', false),
        'query' => env('APP_LOG_QUERY', false),
        'debug' => env('APP_LOG_DEBUG', false),
    ]
];