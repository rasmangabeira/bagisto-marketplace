<?php

namespace Webkul\Marketplace\Http\Controllers;

use Illuminate\Routing\Controller;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;

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
     * AttributeFamilyRepository object
     *
     * @var AttributeFamilyRepository
     */
    protected $attributeFamilyRepository;
    
    public function __construct(ProductRepository $productRepository,AttributeFamilyRepository $attributeFamilyRepository)
    {
        $this->middleware('customer');
        $this->_config = request('_config');
        $this->productRepository = $productRepository;
        $this->attributeFamilyRepository = $attributeFamilyRepository;
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
    
}
