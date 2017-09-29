<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Interval
    |--------------------------------------------------------------------------
    |
    | If the "Allowed Requests" is reached within the
    | specified number of minutes, lockout the user.
    |
    */

    'interval' => env('THROTTLE_INTERVAL', 30),

    /*
    |--------------------------------------------------------------------------
    | Max Attempts
    |--------------------------------------------------------------------------
    |
    | How many requests are allowed
    | within the "Throttle Interval"?
    |
    */

    'max_attempts' => env('THROTTLE_MAX_ATTEMPTS', 50),

    /*
    |--------------------------------------------------------------------------
    | Lockout Interval
    |--------------------------------------------------------------------------
    |
    | Specify how many minutes a throttled user is locked
    | out before they can continue making requests.
    |
    */

    'lockout_interval' => env('THROTTLE_LOCKOUT_INTERVAL', 5),
];
