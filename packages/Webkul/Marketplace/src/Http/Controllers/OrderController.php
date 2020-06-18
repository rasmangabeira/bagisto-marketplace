<?php namespace Webkul\Marketplace\Http\Controllers;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Routing\Controller;

class OrderController extends Controller{
    
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;
    
    public function __construct()
    {
        $this->middleware('customer');
        $this->_config = request('_config');
    }
    
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }
    
}