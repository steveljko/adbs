<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | User Preferences Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for all allowed user preferences.
    | Each preference must have a type, default value, and category.
    |
    |   * name:
    |     - type
    |     - default
    |     - allowed_values
    |     - description
    */

    'view' => [
        'view_type' => [
            'type' => 'string',
            'default' => 'card',
            'allowed_values' => ['card', 'list', 'low_performance'],
            'description' => 'Default view type for displaying content on homepage.',
        ],
        'disable_view_switch' => [
            'type' => 'boolean',
            'default' => false,
            'description' => 'Hide the floating view switch on all pages.',
        ],
    ],
];
