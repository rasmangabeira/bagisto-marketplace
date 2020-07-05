<?php namespace Webkul\Marketplace\Http\Middleware;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Closure;

class SellerProduct{
    
    public function handle($request, Closure $next)
    {
        $product_id = $request->route()->parameter('id');
        $id = auth()->guard('customer')->user()->id;
        
        $seller  = \DB::table('sellers')->where('customer_id',$id)->first();
        
        $prod  = \DB::table('seller_products')->where([
            'seller_id'=>$seller->id,
            'product_id'=>$product_id
        ])->first();
        
        if (!$prod) {
            throw new \Exception('i will kill you');
        } 
        return $next($request);
    }
    
}