<?php
namespace Webkul\Marketplace\Models;

class OrderItem extends \Webkul\Sales\Models\OrderItem implements \Webkul\Marketplace\Contracts\OrderItem
{
    public function getGrandTotalAttribute()
    {
        return $this->total + $this->tax_amount - $this->discount_amount;
    }
    
    public function getCommissionAttribute($commission_percent)
    {
        return ($commission_percent/100) * $this->total;
    }
    
    public function getSellerTotalAttribute()
    {
        return $this->grand_total - $this->commission;
    }
   
}