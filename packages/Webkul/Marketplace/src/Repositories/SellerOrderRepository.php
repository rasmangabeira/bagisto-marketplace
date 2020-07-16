<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

class SellerOrderRepository extends Repository
{
    public function model(): string {
        return 'Webkul\Marketplace\Contracts\SellerOrder';
    }
    
    
     public function getOrderInfo($id) {
        return $this->find($id,['commission','sub_total','tax_amount','seller_total']);   
    }
    

}