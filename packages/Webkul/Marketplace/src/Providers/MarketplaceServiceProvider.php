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
        
        Event::listen('customer.registration.after','Webkul\Marketplace\Listeners\Customer@createNewSeller');
        
        Event::listen('catalog.product.create.after','Webkul\Marketplace\Listeners\Customer@createNewSellerProduct');
        
        Event::listen('customer.registration.before','Webkul\Marketplace\Listeners\Customer@beforeRegistration');
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
    }

}