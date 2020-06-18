<?php

namespace Webkul\Marketplace\Listeners;


use Webkul\Marketplace\Repositories\{SellerRepository,SellerProductRepository};
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Customer
{
    protected $sellerRepository;
    
    protected $sellerProductRepository;
    
    use ValidatesRequests;
    
    public function __construct(
        SellerRepository $sellerRepository,
        SellerProductRepository $sellerProductRepository
    )
    {
        $this->sellerRepository = $sellerRepository;
        $this->sellerProductRepository = $sellerProductRepository;
    }
    
    public function createNewSeller($customer)
    {
        if($customer->is_seller){
          $data['id'] = $customer->id;
          $data['customer_id'] = $customer->id;
          $data['url'] = request('url');
          $this->sellerRepository->create($data);   
        }
    }
    
    public function createNewSellerProduct($product) {
        if(request('is_seller') == 1){
            $id = auth()->guard('customer')->user()->id;
            $data['product_id'] = $product->id;
            $data['seller_id'] = $id;
            $this->sellerProductRepository->create($data);  
        }
    }
    
    public function beforeRegistration() {
        return true;
        
        
//        $this->validate(request(), [
//            'url' => 'string|unique:sellers,url'
//        ]);

        
        
        
            $data = array(
                'url' => request('url')

            );
            $rules = array(
                'url' => 'string|unique:sellers,url'
            );

            $validator = Validator::make(['url' => request('ur')], $rules);

            if ($validator->fails()) {
                dd($validator->errors());
                session()->flash('error', $failure->errors()[0]);
                throw new \Illuminate\Validation\ValidationException($validator);
            } else {    
                return true;
            }
        dd(request());
    }
}