<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\SellerOrder as SellerOrderContract;


class SellerOrder extends Model implements SellerOrderContract
{
    //sub_total,base_sub_total : without any discount from (tax,....)
    //discount : dont worry i get this from item per product not from all orders
    protected $table = 'seller_orders';
    public $timestamps = false;
    protected $fillable = array('seller_id','order_id','grand_total','base_grand_total','seller_total','base_seller_total','base_commission','commission','discount_percent','discount_amount','base_discount_amount','tax_amount','base_tax_amount','shipping_amount','base_shipping_amount','shipping_discount_amount','base_shipping_discount_amount','total_qty_ordered','total_qty_ordered','sub_total','base_sub_total','sub_total_invoiced','base_sub_total_invoiced','grand_total_invoiced','base_grand_total_invoiced','discount_invoiced','base_discount_invoiced','tax_amount_invoiced','base_tax_amount_invoiced','seller_total_invoiced','base_seller_total_invoiced','status','total_paid','grand_total_refunded','base_grand_total_refunded','sub_total_refunded','base_sub_total_refunded','discount_refunded','base_discount_refunded','tax_amount_refunded','base_tax_amount_refunded','shipping_refunded','base_shipping_refunded','channel_name','customer_email','customer_first_name','customer_last_name','shipping_method','shipping_title','shipping_description','coupon_code','is_gift','customer_company_name','is_guest','created_at','state','commission_percent','base_shipping_invoiced','shipping_invoiced');
    
    
     const status = [
        'Invoice Pending'=>'Invoice Pending',//the order for customer(not seller) pending
        'Already Paid'=>'Already Paid',
        'Pay'=>'Pay'////the order for customer(not seller) invoiced but the seller invoice not complete yet 
    ];
     
    public const ADDRESS_TYPE_SHIPPING = 'order_shipping';
    public const ADDRESS_TYPE_BILLING = 'order_billing';
     
     
    /**
     * Get the order items record associated with the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItemProxy::modelClass(),'seller_order_id')->whereNull('parent_id');
    }
    
    /**
     * Get the payment for the order.
     */
    public function payment()
    {
        return $this->hasOne(\Webkul\Sales\Models\OrderPaymentProxy::modelClass(),'order_id','order_id');
    }
    
     /**
     * Get the biling address for the order.
     */
    public function billing_address()
    {
        return $this->addresses()->where('address_type', self::ADDRESS_TYPE_BILLING);
    }
    public function shipping_address()
    {
        return $this->addresses()->where('address_type', self::ADDRESS_TYPE_SHIPPING);
    }
    public function addresses()
    {
        return $this->hasMany(\Webkul\Sales\Models\OrderAddressProxy::modelClass(),'order_id','order_id');
    }
    
     /**
     * Get billing address for the order.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }
    /**
     * Get shipping address for the order.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }
    
     /**
     * Get the order invoices record associated with the order.
     */
    public function invoices()
    {
        return $this->hasMany(\Webkul\Sales\Models\InvoiceProxy::modelClass(),'order_id','order_id');
    }
    
    
    /**
     * Get the order shipments record associated with the order.
     */
    public function shipments($shipment_ids)
    {
        return $this->hasMany(\Webkul\Sales\Models\ShipmentProxy::modelClass(),'order_id','order_id')->whereIn('id', $shipment_ids);
    }
    
    
    /**
     * Get the order refunds record associated with the order.
     */
    public function refunds($refund_ids)
    {
        return $this->hasMany(\Webkul\Sales\Models\RefundProxy::modelClass(),'order_id','order_id')
                ->whereIn('id', $refund_ids);
    }
    
    
    /**
     * Get the order shipments record associated with the order.
     */
    public function shipmentItems($seller_order_id)
    {
        return (\Webkul\Sales\Models\ShipmentItemProxy::modelClass())
           ::join('order_items as order_item', 'order_item.id', '=', 'shipment_items.order_item_id')
            ->where('order_item.seller_order_id',$seller_order_id)
            ->get();
    }
    
    public function refundItems($seller_order_id)
    {
        return (\Webkul\Sales\Models\RefundItemProxy::modelClass())
           ::join('order_items as order_item', 'order_item.id', '=', 'refund_items.order_item_id')
            ->where('order_item.seller_order_id',$seller_order_id)
            ->get();
    }
}