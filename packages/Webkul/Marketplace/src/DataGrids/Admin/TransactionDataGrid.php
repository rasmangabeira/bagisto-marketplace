<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class TransactionDataGrid extends DataGrid
{
    protected $sortOrder = 'desc';

    protected $index = 'id';

    protected $itemsPerPage = 10;
    
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('seller_invoices as invoice')  
            ->addSelect('invoice.id as id','invoice.seller_name','invoice.transaction_id','invoice.comment','invoice.grand_total');   

     //   $this->addFilter('prod_id','prod.id');
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
            'index'      => 'seller_name',
            'label'      => trans('Seller Name'),
            'type'       => 'string',
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
}