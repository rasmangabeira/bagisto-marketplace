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
        
        $invoiceItems = new \Illuminate\Database\Eloquent\Collection();
        //$invoiceItems = [];
        foreach ($invoices as $key => $invoice) {
           
            $invoiceItems = $invoice->items()->whereHas('order_item', function ($query) use($id){
                return $query->where('seller_order_id', '=', $id);
            })->get();

            
           // $invoiceItems[] = $items;
   
           // $invoiceItems->toBase()->merge($items);
        }
         //dd($invoiceItems->collect());
   
        return view($this->_config['view'], compact('order','invoiceItems'));
    }
    
}