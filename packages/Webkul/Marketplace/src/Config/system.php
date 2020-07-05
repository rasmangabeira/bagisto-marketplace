<?php

// admin => configuration menu
return [
    [
        'key'  => 'marketplace',
        'name' => 'Marketplace',
        'sort' => 1,
    ],
    [
        'key'  => 'marketplace.settings',
        'name' => 'settings',
        'sort' => 1,
    ],
    [
        'key'    => 'marketplace.settings.general',
        'name'   => 'General',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'commission_per_unit',
                'title'         => 'Commission Per Unit (In Percentage)',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false
            ]
        ],
    ]
];