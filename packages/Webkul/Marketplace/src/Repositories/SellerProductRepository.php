<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;

class SellerProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\SellerProduct';
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

   
}