<?php

namespace Webkul\Marketplace\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class ReviewDataGrid extends DataGrid
{
    protected $sortOrder = 'desc';

    protected $index = 'id';

    protected $itemsPerPage = 10;
    
    public function prepareQueryBuilder()
    {
        $cus_id = auth()->guard('customer')->user()->id;
        $seller  = \DB::table('sellers')->where('customer_id',$cus_id)->first();
        
        $queryBuilder = 
                DB::table('product_reviews as product_review')
                ->join('seller_products as prod_seller', 'product_review.product_id', '=', 'prod_seller.product_id')
            ->addSelect('product_review.id','product_review.name as customer_name','product_review.rating','product_review.comment','product_review.status')
                ->where('prod_seller.seller_id',$seller->id)
            ; 
      
        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'customer_name',
            'label'      => trans('Customer name'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'rating',
            'label'      => trans('Rating'),
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
            'index'      => 'status',
            'label'      => trans('Status'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
       
    }
    
  
}