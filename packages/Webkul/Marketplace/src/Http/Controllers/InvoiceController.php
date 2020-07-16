<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Webkul\Marketplace\Http\Controllers;
use Illuminate\Routing\Controller;
use Webkul\Sales\Repositories\InvoiceRepository;
use PDF;
/**
 * Description of InvoiceController
 *
 * @author abdullah
 */
class InvoiceController extends Controller{
    
    
    
    
    /**
     * InvoiceRepository object
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;
    
    
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @return void
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    
     /**
     * Print and download the for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $seller_order_id = $_GET['order'];
        $invoice = $this->invoiceRepository->findOrFail($id); 
        $invoiceItems = $invoice->items()->whereHas('order_item', function ($query) use($seller_order_id){
            return $query->where('seller_order_id', '=', $seller_order_id);
        })->get();
        

        $pdf = PDF::loadView('marketplace::invoice.pdf', compact('invoice','invoiceItems'))->setPaper('a4');

        return $pdf->download('invoice-' . $invoice->created_at->format('d-m-Y') . '.pdf');
    }
}
