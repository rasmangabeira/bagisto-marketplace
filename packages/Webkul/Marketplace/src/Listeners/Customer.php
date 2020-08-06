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
          $data['customer_id'] = $customer->id;
          $data['url'] = request('url');
          $this->sellerRepository->create($data);   
        }
    }
    
    public function createNewSellerProduct($product) {
        if(request('is_seller') == 1){
            $id = auth()->guard('customer')->user()->id;
            $seller  = \DB::table('sellers')->where('customer_id',$id)->first();
            $data['product_id'] = $product->id;
            $data['seller_id'] = $seller->id;
            $this->sellerProductRepository->create($data);  
        }
    }
    
    public function afterCustomerUpdate($customer) {
        if($customer->is_seller && request('commission_percentage')){
            $seller  = \DB::table('sellers')->where('customer_id',$customer->id)
                    ->first();
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
    
    
  
    
    public function afterSaveOrder($order) {
        $items = $order->items()->get();
        list($sellers_item, $ordersItems) = $this->sellerOrderRepository->collectOrderItems($items);

        foreach ($sellers_item as  $seller_item) {
            $this->sellerOrderRepository->prepareOrder($seller_item,$order);
            $id = $this->sellerOrderRepository->insertGetId($seller_item);
            $keys = array_keys($ordersItems, $seller_item['seller_id']);
            if ($keys) {
                \DB::table('order_items')
                        ->whereIn('id', array_values($keys))
                        ->update(['seller_order_id' => $id]);
            }
        }
    }
    
    
    public function afterSaveInvoice($invoice) {
        $sellerOrders = $this->sellerOrderRepository
                ->where(['order_id'=>$invoice->order_id])->get();
        
        foreach($sellerOrders as $sellerOrder){
            $data = $this->sellerOrderRepository
                    ->prepareInvoiceForOrder($sellerOrder, $invoice);
            if(!empty($data)){
                $this->sellerOrderRepository->update($data, $sellerOrder->id);
            }
        }    

    }
    
    public function afterSaveRefund($refund) {
        $sellerOrders = $this->sellerOrderRepository
                ->where(['order_id'=>$refund->order_id])
                ->get();
        
        foreach ($sellerOrders as $sellerOrder) {
            $data = $this->sellerOrderRepository
                    ->prepareRefundForOrder($sellerOrder, $refund);
            if(!empty($data)){
                $this->sellerOrderRepository->update($data, $sellerOrder->id);
                $this->sellerOrderRepository->updateOrderStatus($sellerOrder);
            }
        }
    }
    
    
    public function afterSaveShipment($shipment) {
        $order_id = $shipment->order_id;
        $sellerOrders = $this->sellerOrderRepository
                        ->where(['order_id' => $order_id])->get();
        foreach ($sellerOrders as $sellerOrder) {
            $this->sellerOrderRepository->updateOrderStatus($sellerOrder);
        }
    }
    
    public function afterCancelOrder($order) {
        $sellerOrders = $this->sellerOrderRepository
                        ->where(['order_id' => $order->id])->get();
        foreach ($sellerOrders as $sellerOrder) {
            $this->sellerOrderRepository->updateOrderStatus($sellerOrder);
        }
    }
}