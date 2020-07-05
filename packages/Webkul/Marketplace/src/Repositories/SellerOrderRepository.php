<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

class SellerOrderRepository extends Repository
{
    public function model(): string {
        return 'Webkul\Marketplace\Contracts\SellerOrder';
    }
    
    

}