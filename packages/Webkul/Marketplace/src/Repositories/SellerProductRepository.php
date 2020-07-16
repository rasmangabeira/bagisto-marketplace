<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Illuminate\Container\Container as App;
use Webkul\Product\Models\ProductAttributeValue;

class SellerProductRepository extends Repository
{
    
    /**
     * AttributeRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;
    
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        App $app
    )
    {
        $this->attributeRepository = $attributeRepository;

        parent::__construct($app);
    }
    
    
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Marketplace\Contracts\SellerProduct';
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
    
     /**
     * @param  int  $sellerId
     * @return \Illuminate\Support\Collection
     */
    public function getAll($sellerId = null)
    {
        $params = request()->input();

        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) use($params, $sellerId) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $qb = $query->distinct()
                        ->addSelect('product_flat.*')
                        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                        ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                        ->join('seller_products','seller_products.product_id','=','products.id')
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->whereNotNull('product_flat.url_key');

            if ($sellerId) {
                $qb->where('seller_products.seller_id', $sellerId);
            }

            if (is_null(request()->input('status'))) {
                $qb->where('product_flat.status', 1);
            }

            if (is_null(request()->input('visible_individually'))) {
                $qb->where('product_flat.visible_individually', 1);
            }

            $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                    ->where('flat_variants.channel', $channel)
                    ->where('flat_variants.locale', $locale);
            });

            if (isset($params['search']))
                $qb->where('product_flat.name', 'like', '%' . urldecode($params['search']) . '%');

            if (isset($params['sort'])) {
                $attribute = $this->attributeRepository->findOneByField('code', $params['sort']);

                if ($attribute) {
                    if ($params['sort'] == 'price') {
                        if ($attribute->code == 'price') {
                            $qb->orderBy('min_price', $params['order']);
                        } else {
                            $qb->orderBy($attribute->code, $params['order']);
                        }
                    } else {
                        $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                    }
                }
            }

            $qb = $qb->leftJoin('products as variants', 'products.id', '=', 'variants.parent_id');

            $qb = $qb->where(function($query1) use($qb) {
                $aliases = [
                    'products' => 'filter_',
                    'variants' => 'variant_filter_',
                ];

                foreach($aliases as $table => $alias) {
                    $query1 = $query1->orWhere(function($query2) use ($qb, $table, $alias) {

                        foreach ($this->attributeRepository->getProductDefaultAttributes(array_keys(request()->input())) as $code => $attribute) {
                            $aliasTemp = $alias . $attribute->code;

                            $qb = $qb->leftJoin('product_attribute_values as ' . $aliasTemp, $table . '.id', '=', $aliasTemp . '.product_id');

                            $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                            $temp = explode(',', request()->get($attribute->code));

                            if ($attribute->type != 'price') {
                                $query2 = $query2->where($aliasTemp . '.attribute_id', $attribute->id);

                                $query2 = $query2->where(function($query3) use($aliasTemp, $column, $temp) {
                                    foreach($temp as $code => $filterValue) {
                                        if (! is_numeric($filterValue)) {
                                            continue;
                                        }

                                        $columns = $aliasTemp . '.' . $column;
                                        $query3 = $query3->orwhereRaw("find_in_set($filterValue, $columns)");
                                    }
                                });
                            } else {
                                $query2->where('product_flat.min_price', '>=', core()->convertToBasePrice(current($temp)))
                                        ->where('product_flat.min_price', '<=', core()->convertToBasePrice(end($temp)));
                            }
                        }
                    });
                }
            });

            return $qb->groupBy('product_flat.id');
        })->paginate(isset($params['limit']) ? $params['limit'] : 9);

        return $results;
    }

   
}