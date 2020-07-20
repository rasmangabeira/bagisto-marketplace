<?php namespace Webkul\Marketplace\Http\Controllers;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Routing\Controller;
use Webkul\Marketplace\Repositories\SellerOrderRepository;

class OrderController extends Controller{
    
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;
    
    /**
     * OrderRepository object
     *
     * @var SellerOrderRepository
     */
    protected $orderRepository;
    
    public function __construct(SellerOrderRepository $orderRepository)
    {
        $this->middleware('customer');
        $this->_config = request('_config');
        $this->orderRepository = $orderRepository;
    }
    
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }
    
    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $order = $this->orderRepository->findOrFail($id);
        $invoices = $order->invoices;

        $invoicesItems = $invoice_ids = [];
        foreach ($invoices as $key => $invoice) {
           
            $invoicesItems = $invoice->items()->whereHas('order_item', function ($query) use($id){
                return $query->where('seller_order_id', '=', $id);
            })->get();
        }
        if($invoicesItems){
            $invoicesItems = $invoicesItems->groupBy('invoice_id');
        }
        
        $shipmentItems = $order->shipmentItems($id);

        if($shipmentItems){
            $shipmentItems = $shipmentItems->groupBy('shipment_id');
            $shipment_ids = $shipmentItems->pluck('shipment_id')->toArray();
            $shipments = $order->shipments($shipment_ids)->get();
        }
        
        $refundItems = $order->refundItems($id);
        if($refundItems){
            $refund_ids = $refundItems->pluck('refund_id')->toArray();
            $refunds = $order->refunds($refund_ids)->get();
            $refundItems = $refundItems->groupBy('refund_id');
        }
        return view($this->_config['view'], compact('order','invoicesItems','shipmentItems','refundItems','shipments','refunds'));
    }
    
}