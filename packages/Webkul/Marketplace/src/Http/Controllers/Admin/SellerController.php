<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\RefundRepository;


use Webkul\Marketplace\Repositories\SellerRepository;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var \Webkul\Marketplace\Repositories\SellerRepository
     */
    protected $sellerRepository;


    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Marketplace\Repositories\SellerRepository  $sellerRepository
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');
        $this->sellerRepository = $sellerRepository;
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
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seller = $this->sellerRepository->findorFail($id);

        try {
            $this->sellerRepository->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Customer']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Customer']));
        }

        return response()->json(['message' => false], 400);
    }
}
