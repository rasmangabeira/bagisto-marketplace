<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\SellerProduct as SellerProductContract;


class SellerProduct extends Model implements SellerProductContract
{
    protected $table = 'seller_products';
    protected $fillable = array('seller_id','product_id');
}