<?php

namespace Webkul\Marketplace\Listeners;


use Webkul\Marketplace\Repositories\{SellerRepository,SellerProductRepository,SellerOrderRepository};
use Illuminate\Foundation\Validation\ValidatesRequests;

class Customer
{
    protected $sellerRepository;
    
    protected $sellerProductRepository;
    
    protected $sellerOrderRepository;
    
    use ValidatesRequests;
    
    public function __construct(
        SellerRepository $sellerRepository,
        SellerProductRepository $sellerProductRepository,
        SellerOrderRepository $sellerOrderRepository
    )
    {
        $this->sellerRepository = $sellerRepository;
        $this->sellerProductRepository = $sellerProductRepository;
        $this->sellerOrderRepository = $sellerOrderRepository;
    }
    
    public function createNewSeller($customer)
    {
        if($customer->is_seller){
          $data['id'] = $customer->id;
          $data['customer_id'] = $customer->id;
          $data['url'] = request('url');
          $this->sellerRepository->create($data);   
        }
    }
    
    public function createNewSellerProduct($product) {
        if(request('is_seller') == 1){
            $id = auth()->guard('customer')->user()->id;
            $data['product_id'] = $product->id;
            $data['seller_id'] = $id;
            $this->sellerProductRepository->create($data);  
        }
    }
    
    public function afterCustomerUpdate($customer) {
        if($customer->is_seller && request('commission_percentage')){
            $seller  = \DB::table('sellers')->where('customer_id',$customer->id)->first();
            $data['commission_percentage'] = request('commission_percentage');
            $this->sellerRepository->update($data,$seller->id); 
        }
    }
    
    /**
     * validate url on registration form(for seller)
     */
    public function beforeRegistration() {
        $this->validate(request(), [
            'url' => 'string|unique:sellers'
        ]);
    }
    
    
    public function afterSaveOrderItem($orderItem) {
      //  dd($orderItem);
    }
    
    public function afterSaveOrder($order) {
        $items = $order->items()->get();
        $sellers_item = [];
        $isThereSeller = false;
        $ordersItems = [];
        foreach ($items as $item) {
            $seller_prod = $this->sellerProductRepository->where([
                'product_id'=>$item->product_id
            ])->first();
            
            if($seller_prod){
                $ordersItems[$item->id] = $seller_prod->seller_id;
                $isThereSeller = true;
                $net_total = $item->total;
                
                // check if item has discount or not
                
                // check if item has tax
                if($item->tax_amount != 0){
                    $net_total = $net_total + $item->tax_amount;
                }
                
                if($item->shipping_amount != 0){
                   // $net_total = $net_total + $item->shipping_amount;//TODO
                }
                
                if($item->discount_amount != 0){
                    //dont worry i get this from item per product not from all orders
                    $net_total = $net_total - $item->discount_amount;
                }
                if(isset($sellers_item[$seller_prod->seller_id])){
                    $grand_total  = $sellers_item[$seller_prod->seller_id]['grand_total'] + $net_total;
                    $discount_amount  = $sellers_item[$seller_prod->seller_id]['discount_amount'] + $item->discount_amount; 
                    $tax_amount  = $sellers_item[$seller_prod->seller_id]['tax_amount'] + $item->tax_amount;
                    $total_qty_ordered  = $sellers_item[$seller_prod->seller_id]['total_qty_ordered'] + $item->qty_ordered;
                    
                    $total_item_count  = $sellers_item[$seller_prod->seller_id]['total_item_count'] + 1;
                    $sub_total =  $sellers_item[$seller_prod->seller_id]['sub_total'] + $item->total;
                    $base_sub_total =  $sellers_item[$seller_prod->seller_id]['base_sub_total'] + $item->base_total;    
                }else{
                    $grand_total = $net_total;
                    $discount_amount = $item->discount_amount;
                    $tax_amount = $item->tax_amount;
                    $total_qty_ordered = $item->qty_ordered;
                    $total_item_count = 1;
                    $sub_total = $item->total;
                    $base_sub_total = $item->base_total;
                }
                
                $sellers_item[$seller_prod->seller_id] = [
                    'order_id'=>$item->order_id,
                    'seller_id'=>$seller_prod->seller_id,
                    'grand_total'=>$grand_total,
                    'base_grand_total'=>$grand_total,
                    'discount_percent'=>$item->discount_percent,
                    'discount_amount'=>$discount_amount,
                    'base_discount_amount'=>$discount_amount,
                    'tax_amount'=>$tax_amount,
                    'base_tax_amount'=>$tax_amount,
                    'total_qty_ordered'=>$total_qty_ordered,
                    'total_item_count'=>$total_item_count,
                    'sub_total'=>$sub_total,
                    'base_sub_total'=>$base_sub_total
                ];
            }
        }
        
        if($isThereSeller){
            foreach ($sellers_item as $key=>$seller_item) {
                $admin_commission = $this->sellerRepository->getAdminCommission($seller_item['seller_id']);
                
                $base_commission = ($admin_commission/100) * $seller_item['grand_total'];
                
                $sellers_item[$key]['base_commission'] = $base_commission;
                $sellers_item[$key]['commission'] = $base_commission;
                $sellers_item[$key]['seller_total'] = $seller_item['grand_total'] -$base_commission; 
                $sellers_item[$key]['base_seller_total'] = $seller_item['grand_total'] -$base_commission;
                
                $sellers_item[$key]['status'] = 'Invoice Pending';
                
                $sellers_item[$key]['channel_name'] = $order->channel_name;
                $sellers_item[$key]['customer_email'] = $order->customer_email;
                $sellers_item[$key]['customer_first_name'] = $order->customer_first_name;
                $sellers_item[$key]['customer_last_name'] = $order->customer_last_name;
                $sellers_item[$key]['shipping_method'] = $order->shipping_method;
                $sellers_item[$key]['shipping_title'] = $order->shipping_title;
                $sellers_item[$key]['shipping_description'] = $order->shipping_description;
                $sellers_item[$key]['coupon_code'] = $order->coupon_code;
                $sellers_item[$key]['is_gift'] =$order->is_gift;
                $sellers_item[$key]['customer_company_name'] = $order->customer_company_name;
                $sellers_item[$key]['is_guest'] = $order->is_guest;
                $sellers_item[$key]['state'] = $order->status;
                
                
   
                $id = $this->sellerOrderRepository->insertGetId($sellers_item[$key]);
                $keys = array_keys ($ordersItems,$seller_item['seller_id']);
                if($keys){
                    \DB::table('order_items')
                    ->whereIn('id', array_values($keys))
                    ->update(['seller_order_id' => $id]);
                }
            }
            //insert [multible] => boolean
            //create => object[error]
            //$sellerOrder = $this->sellerOrderRepository->insert($sellers_item);
          
        }
    }
    
    
    public function afterSaveInvoice($invoice) {
      //  return;
        // check if this invoice order belong to seller
        
        $sellerOrders = $this->sellerOrderRepository
                ->where(['order_id'=>$invoice->order_id])->get();
        
        
        
        foreach($sellerOrders as $sellerOrder){
        if($sellerOrder){
            $seller_order_id = $sellerOrder->id;
            $items = $invoice->items()->whereHas('order_item', function ($query) use($seller_order_id){
                return $query->where('seller_order_id', '=', $seller_order_id);
            })->get();
            
            
            $sub_total_invoiced = $discount_amount = $grand_total_invoiced =$tax_amount_invoiced= 0;
            $updateOrder = false;
            foreach ($items as $item) {
                // check if this invoice item product bellong to seller
                $is_seller = $this->sellerProductRepository
                        ->where(['product_id'=>$item->product_id])
                ->first();
                
                if($is_seller){
                    $updateOrder = true;
                    $sub_total_invoiced +=  $item->total;
                    $grand_total_invoiced +=  $item->total;
                    $discount_amount +=  $item->discount_amount;//TODO iam not sure this
                    $tax_amount_invoiced +=  $item->tax_amount;
                    
                    if($item->discount_amount !=0){
                        $grand_total_invoiced = $grand_total_invoiced - 
                                $item->discount_amount;
                    }
                    if($item->tax_amount !=0){
                        $grand_total_invoiced = $grand_total_invoiced + 
                                $item->tax_amount;
                    }
                }
            }
            if($updateOrder){
                $x = $sellerOrder->grand_total_invoiced+$grand_total_invoiced;
                $admin_commission = $this->sellerRepository
                        ->getAdminCommission($sellerOrder->seller_id);
               
                $base_commission = ($admin_commission/100) * $x;
                $this->sellerOrderRepository->update([
                    'sub_total_invoiced'=>$sellerOrder->sub_total_invoiced+$sub_total_invoiced,
                    'base_sub_total_invoiced'=>$sellerOrder->base_sub_total_invoiced+$sub_total_invoiced,
                    'grand_total_invoiced'=>$sellerOrder->grand_total_invoiced+$grand_total_invoiced,
                    'base_grand_total_invoiced'=>$sellerOrder->base_grand_total_invoiced+$grand_total_invoiced,
                    'discount_invoiced'=>$sellerOrder->discount_invoiced+$discount_amount,
                    'base_discount_invoiced'=>$sellerOrder->base_discount_invoiced+$discount_amount,
                    'tax_amount_invoiced'=>$sellerOrder->tax_amount_invoiced+$tax_amount_invoiced,
                    'base_tax_amount_invoiced'=>$sellerOrder->base_tax_amount_invoiced+$tax_amount_invoiced,
                    'seller_total_invoiced'=>$x-$base_commission,
                    'base_seller_total_invoiced'=>$x-$base_commission,
                    'status'=>'Pay'
                     ], $sellerOrder->id
                );
                
            }
        }}
    }
    
    public function afterSaveRefund($refund) {
        $sellerOrders = $this->sellerOrderRepository
                ->where(['order_id'=>$refund->order_id])
                ->get();
        
        foreach ($sellerOrders as $key => $sellerOrder) {
            $seller_order_id = $sellerOrder->id;
            $items = $refund->items()->whereHas('order_item', function ($query) use($seller_order_id){
                return $query->where('seller_order_id', '=', $seller_order_id);
            })->get();
            
            $grand_total = $total = $discount_amount = $tax_amount = 0;
            foreach ($items as $key => $item) {
                $grand_total += $item->total;
                $total += $item->total;
                $discount_amount += $item->discount_amount;
                $tax_amount += $item->tax_amount;
                // check if item has tax
                if($item->tax_amount != 0){
                    $grand_total = $grand_total + $item->tax_amount;
                }
                if($item->discount_amount != 0){
                    $grand_total = $grand_total - $item->discount_amount;
                }
            }
            
            $sellerOrder->sub_total_refunded = $sellerOrder->sub_total_refunded+$total;
            $sellerOrder->base_sub_total_refunded = $sellerOrder->base_sub_total_refunded+$total;
            $sellerOrder->grand_total_refunded = $sellerOrder->grand_total_refunded+$grand_total;
            $sellerOrder->base_grand_total_refunded = $sellerOrder->base_grand_total_refunded+$grand_total;
            
            
            $sellerOrder->discount_refunded = $sellerOrder->discount_refunded+$discount_amount;
            $sellerOrder->base_discount_refunded = $sellerOrder->base_discount_refunded+$discount_amount;
            
            
            $sellerOrder->tax_amount_refunded = $sellerOrder->tax_amount_refunded+$tax_amount;
            $sellerOrder->base_tax_amount_refunded = $sellerOrder->base_tax_amount_refunded+$tax_amount;
            
            $sellerOrder->save();
        }
    }
}