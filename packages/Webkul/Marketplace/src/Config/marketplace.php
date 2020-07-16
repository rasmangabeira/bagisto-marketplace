<?php

/* 
   for admin
 */

return [
    
    [
        'key'        => 'marketplace',
        'name'       => 'Marketplace',
        'route'      => 'admin.cms.index',
        'sort'       => 2,
        'icon-class' => 'sales-icon',
    ],
    [
        'key'        => 'marketplace.sellers',
        'name'       => 'Sellers',
        'route'      => 'admin.marketplace.sellers.index',
        'sort'       => 1,
        'icon-class' => '',
    ],
    [
        'key'        => 'marketplace.products',
        'name'       => 'Products',
        'route'      => 'admin.marketplace.products.index',
        'sort'       => 2,
        'icon-class' => '',
    ],
    [
        'key'        => 'marketplace.reviews',
        'name'       => 'Seller Reviews',
        'route'      => 'admin.marketplace.sellers.index',
        'sort'       => 3,
        'icon-class' => '',
    ],
    [
        'key'        => 'marketplace.orders',
        'name'       => 'Orders',
        'route'      => 'admin.marketplace.order.index',
        'sort'       => 4,
        'icon-class' => '',
    ],
    [
        'key'        => 'marketplace.transactions',
        'name'       => 'Transactions',
        'route'      => 'admin.marketplace.transactions.index',
        'sort'       => 5,
        'icon-class' => '',
    ]
];