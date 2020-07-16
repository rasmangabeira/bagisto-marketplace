<?php namespace Webkul\Marketplace\Http\Controllers\Admin;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Webkul\Admin\Http\Controllers\Controller;

use Webkul\Marketplace\Repositories\{SellerInvoiceRepository,SellerOrderRepository};

class OrderController extends Controller{
    
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;
    
    /**
     *
     * @var SellerInvoiceRepository
     */
    protected $sellerInvoiceRepository;
    /**
     *
     * @var SellerOrderRepository
     */
    protected $sellerOrderRepository;
    
    
    public function __construct(SellerInvoiceRepository $sellerInvoiceRepository,
            SellerOrderRepository $sellerOrderRepository)
    {
        $this->middleware('admin');
        $this->_config = request('_config');
        $this->sellerInvoiceRepository = $sellerInvoiceRepository;
        $this->sellerOrderRepository = $sellerOrderRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }
    
    public function createInvoice() {
        
//        $invoiceItems = \Webkul\Sales\Models\InvoiceItem::where(['order_item_id'=>44])->get();
        $data = request()->all();
       
//        $sellerInvoice = new \Webkul\Marketplace\Models\SellerInvoice();
//        
//        $total = $invoiceItems->sum('total');
        

        $param = [
            'order_id'=>$data['order_id'],
            'grand_total'=>$data['remaining'],
            'base_grand_total'=>$data['remaining'],
            'transaction_id'=>time() . '-' . $data['seller_id'],
            'comment'=>$data['comment'],
            'seller_id'=>$data['seller_id'],
            'seller_name'=>$data['seller_name'],
            'payment_method'=>'manual'
        ];
        
        $this->sellerInvoiceRepository->create($param);
        $sellerOrder = $this->sellerOrderRepository->find($data['order_id']);
        
        $finalAmmount = $data['remaining']+$sellerOrder->total_paid;
        
        if($finalAmmount == $data['seller_total']){
            $status = 'Already Paid';
        }else{
            $status = 'Invoice Pending';
        }
        $ammount = $data['remaining'];
        $this->sellerOrderRepository
                ->update([
                    'status'=>$status,
                    'total_paid'=>$finalAmmount
                    ],$data['order_id']);
        //}
        
        return redirect()->back();
        
    }
    
}