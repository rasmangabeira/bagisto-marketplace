<?php namespace Webkul\Marketplace\Http\Controllers\Admin;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Webkul\Admin\Http\Controllers\Controller;

class OrderController extends Controller{
    
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;
    
    public function __construct()
    {
        $this->middleware('admin');
        $this->_config = request('_config');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }
    
}