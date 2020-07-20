<?php namespace Webkul\Marketplace\Http\Middleware;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Closure;

class SellerOrder{
    
    public function handle($request, Closure $next)
    {
        $order_id = $request->route()->parameter('id');
        $id = auth()->guard('customer')->user()->id;
        
        $seller  = \DB::table('sellers')->where('customer_id',$id)->first();
  
        $res  = \DB::table('seller_orders')->select('id')->where([
            'seller_id'=>$seller->id,
            'id'=>$order_id
        ])->first();
        if (!$res) {
            throw new \Exception('i will kill you');
        } 
        return $next($request);
    }
    
}