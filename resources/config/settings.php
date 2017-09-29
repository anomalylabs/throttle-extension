<?php

return [
    'max_attempts'      => [
        'required' => true,
        'env'      => 'THROTTLE_MAX_ATTEMPTS',
        'bind'     => 'anomaly.extension.throttle::throttle.max_attempts',
        'type'     => 'anomaly.field_type.integer',
        'config'   => [
            'min'           => 3,
            'default_value' => 50,
        ],
    ],
    'throttle_interval' => [
        'required' => true,
        'env'      => 'THROTTLE_INTERVAL',
        'bind'     => 'anomaly.extension.throttle::throttle.interval',
        'type'     => 'anomaly.field_type.integer',
        'config'   => [
            'min'           => 1,
            'default_value' => 30,
        ],
    ],
    'lockout_interval'  => [
        'required' => true,
        'env'      => 'THROTTLE_LOCKOUT_INTERVAL',
        'bind'     => 'anomaly.extension.throttle::throttle.lockout_interval',
        'type'     => 'anomaly.field_type.integer',
        'config'   => [
            'min'           => 1,
            'default_value' => 5,
        ],
    ],
];
