<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Webkul\Marketplace\Http\Controllers;

/**
 * Description of ReviewController
 *
 * @author abdullah
 */
class ReviewController {
    //put your code here
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('marketplace::review.index');
    }
}
