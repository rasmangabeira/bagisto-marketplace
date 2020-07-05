<?php namespace Webkul\Marketplace\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // in admin when admin edit customer
        Event::listen('bagisto.admin.customer.edit.phone.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('marketplace::admin.customer.commission');
        });
        
        // in front-end  product detail
        Event::listen('bagisto.shop.products.view.short_description.after', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('marketplace::product.detail');
        });
        
        Event::listen('customer.registration.after','Webkul\Marketplace\Listeners\Customer@createNewSeller');
        
        Event::listen('catalog.product.create.after','Webkul\Marketplace\Listeners\Customer@createNewSellerProduct');
        
        Event::listen('customer.registration.before','Webkul\Marketplace\Listeners\Customer@beforeRegistration');
        
        Event::listen('customer.update.after','Webkul\Marketplace\Listeners\Customer@afterCustomerUpdate');
        
        
        Event::listen('checkout.order.save.after','Webkul\Marketplace\Listeners\Customer@afterSaveOrder');
        
        
        
//        Event::listen('checkout.order.orderitem.save.after','Webkul\Marketplace\Listeners\Customer@afterSaveOrderItem');
//        
        
        Event::listen('sales.invoice.save.after','Webkul\Marketplace\Listeners\Customer@afterSaveInvoice');
        
        Event::listen('sales.refund.save.after','Webkul\Marketplace\Listeners\Customer@afterSaveRefund');
        
        //
    }
    
}