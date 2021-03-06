<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 Route::group(['middleware' => ['web']], function () {
     
    //Route::view('/customer/register', 'marketplace::customers.signup.index'); 
    // Admin Routes
    Route::prefix('admin')->group(function () {
        
        Route::group(['middleware' => ['admin']], function () {
            Route::get('/sellers', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@index')->defaults('_config', [
                    'view' => 'marketplace::admin.seller.index'
            ])->name('admin.marketplace.sellers.index');
            
            Route::post('sellers/delete/{id}', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@destroy')->name('admin.seller.delete');
            
            
            
             Route::get('/marketplace/products', 'Webkul\Marketplace\Http\Controllers\Admin\ProductController@index')->defaults('_config', [
                    'view' => 'marketplace::admin.product.index'
            ])->name('admin.marketplace.products.index');
             
             
             
             Route::get('/marketplace/orders', 'Webkul\Marketplace\Http\Controllers\Admin\OrderController@index')->defaults('_config', [
                    'view' => 'marketplace::admin.order.index'
            ])->name('admin.marketplace.order.index');
             
             
             Route::post('marketplace/sellers/masssdelete', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@massDestroy')->name('admin.marketplace.sellers.mass-delete');
             
             Route::post('marketplace/sellers/masssupdate', 'Webkul\Marketplace\Http\Controllers\Admin\SellerController@massUpdate')->name('admin.marketplace.sellers.mass-update');
             
             Route::post('marketplace/orders', 'Webkul\Marketplace\Http\Controllers\Admin\OrderController@createInvoice')->name('admin.order.createInvoice');
             
             
             Route::get('/marketplace/transactions', 'Webkul\Marketplace\Http\Controllers\Admin\TransactionController@index')->defaults('_config', [
                    'view' => 'marketplace::admin.transaction.index'
            ])->name('admin.marketplace.transactions.index');
            
            
        });
    });
});

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::prefix('customer')->group(function () {
         //registration form show
        Route::get('register', 'Webkul\Customer\Http\Controllers\RegistrationController@show')->defaults('_config', [
            'view' => 'marketplace::customers.signup.x'
        ])->name('customer.register.index');
       
    });
   
    Route::get('marketplace/account/edit', 'Webkul\Marketplace\Http\Controllers\SellerController@edit')
            ->name('seller.profile.edit');
    
    
    Route::post('marketplace/account/edit', 'Webkul\Marketplace\Http\Controllers\SellerController@update')->defaults('_config', [
                    'redirect' => 'seller.profile.edit'
    ])->name('seller.profile.update');
    
    
    
    Route::get('marketplace/account/catalog/products', 'Webkul\Marketplace\Http\Controllers\ProductController@index')
            ->defaults('_config', [
                    'view' => 'marketplace::product.index'
             ])
            ->name('seller.products.index');
    
    
    
    
    
    
    Route::get('marketplace/account/catalog/products/create', 'Webkul\Marketplace\Http\Controllers\ProductController@create')
            ->defaults('_config', [
                    'view' => 'marketplace::product.create'
    ])
    ->name('seller.products.create');
    
     Route::post('marketplace/account/catalog/products/create', 'Webkul\Product\Http\Controllers\ProductController@store')->defaults('_config', [
                 
                    'redirect' => 'seller.products.edit'
     ])->name('seller.products.create');
     
     
     Route::get('/marketplace/account/catalog/products/edit/{id}', 'Webkul\Product\Http\Controllers\ProductController@edit')->defaults('_config', [
                    'view' => 'marketplace::product.edit'
     ])->name('seller.products.edit')->middleware(\Webkul\Marketplace\Http\Middleware\SellerProduct::class);
     
     
     Route::put('/marketplace/account/catalog/products/edit/{id}', 'Webkul\Marketplace\Http\Controllers\ProductController@update')->defaults('_config', [
                    'redirect' => 'seller.products.index'
                ])->name('seller.products.update')->middleware(\Webkul\Marketplace\Http\Middleware\SellerProduct::class);
     
     
     

          
     Route::get('/marketplace/account/sales/orders', 'Webkul\Marketplace\Http\Controllers\OrderController@index')->defaults('_config', [
                    'view' => 'marketplace::order.index'
     ])->name('seller.orders.index');
     
     
     
          Route::get('/marketplace/account/sales/orders/view/{id}', 'Webkul\Marketplace\Http\Controllers\OrderController@view')
            ->defaults('_config', [
                    'view' => 'marketplace::order.view'
             ])
            ->name('seller.orders.view')->middleware(\Webkul\Marketplace\Http\Middleware\SellerOrder::class);
     
     
     Route::get('marketplace/seller/profile/{url}', 'Webkul\Marketplace\Http\Controllers\SellerController@index')->name('seller.profile.index')->defaults('_config', [
                    'auth' => false
             ]);
     
     
     
     
      Route::get('/marketplace/account/sales/transactions', 'Webkul\Marketplace\Http\Controllers\TransactionController@index')->defaults('_config', [
                    'view' => 'marketplace::transaction.index'
     ])->name('seller.transactions.index');
      
      
      Route::get('/marketplace/account/sales/transactions/view/{id}', 'Webkul\Marketplace\Http\Controllers\TransactionController@view')->defaults('_config', [
                    'view' => 'marketplace::transaction.view'
     ])->name('seller.transactions.view');
      
      
      Route::get('/marketplace/seller/{url}/products', 'Webkul\Marketplace\Http\Controllers\ProductController@products')->defaults('_config', [
            'view' => 'marketplace::shop.products.index',
            'auth' => false
        ])->name('seller.shop.productOrCategory.index');
      
      
       Route::get('/seller-products/{seller_id}', 'Webkul\Marketplace\Http\Controllers\ProductController@getSellerProducts')->defaults('_config', [
            'auth' => false
        ]);
       
       
       
       Route::get('/marketplace/account/sales/invoices/print/{id}', 'Webkul\Marketplace\Http\Controllers\InvoiceController@print')->name('seller.printinvoice');
       
       
       Route::get('/marketplace/account/dashboard', 'Webkul\Marketplace\Http\Controllers\DashboardController@index')->name('marketplaceDashboard');
       
        Route::post('/marketplace/seller/demoshop/contact', 'Webkul\Marketplace\Http\Controllers\SellerController@contact')->name('seller_contact')
             ->defaults('_config', [
                    'auth' => false
             ]);
        
        
         Route::get('/marketplace/account/reviews', 'Webkul\Marketplace\Http\Controllers\ReviewController@index')
            ->name('seller.review.index');
     
   
});