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
    
    public function getAdminCommission($id) {
        $seller = $this->model->find($id);
        $commission_percentage = $seller->commission_percentage;
        if($commission_percentage){
            return $commission_percentage;
        }elseif(core()->getConfigData('marketplace.settings.general.commission_per_unit')){
            return core()->getConfigData('marketplace.settings.general.commission_per_unit');
        }else{
            return 0;
        }
    }

   
}