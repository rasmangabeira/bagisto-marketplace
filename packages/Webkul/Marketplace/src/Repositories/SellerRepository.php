<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

class SellerRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\Seller';
    }

    public function create(array $data)
    {
        return  $this->model->create($data);
    }

   
}