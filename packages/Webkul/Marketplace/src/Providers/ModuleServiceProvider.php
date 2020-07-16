<?php

namespace Webkul\Marketplace\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Marketplace\Models\Seller::class,
        \Webkul\Marketplace\Models\SellerProduct::class,
        \Webkul\Marketplace\Models\SellerOrder::class,
        \Webkul\Marketplace\Models\SellerInvoice::class,
        \Webkul\Marketplace\Models\OrderItem::class
    ];
}