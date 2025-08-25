<?php

return [
    'modules' => [
        'brand',
        'category',
        'order',
        'panel_user',
        'product',
        'slider',
        'stock',
        'unit',
        'user',
        'transfer_money_to_admin',
        'transfer_money_to_user',
        'transfer_money_to_user_to_user',
        'transfer_history',
        'pickup_address',
        'slider'
    ],


    'cashfree' => [
        'base_url'     => env('CASHFREE_BASE_URL', 'https://sandbox.cashfree.com/pg'),
        'client_id'    => env('CASHFREE_CLIENT_ID'),
        'client_secret' => env('CASHFREE_CLIENT_SECRET'),
        'api_version'  => '2025-01-01',
    ],

    'payment_gateway' => [
        'cashfree'
    ]
];
