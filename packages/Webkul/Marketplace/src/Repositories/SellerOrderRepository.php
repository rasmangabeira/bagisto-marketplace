<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Repositories\{SellerProductRepository,SellerRepository};
use Illuminate\Container\Container as App;

class SellerOrderRepository extends Repository
{
    protected $sellerProductRepository;
    protected $sellerRepository;
    
    public function __construct(
        SellerProductRepository $sellerProductRepository,
        SellerRepository $sellerRepository,
        App $app
    )
    {
        $this->sellerProductRepository = $sellerProductRepository;
        $this->sellerRepository = $sellerRepository;

        parent::__construct($app);
    }
    public function model(): string {
        return 'Webkul\Marketplace\Contracts\SellerOrder';
    }
    
    
     public function getOrderInfo($id) {
        return $this->find($id,['commission','sub_total','tax_amount','seller_total','commission_percent','shipping_amount']);   
    }
    

    
    /** TODO :  best code
     * collect Order items to one order
     * @param type $items
     */
    public function collectOrderItems($items) {
        $sellers_item = $ordersItems = [];
        foreach ($items as $item) {
            $seller_prod = $this->sellerProductRepository->where([
                'product_id'=>$item->product_id
            ])->first();
            
            if($seller_prod){
                $seller_id = $seller_prod->seller_id;
                
                $sellers_item[$seller_id] = [
                    'order_id'=>$item->order_id,
                    'seller_id'=>$seller_id,
                    'discount_percent'=>$item->discount_percent,
                    'grand_total'=>0,
                    'base_grand_total'=>0,
                    'discount_amount'=>0,
                    'base_discount_amount'=>0,
                    'tax_amount'=>0,
                    'base_tax_amount'=>0,
                    'total_qty_ordered'=>0,
                    'total_item_count'=>0,
                    'sub_total'=>0,
                    'base_sub_total'=>0
                ];
                
                $ordersItems[$item->id] = $seller_id;
                
                $sellers_item[$seller_id]['grand_total'] += $item->total + $item->tax_amount  - $item->discount_amount;
                $sellers_item[$seller_id]['base_grand_total']+= $item->total + $item->tax_amount  - $item->discount_amount;
                
                $sellers_item[$seller_id]['discount_amount'] +=$item->discount_amount;
                $sellers_item[$seller_id]['base_discount_amount'] +=$item->base_discount_amount;
                $sellers_item[$seller_id]['tax_amount'] +=$item->tax_amount;
                $sellers_item[$seller_id]['base_tax_amount'] +=$item->base_tax_amount;
                $sellers_item[$seller_id]['total_qty_ordered'] +=$item->qty_ordered;
                $sellers_item[$seller_id]['total_item_count'] +=1;
                $sellers_item[$seller_id]['sub_total'] +=$item->total;
                $sellers_item[$seller_id]['base_sub_total'] +=$item->base_total;
            }
        }
        
        return [$sellers_item,$ordersItems];
    }
    
    public function prepareOrder(&$seller_order,$order) {
        $admin_commission = $this->sellerRepository
                ->getAdminCommission($seller_order['seller_id']);
        $seller_order['grand_total'] += $order->shipping_amount - $order->shipping_discount_amount;
        
        $seller_order['base_grand_total'] +=  $order->base_shipping_amount - $order->base_shipping_discount_amount;

        $base_commission = ($admin_commission / 100) * $seller_order['sub_total'];

        $seller_order['base_commission'] = $base_commission;
        $seller_order['commission'] = $base_commission;
        $seller_order['seller_total'] = $seller_order['grand_total'] - $base_commission;
        $seller_order['base_seller_total'] = $seller_order['grand_total'] - $base_commission;

        $seller_order['status'] = 'Invoice Pending';
        $seller_order['state'] = $order->status;
        $seller_order['commission_percent'] = $admin_commission;
        $seller_order['channel_name'] = $order->channel_name;
        $seller_order['shipping_amount'] = $order->shipping_amount;
        $seller_order['base_shipping_amount'] = $order->base_shipping_amount;
        $seller_order['customer_email'] = $order->customer_email;
        $seller_order['customer_first_name'] = $order->customer_first_name;
        $seller_order['customer_last_name'] = $order->customer_last_name;
        $seller_order['customer_id'] = $order->customer_id;
        $seller_order['created_at'] = $order->created_at;
        $seller_order['shipping_method'] = $order->shipping_method;
        $seller_order['shipping_title'] = $order->shipping_title;
        $seller_order['shipping_description'] = $order->shipping_description;
        $seller_order['coupon_code'] = $order->coupon_code;
        $seller_order['is_gift'] = $order->is_gift;
        $seller_order['customer_company_name'] = $order->customer_company_name;
        $seller_order['is_guest'] = $order->is_guest;
        $seller_order['shipping_discount_amount'] = $order->shipping_discount_amount;
        $seller_order['base_shipping_discount_amount'] = $order->base_shipping_discount_amount;
        $seller_order['discount_amount'] +=  $order->shipping_discount_amount;
        $seller_order['base_discount_amount'] += $order->base_shipping_discount_amount;
    }
    
    
        
    

    public function prepareInvoiceForOrder($order,$invoice) {
        $seller_order_id = $order->id;
        $items = $invoice->items()
                        ->whereHas('order_item', function ($query) use($seller_order_id) {
                            return $query->where('seller_order_id', '=', $seller_order_id);
                        })->get();

        if ($items->count() > 0) {
            $sub_total_invoiced = $items->sum('total');
            $discount_amount = $items->sum('discount_amount');
            $tax_amount_invoiced = $items->sum('tax_amount');
            $grand_total_invoiced = $sub_total_invoiced + $tax_amount_invoiced - $discount_amount;


            $total_invoiced = $order->sub_total_invoiced + $sub_total_invoiced;
            $admin_commission = $order->commission_percent;

            $base_commission = ($admin_commission / 100) * $total_invoiced;
            
            
            $grand_invoiced = $order->grand_total_invoiced + $grand_total_invoiced + $invoice->base_shipping_amount;
            

            return [
                'sub_total_invoiced' => $total_invoiced,
                'base_sub_total_invoiced' => $order->base_sub_total_invoiced + $sub_total_invoiced,
                'grand_total_invoiced' => $grand_invoiced,
                'base_grand_total_invoiced' => $order->base_grand_total_invoiced + $grand_total_invoiced + $invoice->base_shipping_amount,
                'discount_invoiced' => $order->discount_invoiced + $discount_amount,
                'base_discount_invoiced' => $order->base_discount_invoiced + $discount_amount,
                'tax_amount_invoiced' => $order->tax_amount_invoiced + $tax_amount_invoiced,
                'base_tax_amount_invoiced' => $order->base_tax_amount_invoiced + $tax_amount_invoiced,
                'shipping_invoiced' => $order->shipping_invoiced + $invoice->shipping_amount,
                'base_shipping_invoiced' => $order->base_shipping_invoiced + $invoice->shipping_amount,
                'seller_total_invoiced' => $grand_invoiced - $base_commission,
                'base_seller_total_invoiced' => $grand_invoiced - $base_commission,
                'status' => 'Pay',
                'state'  => 'processing'
            ];
        }else{
            return [];
        }
    }
    
    
    public function prepareRefundForOrder($order,$refund) {
        $seller_order_id = $order->id;
        $items = $refund->items()
                ->whereHas('order_item', function ($query) use($seller_order_id){
            return $query->where('seller_order_id', '=', $seller_order_id);
        })->get();
        if($items->count() > 0){
            $total = $items->sum('total');
            $discount_amount = $items->sum('discount_amount');
            $tax_amount = $items->sum('tax_amount');
            $grand_total =  $total + $tax_amount- $discount_amount;
            return [
                'sub_total_refunded'=>$order->sub_total_refunded+$total,
                'base_sub_total_refunded'=>$order->base_sub_total_refunded+$total,
                'grand_total_refunded'=>$order->grand_total_refunded+$grand_total+ $refund->shipping_amount,
                'base_grand_total_refunded'=>$order->base_grand_total_refunded+$grand_total+ $refund->base_shipping_amount,
                'discount_refunded'=>$order->discount_refunded+$discount_amount,
                'base_discount_refunded'=>$order->base_discount_refunded+$discount_amount,
                'tax_amount_refunded'=>$order->tax_amount_refunded+$tax_amount,
                'base_tax_amount_refunded'=>$order->base_tax_amount_refunded+$tax_amount,
                'shipping_refunded'=>$order->shipping_refunded+$refund->shipping_amount,
                'base_shipping_refunded'=>$order->base_shipping_refunded+$refund->base_shipping_amount,
                 // as demo version if one qty is refuned no pay to seller[maybe bug]
                'status'=>'Refunded',
                
            ];
            
        }else{
            return [];
        }
    }
    
      /**
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function updateOrderStatus($order)
    {
        $status = 'processing';

        if ($this->isInCompletedState($order)) {
            $status = 'completed';
        }

        if ($this->isInCanceledState($order)) {
            $status = 'canceled';
        } elseif ($this->isInClosedState($order)) {
            $status = 'closed';
        }

        $order->state = $status;
        $order->save();
    }
    
        /**
     * @param    $order
     * @return void
     */
    public function isInCompletedState($order)
    {
        $totalQtyOrdered = $totalQtyInvoiced = $totalQtyShipped = $totalQtyRefunded = $totalQtyCanceled = 0;

        foreach ($order->items()->get() as $item) {
            $totalQtyOrdered += $item->qty_ordered;
            $totalQtyInvoiced += $item->qty_invoiced;

            if (! $item->isStockable()) {
                $totalQtyShipped += $item->qty_ordered;
            } else {
                $totalQtyShipped += $item->qty_shipped;
            }

            $totalQtyRefunded += $item->qty_refunded;
            $totalQtyCanceled += $item->qty_canceled;
        }

        if ($totalQtyOrdered != ($totalQtyRefunded + $totalQtyCanceled)
            && $totalQtyOrdered == $totalQtyInvoiced + $totalQtyCanceled
            && $totalQtyOrdered == $totalQtyShipped + $totalQtyRefunded + $totalQtyCanceled) {
            return true;
        }

        return false;
    }
    
     /**
     * @param  $order
     * @return void
     */
    public function isInCanceledState($order)
    {
        $totalQtyOrdered = $totalQtyCanceled = 0;

        foreach ($order->items()->get() as $item) {
            $totalQtyOrdered += $item->qty_ordered;
            $totalQtyCanceled += $item->qty_canceled;
        }

        return $totalQtyOrdered === $totalQtyCanceled;
    }
    
    /**
     * @param mixed $order
     *
     * @return void
     */
    public function isInClosedState($order)
    {
        $totalQtyOrdered = $totalQtyRefunded = $totalQtyCanceled = 0;

        foreach ($order->items()->get() as $item) {
            $totalQtyOrdered += $item->qty_ordered;
            $totalQtyRefunded += $item->qty_refunded;
            $totalQtyCanceled += $item->qty_canceled;
        }

        return $totalQtyOrdered === $totalQtyRefunded + $totalQtyCanceled;
    }

}