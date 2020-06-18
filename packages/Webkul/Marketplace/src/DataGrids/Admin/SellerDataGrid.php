<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class SellerDataGrid extends DataGrid
{
    protected $index = 'sel_id';

    protected $sortOrder = 'desc';

    protected $itemsPerPage = 10;

  

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'sel_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true
        ]);
        
        $this->addColumn([
            'index'      => 'shop_title',
            'label'      => 'Seller Name',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'email',
            'label'      => 'Seller Email',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Created At',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareQueryBuilder() {
        $queryBuilder = DB::table('sellers as sel')
            ->join('customers as cust', 'cust.id', '=', 'sel.customer_id')
            ->addSelect('sel.id as sel_id','sel.shop_title','cust.email','sel.created_at');   

        $this->addFilter('sel_id', 'sel.id');
        $this->setQueryBuilder($queryBuilder);
    }
    
    public function prepareActions() {
        
        $this->addAction([
            'method' => 'POST',
            'route'  => 'admin.seller.delete',
            'icon'   => 'icon trash-icon',
            'title'  => trans('admin::app.customers.customers.delete-help-title'),
        ]);
    }

}