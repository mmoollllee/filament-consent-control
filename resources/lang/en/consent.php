<?php

return [

    'banner' => [
        'title' => 'Cookies & Services',
        'description' => 'This website uses cookies and external services.',
        'settings_button' => 'More Information',
        'reset_button' => 'Delete All Cookies',
        'reset_message' => 'All cookies have been successfully deleted.',
        'close_button' => 'Close',
        'ok_button' => 'OK',
        'all_button' => 'Allow All',
    ],

    'message' => [
        'text' => 'This content is loaded externally from :source.<br />By activating this content, data such as your IP address will be transmitted to the external server. For more information, please refer to our <a href=":privacy_url" title="Read Privacy Policy">Privacy Policy</a>.',
        'button' => 'Load Content',
    ],

    'categories' => [
        'necessary' => [
            'label' => 'Necessary',
            'description' => 'Ensures the functionality of the website.',
            'children' => [
                'settings' => [
                    'label' => 'Site Settings',
                    'description' => 'Stores your settings in this banner.',
                ],
            ],
        ],
        'analytics' => [
            'label' => 'Analytics',
            'description' => 'Allow the website operator to evaluate and improve the offer on this website.',
            'children' => [
                'gtm' => [
                    'label' => 'Google Tag Manager',
                    'description' => 'Cookie _ga, storage duration 2 years',
                ],
            ],
        ],
        'functional' => [
            'label' => 'Functional',
            'description' => 'Features for displaying content.',
        ],
    ],

];
