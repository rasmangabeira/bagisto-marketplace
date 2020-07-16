<?php

namespace Webkul\Marketplace\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class TransactionDataGrid extends DataGrid
{
    protected $sortOrder = 'desc';

    protected $index = 'id';

    protected $itemsPerPage = 10;
    
    public function prepareQueryBuilder()
    {
        $cus_id = auth()->guard('customer')->user()->id;
        $seller  = \DB::table('sellers')->where('customer_id',$cus_id)->first();
        $queryBuilder = DB::table('seller_invoices as invoice')  
            ->addSelect('invoice.id as id','invoice.transaction_id','invoice.comment','invoice.grand_total')
            ->where('invoice.seller_id', $seller->id);    
        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'transaction_id',
            'label'      => trans('Transaction Id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'comment',
            'label'      => trans('Comment'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => trans('Grand Total'),
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
            'route'  => 'seller.transactions.view',
            'icon'   => 'icon eye-icon',
        ]);
    }
}