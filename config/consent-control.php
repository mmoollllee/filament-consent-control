<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration Driver
    |--------------------------------------------------------------------------
    |
    | 'config'   - reads from this file (default)
    | 'eloquent' - reads from a JSON field on your own model
    |
    */
    'driver' => env('CONSENT_CONTROL_DRIVER', 'config'),

    /*
    |--------------------------------------------------------------------------
    | Eloquent Driver Settings
    |--------------------------------------------------------------------------
    |
    | When using driver 'eloquent', specify which model, field, and record
    | holds the consent settings JSON. The field must be cast to 'array'.
    |
    | Use ConsentSettingsForm::make('your_field') in your Filament form
    | and point 'field' to the same column name.
    |
    */
    'eloquent' => [
        'model' => null, // e.g. App\Models\Setting::class
        'field' => 'consent_settings',
        'record_id' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cookie Settings
    |--------------------------------------------------------------------------
    */
    'cookie' => [
        'name' => env('CONSENT_COOKIE_NAME', 'consentcontrol'),
        'days' => env('CONSENT_COOKIE_DAYS', 365),
        'domain' => env('CONSENT_COOKIE_DOMAIN', parse_url(config('app.url', ''), PHP_URL_HOST)),
        'path' => '/',
        'same_site' => 'lax',
        'secure' => env('CONSENT_COOKIE_SECURE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Banner Behavior
    |--------------------------------------------------------------------------
    */
    'banner' => [
        'animated' => true,
        'start_collapsed' => true,
        'position' => 'bottom-right', // bottom-right, bottom-left, bottom-full
    ],

    /*
    |--------------------------------------------------------------------------
    | Consent Categories
    |--------------------------------------------------------------------------
    |
    | Each key is the consent category identifier stored in the cookie.
    | Labels/descriptions can be plain strings or translation keys
    | (e.g. 'consent-control::consent.categories.necessary.label').
    |
    */
    'categories' => [
        'necessary' => [
            'label' => 'Notwendige',
            'description' => 'Stellt die Funktionalität der Website sicher.',
            'checked' => true,
            'disabled' => true,
            'children' => [
                [
                    'label' => 'Seiten-Einstellungen',
                    'description' => 'Speichert Ihre Einstellungen in diesem Banner.',
                ],
            ],
            'scripts' => [],
            'inline_script' => null,
        ],

        'analytics' => [
            'label' => 'Analytics',
            'description' => 'Erlauben Sie dem Website-Betreiber, das Angebot auf dieser Webseite zu bewerten und zu verbessern.',
            'checked' => false,
            'disabled' => false,
            'children' => [
                [
                    'label' => 'Google Tag Manager',
                    'description' => 'Cookie _ga, Speicherdauer 2 Jahre',
                ],
            ],
            'scripts' => [],
            'inline_script' => null,
        ],

        'functional' => [
            'label' => 'Funktionell',
            'description' => 'Funktionen für die Darstellung der Inhalte.',
            'checked' => false,
            'disabled' => false,
            'children' => [],
            'scripts' => [],
            'inline_script' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Links
    |--------------------------------------------------------------------------
    */
    'links' => [
        'privacy' => '/datenschutz/',
        'imprint' => '/impressum/',
    ],

];
