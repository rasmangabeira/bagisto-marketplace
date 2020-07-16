<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\SellerInvoice as SellerInvoiceContract;


class SellerInvoice extends Model implements SellerInvoiceContract
{
    protected $table = 'seller_invoices';
    protected $fillable = array('order_id','grand_total','base_grand_total','transaction_id','comment','seller_id','seller_name','payment_method');
    
    
     /**
     * Get the order that belongs to the invoice.
     */
    public function order()
    {
        return $this->belongsTo(SellerOrderProxy::modelClass());
    }
}