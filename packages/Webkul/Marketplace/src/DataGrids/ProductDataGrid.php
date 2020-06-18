<?php

namespace Webkul\Marketplace\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class ProductDataGrid extends DataGrid
{
    protected $sortOrder = 'desc';

    protected $index = 'prod_id';

    protected $itemsPerPage = 10;
    
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('products as prod')
            ->join('seller_products as prod_seller', 'prod.id', '=', 'prod_seller.product_id')
            ->join('product_flat as prod_flat', 'prod.id', '=', 'prod_flat.product_id')
            ->leftjoin('product_inventories as prod_inventories', 'prod.id', '=', 'prod_inventories.product_id')    
            ->addSelect('prod.id as prod_id','prod.sku', 'prod_flat.name as product_name', 'prod_flat.price as product_price','prod_inventories.qty as product_qty')
            ->where('prod_seller.seller_id', auth()->guard('customer')->user()->id);    
   

        $this->addFilter('prod_id','prod.id');
        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'prod_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'sku',
            'label'      => trans('sku'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('Product Name'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'product_price',
            'label'      => trans('Product Price'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'product_qty',
            'label'      => trans('qty'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }
}