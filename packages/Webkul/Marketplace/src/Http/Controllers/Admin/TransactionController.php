<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Webkul\Admin\Http\Controllers\Controller;

/**
 * Description of TransactionController
 *
 * @author abdullah
 */
class TransactionController extends Controller{
    
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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }
}
