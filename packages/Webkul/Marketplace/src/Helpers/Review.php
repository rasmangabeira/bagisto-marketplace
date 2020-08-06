<?php
namespace Webkul\Marketplace\Helpers;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Review
 *
 * @author abdullah
 */
class Review {
    
    public function getAverageRatingForSeller($seller_id) {
        return \DB::table('product_reviews')
                    ->join('seller_products as prod_seller', 'product_reviews.product_id', '=', 'prod_seller.product_id')
                     ->where('prod_seller.seller_id',$seller_id)
                     ->where('product_reviews.status','approved')
                     ->avg('rating');
    }
    
    public function getNumRatingForSeller($seller_id) {
        return \DB::table('product_reviews')
                    ->join('seller_products as prod_seller', 'product_reviews.product_id', '=', 'prod_seller.product_id')
                     ->select('product_reviews.id')
                     ->where('prod_seller.seller_id',$seller_id)
                     ->where('product_reviews.status','approved')
                     ->whereNotNull('rating')
                     ->count('product_reviews.id');
    }
    
    public function getNumCommentForSeller($seller_id) {
        return \DB::table('product_reviews')
                    ->join('seller_products as prod_seller', 'product_reviews.product_id', '=', 'prod_seller.product_id')
                     ->select('product_reviews.id')
                     ->where('prod_seller.seller_id',$seller_id)
                     ->where('product_reviews.status','approved')
                     ->whereNotNull('comment')
                     ->count('product_reviews.id');
    }
}
