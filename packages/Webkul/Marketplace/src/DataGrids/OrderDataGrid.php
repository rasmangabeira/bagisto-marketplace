<?php

namespace Webkul\Marketplace\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class OrderDataGrid extends DataGrid
{
    protected $sortOrder = 'desc';

    protected $index = 'order_id';

    protected $itemsPerPage = 10;
    
    public function prepareQueryBuilder()
    {
        $cus_id = auth()->guard('customer')->user()->id;
        $seller  = \DB::table('sellers')->where('customer_id',$cus_id)->first();
        $queryBuilder = 
                DB::table('seller_orders as order_seller')  
            ->addSelect('order_seller.id as order_id','order_seller.grand_total','order_seller.sub_total','order_seller.created_at','order_seller.state','order_seller.customer_first_name')
                
            ->where('order_seller.seller_id', $seller->id); 
        $this->addFilter('order_id','order.id');
        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'sub_total',
            'label'      => 'Base Total',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => 'Grand Total',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Order Date',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'state',
            'label'      => 'Status',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => true,
            'wrapper'    => function ($value) {
                if ($value->state == 'processing') {
                    return '<span class="badge badge-md badge-success">' . trans('shop::app.customer.account.order.index.processing') . '</span>';
                } elseif ($value->state == 'completed') {
                    return '<span class="badge badge-md badge-success">' . trans('shop::app.customer.account.order.index.completed') . '</span>';
                } elseif ($value->state == "canceled") {
                    return '<span class="badge badge-md badge-danger">' . trans('shop::app.customer.account.order.index.canceled') . '</span>';
                } elseif ($value->state == "closed") {
                    return '<span class="badge badge-md badge-info">' . trans('shop::app.customer.account.order.index.closed') . '</span>';
                } elseif ($value->state == "pending") {
                    return '<span class="badge badge-md badge-warning">' . trans('shop::app.customer.account.order.index.pending') . '</span>';
                } elseif ($value->state == "pending_payment") {
                    return '<span class="badge badge-md badge-warning">' . trans('shop::app.customer.account.order.index.pending-payment') . '</span>';
                } elseif ($value->state == "fraud") {
                    return '<span class="badge badge-md badge-danger">' . trans('shop::app.customer.account.order.index.fraud') . '</span>';
                }
            },
        ]);
        
        $this->addColumn([
            'index'      => 'customer_first_name',
            'label'      => 'Billed To',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }
    
    
     public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'seller.orders.view',
            'icon'   => 'icon eye-icon',
        ]);
    }
}