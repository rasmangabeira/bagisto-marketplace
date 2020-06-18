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
        $queryBuilder = DB::table('orders as order')
            ->join('seller_orders as order_seller', 'order.id', '=', 'order_seller.order_id')  
            ->addSelect('order.id as order_id','order.grand_total','order.base_grand_total','order.created_at','order.status','order.customer_first_name')
                
            ->where('order_seller.seller_id', auth()->guard('customer')->user()->id);    
   

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
            'index'      => 'base_grand_total',
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
            'index'      => 'status',
            'label'      => 'Status',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
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
}