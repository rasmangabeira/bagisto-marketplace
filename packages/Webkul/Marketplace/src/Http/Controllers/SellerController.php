<?php

namespace Webkul\Marketplace\Http\Controllers;

use Illuminate\Routing\Controller;
use Webkul\Marketplace\Repositories\SellerRepository;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Mail;

class SellerController extends Controller
{
    use ValidatesRequests;
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
        $this->_config = request('_config');
        if(isset($this->_config['auth']) && $this->_config['auth'] === false){
            
        }else{
            $this->middleware('customer');
        }
        
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
        $count = \DB::table('seller_products')
                     ->select(\DB::raw('count(*) as count'))
                     ->where('seller_id',$seller->id)
                     ->first();
        
        $reviewHelper = app('Webkul\Marketplace\Helpers\Review');
        $averageRatingForSeller = $reviewHelper->getAverageRatingForSeller($seller->id);
        
        $numRatingForSeller = $reviewHelper->getNumRatingForSeller($seller->id);
        $numCommentForSeller = $reviewHelper->getNumCommentForSeller($seller->id);

        return view('marketplace::profile.index',['seller'=>$seller,'productCount'=>$count->count,'averageRatingForSeller'=>$averageRatingForSeller,'numRatingForSeller'=>$numRatingForSeller,'numCommentForSeller'=>$numCommentForSeller]);
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
    
    public function contact() {
        Mail::queue(new \Webkul\Marketplace\Mail\ContactSellerEmail([]));
    }
    
    }
