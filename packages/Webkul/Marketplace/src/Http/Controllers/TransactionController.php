<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Webkul\Marketplace\Http\Controllers;
use Illuminate\Routing\Controller;
use Webkul\Marketplace\Repositories\{SellerInvoiceRepository,SellerOrderRepository};

/**
 * Description of TransactionController
 *
 * @author abdullah
 */
class TransactionController extends Controller{
    //put your code here
    
    
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;
    
    /**
     * SellerInvoiceRepository object
     *
     * @var SellerInvoiceRepository
     */
    protected $sellerInvoiceRepository;
    
    
    /**
     * SellerOrderRepository object
     *
     * @var SellerOrderRepository
     */
    protected $sellerOrderRepository;
    
    
    public function __construct(SellerInvoiceRepository $sellerInvoiceRepository,SellerOrderRepository $sellerOrderRepository)
    {
        $this->middleware('customer');
        $this->_config = request('_config');
        $this->sellerInvoiceRepository = $sellerInvoiceRepository;
        $this->sellerOrderRepository = $sellerOrderRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $id = auth()->guard('customer')->user()->id;
        $seller  = \DB::table('sellers')->where('customer_id',$id)->first();
        
        $statistics = [
            'total_payout'=>$total_payout = $this->getOrdersSeller($seller->id)->sum('total_paid') - $this->getOrdersSeller($seller->id)->sum('base_grand_total_refunded'),  
            'total_sale' => $total_sale = $this->getOrdersSeller($seller->id)->sum('seller_total_invoiced') - $this->getOrdersSeller($seller->id)->sum('base_grand_total_refunded'),
            'remaining_payout'=>$total_sale - $total_payout
        ];
        return view($this->_config['view'],compact('statistics'));
    }
    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $transaction = $this->sellerInvoiceRepository->findOrFail($id);
        $orderItems = $this->sellerInvoiceRepository->orderItems($transaction->order_id);
        $order= $this->sellerOrderRepository->getOrderInfo($transaction->order_id);
        //dd($this->sellerOrderRepository->getOrderInfo($transaction->order_id));
        return view($this->_config['view'], compact('transaction','orderItems','order'));
    }
    
    private function getOrdersSeller($seller_id)
    {
        return $this->sellerOrderRepository->scopeQuery(function ($query) use ($seller_id) {
            return $query->where('seller_id',$seller_id);
        });
    }
    
}
