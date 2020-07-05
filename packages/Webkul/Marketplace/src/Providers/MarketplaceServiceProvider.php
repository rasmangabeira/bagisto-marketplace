<?php namespace Webkul\Marketplace\Providers;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class MarketplaceServiceProvider extends ServiceProvider{
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        include __DIR__ . '/../Http/routes.php';
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'marketplace');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'marketplace');
        
        $this->app->register(EventServiceProvider::class);
        $this->composeView();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/marketplace.php', 'menu.admin'
        );
        
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.customer'
        );
        
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
    
    
    
    /**
     * Bind the the data to the views
     *
     * @return void
     */
    protected function composeView()
    {
        // in admin when admin edit customer
        view()->composer('marketplace::admin.customer.commission', function ($view) {
            $customer = $view->getData()['customer'];
            $seller = \Webkul\Marketplace\Models\Seller::where('customer_id',$customer->id)->first();
            $view->with('seller', $seller);
        });
        
        // in front-end  product detail
        view()->composer('marketplace::product.detail', function ($view) {
            $product= $view->getData()['product'];
            $productSeller = \Webkul\Marketplace\Models\SellerProduct::where('product_id',$product->id)->first();
            if($productSeller){
                $view->with('is_seller', true);
                $seller = \Webkul\Marketplace\Models\Seller::where('id',$productSeller->seller_id)->first();
                $view->with('seller', $seller);
            }else{
                $view->with('is_seller', false);
            }
        });
    }

}