<?php

namespace Webkul\Marketplace\Http\Controllers;

use Illuminate\Routing\Controller;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Illuminate\Http\Request;
//use Webkul\Velocity\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\SellerProductRepository;
use Webkul\Velocity\Helpers\Helper;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @var array
     */
    protected $_config;
    
    /**
     * ProductRepository object
     *
     * @var ProductRepository
     */
    protected $productRepository;
    
    
    /**
     * SellerProductRepository object
     *
     * @var SellerProductRepository
     */
    protected $sellerProductRepository;
    
    
    private $velocityHelper;




    /**
     * AttributeFamilyRepository object
     *
     * @var AttributeFamilyRepository
     */
    protected $attributeFamilyRepository;
    
    public function __construct(ProductRepository $productRepository,AttributeFamilyRepository $attributeFamilyRepository,SellerProductRepository $sellerProductRepository,Helper $helper)
    {
        $this->middleware('customer');
        $this->_config = request('_config');
        $this->productRepository = $productRepository;
        $this->attributeFamilyRepository = $attributeFamilyRepository;
        $this->sellerProductRepository = $sellerProductRepository;
        $this->velocityHelper = $helper; 
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
    
     /**
     * Show the product create form
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $items = array();

        foreach (config('product_types') as $item) {
            $item['children'] = [];
            
            array_push($items, $item);
        }

        $types = core()->sortItems($items);
        
        $families = $this->attributeFamilyRepository->all();
        
        $configurableFamily = null;

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamilyRepository->find($familyId);
}
        return view($this->_config['view'], [
            'productTypes' => $types,
            'families'=>$families,
            'configurableFamily'=>$configurableFamily
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Product\Http\Requests\ProductForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $product = $this->productRepository->update($_POST, $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Product']));

        return redirect()->route($this->_config['redirect']);
    }
    
    
      public function products(Request $request,$url) {
        
          $seller = \DB::table('sellers')->select('id')->where('url',$url)->first();
      $slugOrPath = trim($request->getPathInfo(), '/');
    $category = \Webkul\Category\Models\Category::find(1);
        if (preg_match('/^([a-z0-9-]+\/?)+$/', $slugOrPath)) {
            if ($product = $this->productRepository->findBySlug('cat-1')) {

                $customer = auth()->guard('customer')->user();
                $seller_id = $seller->id;
                
                

                return view($this->_config['view'], compact('product', 'customer','category','seller_id'));
            }

        }

        abort(404);
    }
    public function getSellerProducts($seller_id)
    {

        $products = $this->sellerProductRepository->getAll($seller_id);

        $productItems = $products->items();
        $productsArray = $products->toArray();

        if ($productItems) {
            $formattedProducts = [];
            foreach ($productItems as $product) {

                array_push($formattedProducts, $this->velocityHelper->formatProduct($product));
            }

            $productsArray['data'] = $formattedProducts;
        }

        return response()->json($response ?? [
            'products'       => $productsArray,
            'paginationHTML' => $products->appends(request()->input())->links()->toHtml(),
        ]);
    }
    
}
