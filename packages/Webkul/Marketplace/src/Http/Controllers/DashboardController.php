<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Webkul\Marketplace\Http\Controllers;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Webkul\Marketplace\Repositories\SellerOrderRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Illuminate\Support\Facades\DB;
/**
 * Description of DashboardController
 *
 * @author abdullah
 */
class DashboardController extends Controller{
    //put your code here
    
    /**
     * string object
     *
     * @var \Illuminate\Support\Carbon
     */
    protected $startDate;
    
    
    /**
     * string object
     *
     * @var \Illuminate\Support\Carbon
     */
    protected $endDate;
    
    /**
     * string object
     *
     * @var \Illuminate\Support\Carbon
     */
    protected $lastStartDate;
    
    
    /**
     * string object
     *
     * @var \Illuminate\Support\Carbon
     */
    protected $lastEndDate;
    
    /**
     * OrderRepository object
     *
     * @var \Webkul\Marketplace\Repositories\SellerOrderRepository
     */
    protected $orderRepository;
    
     /**
     * ProductInventoryRepository object
     *
     * @var \Webkul\Product\Repositories\ProductInventoryRepository
     */
    protected $productInventoryRepository;
    
    
       public function __construct(
        SellerOrderRepository $orderRepository,
        ProductInventoryRepository $productInventoryRepository       
    )
    {
        $this->orderRepository = $orderRepository;
        $this->productInventoryRepository = $productInventoryRepository;
    }
    
    
    public function index() {
        
        $this->setStartEndDate();
        $id = auth()->guard('customer')->user()->id;
        $seller  = DB::table('sellers')->where('customer_id',$id)->first();

        $statistics = [
            'stock_threshold' => $this->getStockThreshold($seller->id),
            'customer_with_most_sales' => $this->getCustomerWithMostSales(),
            'total_orders'             =>  [
                'previous' => $previous = $this->previousOrders()->count(),
                'current'  => $current = $this->currentOrders()->count(),
                'progress' => $this->getPercentageChange($previous, $current),
            ],
            'total_sales'              =>  [
                'previous' => $previous = $this->previousOrders()->sum('base_grand_total_invoiced') - $this->previousOrders()->sum('base_grand_total_refunded'),
                'current'  => $current = $this->currentOrders()->sum('base_grand_total_invoiced') - $this->currentOrders()->sum('base_grand_total_refunded'),
                'progress' => $this->getPercentageChange($previous, $current),
            ],
            'avg_sales'                =>  [
                'previous' => $previous = $this->previousOrders()->avg('base_grand_total_invoiced') - $this->previousOrders()->avg('base_grand_total_refunded'),
                'current'  => $current = $this->currentOrders()->avg('base_grand_total_invoiced') - $this->currentOrders()->avg('base_grand_total_refunded'),
                'progress' => $this->getPercentageChange($previous, $current),
            ],
                
        ];
        
       // dd($statistics);
    

             foreach (core()->getTimeInterval($this->startDate, $this->endDate) as $interval) {
                 
        
            $statistics['sale_graph']['label'][] = $interval['start']->format('d M');

            $total = $this->getOrdersBetweenDate($interval['start'], $interval['end'])->sum('base_grand_total_invoiced') - $this->getOrdersBetweenDate($interval['start'], $interval['end'])->sum('base_grand_total_refunded');

            $statistics['sale_graph']['total'][] = $total;
            $statistics['sale_graph']['formated_total'][] = core()->formatBasePrice($total);
        }
        return view('marketplace::dashboard',compact('statistics'))->with(['startDate' => $this->startDate, 'endDate' => $this->endDate]);
    }
    
    /**
     * Sets start and end date
     *
     * @return void
     */
    public function setStartEndDate()
    {
        $this->startDate = request()->get('start')
                           ? Carbon::createFromTimeString(request()->get('start') . " 00:00:01")
                           : Carbon::createFromTimeString(Carbon::now()->subDays(30)->format('Y-m-d') . " 00:00:01");

        $this->endDate = request()->get('end')
                         ? Carbon::createFromTimeString(request()->get('end') . " 23:59:59")
                         : Carbon::now();

        if ($this->endDate > Carbon::now()) {
            $this->endDate = Carbon::now();
        }

        $this->lastStartDate = clone $this->startDate;
        $this->lastEndDate = clone $this->startDate;

        $this->lastStartDate->subDays($this->startDate->diffInDays($this->endDate));
        // $this->lastEndDate->subDays($this->lastStartDate->diffInDays($this->lastEndDate));
    }
    
    
     /**
     * Returns orders between two dates
     *
     * @param  \Illuminate\Support\Carbon  $start
     * @param  \Illuminate\Support\Carbon  $end
     * @return Illuminate\Database\Query\Builder
     */
    private function getOrdersBetweenDate($start, $end)
    {
        return $this->orderRepository->scopeQuery(function ($query) use ($start, $end) {
            return $query->where('seller_orders.created_at', '>=', $start)->where('seller_orders.created_at', '<=', $end);
        });
    }
    
    /**
     * Return stock threshold.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getStockThreshold($seller_id)
    {
        return $this->productInventoryRepository->getModel()
                    ->leftJoin('products', 'product_inventories.product_id', 'products.id')
                    ->leftJoin('seller_products', 'seller_products.product_id', 'products.id') 
                    ->select(DB::raw('SUM(qty) as total_qty'))
                    ->addSelect('product_inventories.product_id')
                    ->where('seller_products.seller_id',$seller_id)
                    ->groupBy('product_id')
                    ->orderBy('total_qty', 'ASC')
                    ->limit(5)
                    ->get();
    }
    
    /**
     * Returns top selling products
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCustomerWithMostSales()
    {
        return $this->orderRepository->getModel()
                    ->select(DB::raw('SUM(base_grand_total) as total_base_grand_total'))
                    ->addSelect(DB::raw('COUNT(id) as total_orders'))
                    ->addSelect('id', 'customer_id', 'customer_email', 'customer_first_name', 'customer_last_name')
                    ->where('seller_orders.created_at', '>=', $this->startDate)
                    ->where('seller_orders.created_at', '<=', $this->endDate)
                    ->groupBy('customer_email')
                    ->orderBy('total_base_grand_total', 'DESC')
                    ->limit(5)
                    ->get();
    }
    
    /**
     * Returns previous order query
     *
     * @return Illuminate\Database\Query\Builder
     */
    private function previousOrders()
    {
        return $this->getOrdersBetweenDate($this->lastStartDate, $this->lastEndDate);
    }

    /**
     * Returns current order query
     *
     * @return Illuminate\Database\Query\Builder
     */
    private function currentOrders()
    {
        return $this->getOrdersBetweenDate($this->startDate, $this->endDate);
    }
     /**
     * Returns percentage difference
     *
     * @param  int  $previous
     * @param  int  $current
     * @return int
     */
    public function getPercentageChange($previous, $current)
    {
        if (! $previous) {
            return $current ? 100 : 0;
        }

        return ($current - $previous) / $previous * 100;
    }
}
