<?php

namespace Webkul\Marketplace\Http\Controllers;

use Illuminate\Routing\Controller;
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
     * AttributeFamilyRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeFamilyRepository
     */
    protected $attributeFamilyRepository;
    
    public function __construct(AttributeFamilyRepository $attributeFamilyRepository)
    {
        $this->middleware('customer');
        $this->_config = request('_config');
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
    
  


}
