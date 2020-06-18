<?php

namespace Webkul\Marketplace\Http\Controllers;

use Illuminate\Routing\Controller;
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
     * SellerRepository object
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
        $this->middleware('customer');

        $this->_config = request('_config');
        $this->sellerRepository = $sellerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function index($url)
    {
        $seller = $this->sellerRepository->findOneWhere(['url'=>$url]);
     
        return view('marketplace::profile.index',['seller'=>$seller]);
    }

    
    /**
     * edit seller profile from front-end
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $seller = $this->sellerRepository->findOneWhere(['customer_id'=>auth()->guard('customer')->user()->id]);
        return view('marketplace::profile.edit', compact('seller'));
    }
    
    public function update()
    {
        $id = auth()->guard('customer')->user()->id;
        $seller = $this->sellerRepository->findOneWhere(['customer_id'=>$id]);
        $this->validate(request(), [
            'shop_title'            => 'string',
            'url'             => 'string|unique:sellers,url,'.$seller->id
        ]);

        $data = collect(request()->input())->except('_token')->toArray();
        if(request()->hasFile('logo_img')){
            $data['logo'] = current(request()->file('logo_img'))->store('sellers');
        }
    
        if(request()->hasFile('banner_img')){
            $data['banner'] = current(request()->file('banner_img'))->store('sellers');
        }
        if ($seller = $this->sellerRepository->update($data, $seller->id)) {
            Session()->flash('success', trans('marketplace::app.seller.account.profile.edit-success'));
            return redirect()->route($this->_config['redirect']);
        } else {
            Session()->flash('success', trans('marketplace::app.seller.account.profile.edit-fail'));
            return redirect()->back($this->_config['redirect']);
        }
    }

}
