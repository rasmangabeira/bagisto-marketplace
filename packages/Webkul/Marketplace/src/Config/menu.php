<?php

/* 
 * for seller customer(front-end)
 */

return [
	     [
        'key'   => 'marketplace',
        'name'  => 'shop::app.layouts.my-account',
        'route' =>'customer.profile.index',
        'sort'  => 8,
    ],
      [
        'key'   => 'marketplace.account',
        'name'  => 'shop::app.layouts.my-account',
        'route' =>'seller.profile.edit',
        'sort'  => 1,
        ],
     [
        'key'   => 'marketplace.product',
        'name'  => 'products',
        'route' =>'seller.products.index',
        'sort'  => 2,
        ],
    [
        'key'   => 'marketplace.order',
        'name'  => 'orders',
        'route' =>'seller.orders.index',
        'sort'  => 3,
    ],
    [
        'key'   => 'marketplace.transaction',
        'name'  => 'transactions',
        'route' =>'seller.transactions.index',
        'sort'  => 4,
    ],
    [
        'key'   => 'marketplace.dashboard',
        'name'  => 'dashboard',
        'route' =>'marketplaceDashboard',
        'sort'  => 5,
    ]
  
        ];