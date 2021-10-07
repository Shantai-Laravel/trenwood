<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lang;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\PropertyCategory;
use App\Models\ProductProperty;
use App\Models\Brand;
use App\Models\PropertyValue;
use App\Models\PropertyGroup;
use App\Models\SubProductProperty;
use App\Models\SubproductCombination;
use App\Models\SubProduct;
use App\Models\UserField;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;
use App\Models\Set;
use App\Models\FrontUser;
use App\Models\Cart;
use App\Models\WishList;
use App\Models\SetProducts;

class ProductsController extends Controller
{
    public static $productList = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function index($product) {}

    /**
     *  get action
     *  Render single product page
     */
    public function getProduct($slug, $productSlug)
    {
        $set = Set::where('alias', $slug)->first();
        $this->_ifExists($set);

        $product = Product::where('alias', $productSlug)->first();
        $this->_ifExists($product);

        $checkProductInSet = SetProducts::where('set_id', $set->id)->where('product_id', $product->id)->first();
        $this->_ifExists($checkProductInSet);

        $collection = $set->collection;
        $anotherSet = $collection->sets->where('alias', '!=', $slug)->first();
        if (view()->exists('front/products/one-item')) {
            return view('front.products.one-item', compact('set', 'product', 'anotherSet'));
        }else{
            echo "view is not found";
        }
    }
    /**
     *  get action
     *  Render all products page
     */
    public function getAllProducts(Request $request) {
        $subcategories = ProductCategory::where('parent_id', 0)->get();

        $filter['categories'] = $request->get('categories') ?? [];
        $filter['collections'] = $request->get('collections') ?? [];
        $filter['properties'] = $request->get('properties') ?? [];
        $filter['price'] = $request->get('price') ?? [];
        $filter['order'] = $request->get('order') ?? [];
        $filter['sort'] = $request->get('sort') ?? [];

        setcookie('filter', serialize($filter), time() + 10000000, '/');

        $properties = $this->getProperties(0);

        $products = $this->_getProductList($filter, 0);
        self::$productList = $products->pluck('id')->toArray();

        $filterSubprods = [];

        setcookie('subprods', serialize($filterSubprods), time() + 10000000, '/');

        $products = $this->getProductsByParams($filter);
        $dependebleProps = SubProductProperty::where('status', 'dependable')->where('show_property', 1)->get();
        $categoryId = 0;

        if ($request->ajax()) {
           $lastItem = "false";
           $url = $products->nextPageUrl();
           $last = $products->lastPage();
           $current = $products->currentPage();

           if (intval($last) == intval($current)) {
               $lastItem = 'true';
           }

           $view = view('front.filters.productToFilter', compact('products', 'url'))->render();
           return json_encode(['html' => $view, 'url' => $url, 'last' => $lastItem]);
        }

        // $seoData = $this->_getSeo($category);

        if (view()->exists('front/products/all-items')) {
            return view('front.products.all-items', compact('filter', 'categories', 'categoryId', 'subcategories', 'products', 'properties', 'dependebleProps'));
        }else{
            echo "view is not found";
        }
    }
    /**
     *  get action
     *  Render collection page
     */
    public function getCollection($slug) {
        $set = Set::where('alias', $slug)->first();
        $this->_ifExists($set);

        session()->forget('subproductsId');

        $collection = $set->collection;
        $anotherSet = $collection->sets->where('alias', '!=', $slug)->first();

        if (view()->exists('front/collections/'.$collection->alias)) {
            return view('front.collections.'.$collection->alias, compact('set', 'collection', 'anotherSet'));
        }else{
            echo "view is not found";
        }
    }
    /**
     *  post action
     *  Change subproduct in select on product page
     */
    public function changeSubProductOneItem() {
        $changedSubproduct = SubProduct::find(request('subproductId'));
        if(count($changedSubproduct) > 0) {
          $data['changedSubproduct'] = view('front.inc.product', compact('changedSubproduct'))->render();
          return json_encode($data);
        } else {
            return response()->json('Something failed', 400);
        }
    }
    /**
     *  post action
     *  Change subproduct in select on collection page
     */
    public function changeSubProduct() {
        $slug = array_values(array_slice(explode('/', url()->previous()), -1))[0];
        $set = Set::where('alias', $slug)->first();
        $this->_ifExists($set);

        $subproduct = SubProduct::find(request('subproductId'));
        if(count($subproduct) > 0) {

            if(session()->get('subproductsId')) {
                foreach (session()->get('subproductsId') as $key => $subproductId) {
                  $subproductsId[$key] = $subproductId;
                }
            }

            $subproductsId[$subproduct->product_id] = $subproduct->id;

            session(['subproductsId' => $subproductsId]);

            $data['subproducts'] = view('front.inc.products', compact('set'))->render();
            return json_encode($data);
        } else {
            return response()->json('Something failed', 400);
        }
    }

    // get SEO data for a page
    private function _getSeo($page){
        $seo['seo_title'] = $page->translationByLanguage($this->lang->id)->first()->meta_title;
        $seo['seo_keywords'] = $page->translationByLanguage($this->lang->id)->first()->meta_keywords;
        $seo['seo_description'] = $page->translationByLanguage($this->lang->id)->first()->meta_description;

        return $seo;
    }
    /**
     *  private method
     *  Check if object exists
     */
    private function _ifExists($object){
        if (is_null($object)) {
            return redirect()->route('404')->send();
        }
    }

    // **************** FILTER ********************** //

    protected function getProperties($category_id)
    {
        $properties = [];
        $category = ProductCategory::where('id', $category_id)->first();

        if (!is_null($category)) {
            $properties = array_merge($properties, $this->getPropertiesList($category->id));
            $category1 = ProductCategory::where('parent_id', $category->id)->pluck('id')->toArray();
            if (count($category1) > 0) {
                $properties = array_merge($properties, $this->getPropertiesListByCats($category1));
                $category2 = ProductCategory::whereIn('parent_id', $category1)->pluck('id')->toArray();
                if (count($category2) > 0) {
                    $properties = array_merge($properties, $this->getPropertiesListByCats($category2));
                    $category3 = ProductCategory::whereIn('parent_id', $category2)->pluck('id')->toArray();
                    if (count($category3) > 0) {
                        $properties = array_merge($properties, $this->getPropertiesList($category3));
                    }
                }
            }
        }else{
            $categoriesAll = ProductCategory::pluck('id')->toArray();
            $properties = array_merge($properties, $this->getPropertiesListByCats($categoriesAll));
        }


        $properties = array_unique($properties);


        return ProductProperty::with('translationByLanguage')
                            ->where('multilingual', 1)
                            ->with('multidata')
                            ->whereIn('id', $properties)
                            ->get();
    }

    protected function getPropertiesList($categoryId)
    {
        $propertiesArr = [];
        $properties = PropertyCategory::where('category_id', $categoryId)->get();
        if (!empty($properties)) {
            foreach ($properties as $key => $property) {
                $propertiesArr[] = $property->property_id;
            }
        }

        return $propertiesArr;
    }

    protected function getPropertiesListByCats($cats)
    {
        $propertiesArr = [];
        $properties = PropertyCategory::whereIn('category_id', $cats)->get();
        if (!empty($properties)) {
            foreach ($properties as $key => $property) {
                $propertiesArr[] = $property->property_id;
            }
        }

        return $propertiesArr;
    }

    public function filter(Request $request)
    {
        if (@$_COOKIE['filter']) {
            $filter = unserialize(@$_COOKIE['filter']);
            if (is_array($filter['categories'])) {
                if (!in_array($request->get('value'), $filter['categories'])) {
                    $filter['categories'][$request->get('value')] = $request->get('value');
                }else{
                    unset($filter['categories'][$request->get('value')]);
                }
            }
            setcookie('filter', serialize($filter), time() + 10000000, '/');
            $filterId = $filter;
        }

        $products = $this->_getProductList($filterId, $request->get('category_id'));
        $subcategories = ProductCategory::where('parent_id', $request->get('category_id'))->get();
        $properties = $this->getProperties($request->get('category_id'));
        $category = ProductCategory::where('id', $request->get('category_id'))->first();

        self::$productList = $products->pluck('id')->toArray();
        $products = $this->getProductsByParams($filter);

        $data['products'] = view('front.filters.productToFilter', compact('products'))->render();
        // $data['filter'] = view('front.filters.categoryFilter', compact('subcategories', 'category', 'filter', 'properties', 'products'))->render();
        $data['url'] = http_build_query($filter, 'myvar_');

        return json_encode($data);
    }

    public function filterProperty(Request $request)
    {
        if (@$_COOKIE['filter']) {
            $filter = unserialize(@$_COOKIE['filter']);
            if (is_array($filter['properties'])) {
                if (array_key_exists($request->get('name'), $filter['properties'])) {
                    if (!in_array($request->get('value'), @$filter['properties'][$request->get('name')])) {
                        $filter['properties'][$request->get('name')][$request->get('value')] = $request->get('value');
                    }else{
                        unset($filter['properties'][$request->get('name')][$request->get('value')]);
                    }
                }else{
                    $filter['properties'][$request->get('name')][$request->get('value')] = $request->get('value');
                }
            }
            setcookie('filter', serialize($filter), time() + 10000000, '/');
            $filterId = $filter;
        }

        $products = $this->_getProductList($filterId, $request->get('category_id'));
        $subcategories = ProductCategory::where('parent_id', $request->get('category_id'))->get();
        $properties = $this->getProperties($request->get('category_id'));
        $category = ProductCategory::where('id', $request->get('category_id'))->first();;

        self::$productList = $products->pluck('id')->toArray();
        $products = $this->getProductsByParams($filter);

        $url = $request->get('url').'?'.http_build_query($filter, 'myvar_');

        $data['products'] = view('front.filters.productToFilter', compact('products', 'url'))->render();
        $data['url'] = http_build_query($filter, 'myvar_');

        return json_encode($data);
    }

    public function filterPrice(Request $request)
    {
        if (@$_COOKIE['filter']) {
            $filter = unserialize(@$_COOKIE['filter']);
            if (is_array($filter['price'])) {
                $filter['price']['from'] = $request->get('from');
                $filter['price']['to'] = $request->get('to');

            }
            setcookie('filter', serialize($filter), time() + 10000000, '/');
            $filterId = $filter;
        }

        $products = $this->_getProductList($filterId, $request->get('category_id'));
        $subcategories = ProductCategory::where('parent_id', $request->get('category_id'))->get();
        $properties = $this->getProperties($request->get('category_id'));
        $category = ProductCategory::where('id', $request->get('category_id'))->first();;

        self::$productList = $products->pluck('id')->toArray();
        $products = $this->getProductsByParams($filter);

        $url = $request->get('url').'?'.http_build_query($filter, 'myvar_');

        $data['products'] = view('front.filters.productToFilter', compact('products', 'url'))->render();
        $data['url'] = http_build_query($filter, 'myvar_');

        return json_encode($data);
    }

    public function filterOrder(Request $request)
    {
        if (@$_COOKIE['filter']) {
            $filter = unserialize(@$_COOKIE['filter']);
                $filter['order']['order'] = $request->get('order');
                $filter['order']['field'] = $request->get('field');
            setcookie('filter', serialize($filter), time() + 10000000, '/');
            $filterId = $filter;
        }
        $products = $this->_getProductList($filterId, $request->get('category_id'));
        $subcategories = ProductCategory::where('parent_id', $request->get('category_id'))->get();
        $properties = $this->getProperties($request->get('category_id'));
        $category = ProductCategory::where('id', $request->get('category_id'))->first();;

        self::$productList = $products->pluck('id')->toArray();
        $products = $this->getProductsByParams($filter);

        $url = $request->get('url').'?'.http_build_query($filter, 'myvar_');

        $data['products'] = view('front.filters.productToFilter', compact('products', 'url'))->render();
        // $data['filter'] = view('front.filters.categoryFilter', compact('subcategories', 'category', 'filter', 'properties', 'products'))->render();
        $data['url'] = http_build_query($filter, 'myvar_');

        return json_encode($data);
    }

    public function getProductsByParams($filter)
    {
        $filter['properties'] = array_filter($filter['properties']);
        $props = [];
        if (is_array($filter['properties'])) {
            foreach ($filter['properties'] as $propId => $values) {
                foreach ($values as $key => $value) {
                    $array = PropertyValue::select('product_id')
                                    ->where('value_id', $value)
                                    ->where('property_id', $propId)
                                    ->whereIn('product_id', self::$productList)
                                    ->pluck('product_id')->toArray();

                    $props = array_merge($props, $array);
                }
                self::$productList = $props;
                $props = [];
            }
        }

         return Product::whereIn('id', self::$productList)
                         ->when(count($filter['order']) > 0, function ($query) use ($filter) {
                                 return $query->orderBy($filter['order']['field'], $filter['order']['order']);
                             })
                             ->orderBy('position', 'asc')->paginate(9);
    }

    // get Products list by filters
    protected function _getProductList($filterId, $catId){

        // dd($filterId['price']);
        $products =   Product::when(count($filterId['categories']) > 0, function ($query) use ($filterId) {
                        $subcats = ProductCategory::whereIn('parent_id', $filterId['categories'])->pluck('id')->toArray();
                        $subcatsLast = ProductCategory::whereIn('parent_id', $subcats)->pluck('id')->toArray();
                        $cats = array_merge($subcats, $filterId['categories'], $subcatsLast);
                        return $query->whereIn('category_id', array_filter($cats));
                    })
                ->when(count($filterId['categories']) == 0, function ($query) use ($catId) {
                        $subcats = ProductCategory::where('parent_id', $catId)->pluck('id')->toArray();
                        $subcatsLast = ProductCategory::whereIn('parent_id', $subcats)->pluck('id')->toArray();
                        $cats = array_merge($subcats,  $subcatsLast, [$catId]);
                        return $query->whereIn('category_id', array_filter($cats));
                    })
                ->when(count($filterId['price']) > 0, function ($query) use ($filterId) {
                        return $query->where('actual_price', '>=', $filterId['price']['from'])->where('actual_price', '<=', $filterId['price']['to']);
                   })
                ->get();

       return $products;
    }

    /**
     * Reset all filtres
     */
    public function filterReset()
    {
        $filter['categories'] = [];
        $filter['price'] = [];
        $filter['properties'] = [];

        setcookie('filter', serialize($filter), time() + 10000000, '/');

        $url = url()->previous();
        $url = strtok($url, '?');
        return redirect($url);
    }
}
