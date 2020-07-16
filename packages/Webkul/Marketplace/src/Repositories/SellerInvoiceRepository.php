<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

class SellerInvoiceRepository extends Repository
{
    public function model(): string {
        return 'Webkul\Marketplace\Contracts\SellerInvoice';
    }
    
    public function orderItems($seller_order_id) {
        return \Webkul\Marketplace\Models\OrderItem
            ::where('seller_order_id', $seller_order_id)
            ->get();    
//        
//        return $this->model
//           ::join('order_items as order_item', 'order_item.seller_order_id', '=', 'seller_invoices.order_id')
//            ->where('order_item.seller_order_id',$seller_order_id)
//            ->get();
    }
}