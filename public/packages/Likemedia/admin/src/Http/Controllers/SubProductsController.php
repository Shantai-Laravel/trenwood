<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\PropertyCategory;
use App\Models\ProductProperty;
use App\Models\PropertyMultiData;
use App\Models\SubProductProperty;
use App\Models\SubProductValue;
use App\Models\SubproductCombination;

class SubProductsController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('position', 'asc')->get();
        $product_category = $categories[0];
        $properties = $this->getProperties($product_category->id);

        return view('admin::admin.subproducts.index', compact('categories', 'properties', 'product_category'));
    }

    public function filterProperties()
    {
        $product_category = ProductCategory::find(request('category'));
        $properties = $this->getProperties($product_category->id);
        $data['properties'] = view('admin::admin.subproducts.properties', compact('properties', 'product_category'))->render();

        return json_encode($data);
    }

    public function store() {

        foreach (request('property_id') as $key => $property) {
            $property = ProductProperty::find($property);
            if($property->type != 'select' && $property->type != 'checkbox' && request('status')[$key] == 'dependable') {
                return redirect()->back()->withErrors('Dependable может быть только тип select/checkbox');
            }
        }

        $category = ProductCategory::find(request('category_id'));

        // add to all categories
        $allChildCategories = $this->childCategories($category);

        if (count($allChildCategories) > 0) {
            foreach ($allChildCategories as $key => $category) {


        $subproduct_properties = $category->properties()->where('product_category_id', $category->id);

        // dd($subproduct_properties->get());
        if(count($subproduct_properties->get()) > 0) {
            foreach (request('property_id') as $key => $property) {
                $image = request('image') == $property ? 1 : 0;
                SubProductProperty::where('property_id', $property)->where('product_category_id', $category->id)->update([
                    'show_property' => request('show')[$key],
                    'status' => request('status')[$key],
                    'image' => $image,
                ]);
            }
        }else{
            foreach (request('property_id') as $key => $property) {
                $image = request('image') == $property ? 1 : 0;
                SubProductProperty::create([
                    'product_category_id' => $category->id,
                    'property_id' => $property,
                    'show_property' => request('show')[$key],
                    'status' => request('status')[$key],
                    'image' => $image
                ]);
            }
        }


        if(count($category->products()->get()) > 0) {
            SubproductCombination::where('category_id', $category->id)->delete();

            foreach ($category->products()->get() as $product) {
              $subproducts = $product->subproducts()->get();

              // get properties only 3 cases
              $categoryProperties = SubProductProperty::where('product_category_id', $category->id)
                                                    ->where('status', 'dependable')
                                                    ->where('show_property', 1)
                                                    ->limit(3)
                                                    ->get();

              if (count($categoryProperties) > 0) {
                  foreach ($categoryProperties as $key => $categoryProperty) {
                      $propCase[$key] = $categoryProperty->property_id;
                  }
              }

                $x = 'A';
                $propValues_1 = PropertyMultiData::where('property_id', @$propCase[0])->get();
                if (count($propValues_1) > 0) {
                    foreach ($propValues_1 as $key => $propValue_1) {

                        $propValues_2 = PropertyMultiData::where('property_id', @$propCase[1])->get();
                        if (count($propValues_2) > 0) {
                            foreach ($propValues_2 as $key => $propValue_2) {

                                $propValues_3 = PropertyMultiData::where('property_id', @$propCase[2])->get();
                                if (count($propValues_3) > 0) {
                                    foreach ($propValues_3 as $key => $propValue_3) {
                                        $this->setCombinations($category, $propValue_1, $propValue_2, $propValue_3, $product, $x);
                                        $x++;
                                    }
                                }else{
                                    $this->setCombinations($category, $propValue_1, $propValue_2, 0, $product, $x);
                                    $x++;
                                }
                            }
                        }else{
                            $this->setCombinations($category, $propValue_1, 0, 0, $product, $x);
                            $x++;
                        }
                    }
                }

            }
        }

    }
}
        // $getCombinations = SubproductCombination::whereIn('category_id', $allChildCategories)->pluck('id')->toArray();
        // SubProduct::whereNotIn('combination_id', $getCombinations)->delete();

        return redirect()->back();
    }

    public function childCategories($category)
    {
        $categoriesID[] = $category->id;

        $categoryChild1 = ProductCategory::where('parent_id', $category->id)->pluck('id')->toArray();
        if (count($categoryChild1) > 0) {
            $categoriesID = array_merge($categoriesID, $categoryChild1);

            $categoryChild2 = ProductCategory::whereIn('parent_id', $categoryChild1)->pluck('id')->toArray();
            if (count($categoryChild2) > 0) {
                $categoriesID = array_merge($categoriesID, $categoryChild2);

                $categoryChild3 = ProductCategory::whereIn('parent_id', $categoryChild2)->pluck('id')->toArray();
                if (count($categoryChild3) > 0) {
                    $categoriesID = array_merge($categoriesID, $categoryChild3);
                }
            }

        }

        return ProductCategory::whereIn('id', $categoriesID)->get();
    }

    private function setCombinations($category, $propValue_1, $propValue_2, $propValue_3, $product, $x)
    {
        $combination = SubproductCombination::create([
            'category_id' => $category->id,
            'case_1' => $propValue_1 ? $propValue_1->id : 0 ,
            'case_2' => $propValue_2 ? $propValue_2->id : 0 ,
            'case_3' => $propValue_3 ? $propValue_3->id : 0 ,
        ]);

        $subproduct = $product->subproducts()->where('product_id', $product->id)->where('code', $product->id.'-'.$x)->first();

        $combinationJSON = [
            $propValue_1 ? $propValue_1->property_id : 0 => $propValue_1 ? $propValue_1->id : 0,
            $propValue_2 ? $propValue_2->property_id : 0 => $propValue_2 ? $propValue_2->id : 0,
            $propValue_3 ? $propValue_3->property_id : 0 => $propValue_3 ? $propValue_3->id : 0,
        ];


        if (!is_null($subproduct)) {
            $subprod = $product->subproducts()->where('id', $subproduct->id)->update([
                'combination_id' => $combination->id,
                'combination' => json_encode($combinationJSON),
            ]);
        }else{
            $subprod = $product->subproducts()->create([
                'code' => $product->id.'-'.$x,
                'combination_id' => $combination->id,
                'combination' => json_encode($combinationJSON),
            ]);
        }
    }

    private function getCombinations($properties, $size) {
        $subsets = [];
        if ($size == 1) {
            return array_map(function ($v) { return [$v]; },$properties);
        }
        foreach ($this->getCombinations($properties,$size-1) as $subset) {
            foreach ($properties as $element) {
                if (!in_array($element,$subset)) {
                    $newSet = array_merge($subset,[$element]);
                    sort($newSet);
                    if (!in_array($newSet,$subsets)) {
                        $subsets[] = array_merge($subset,[$element]);
                    }
                }
            }
        }
        return $subsets;
    }

    private function getProperties($category_id)
    {
        $properties = [];
        $category = ProductCategory::where('id', $category_id)->first();

        if (!is_null($category)) {
            $properties = array_merge($properties, $this->getPropertiesList($category->id));
            $category1 = ProductCategory::where('id', $category->id)->first();
            if (!is_null($category1)) {
                $properties = array_merge($properties, $this->getPropertiesList($category1->id));
                $category2 = ProductCategory::where('id', $category1->id)->first();
                if (!is_null($category2)) {
                    $properties = array_merge($properties, $this->getPropertiesList($category2->id));
                    $category3 = ProductCategory::where('id', $category2->id)->first();
                    if (!is_null($category3)) {
                        $properties = array_merge($properties, $this->getPropertiesList($category3->id));
                    }
                }
            }
        }

        $properties = array_unique($properties);


        $ret = ProductProperty::with('translationByLanguage')
                            ->with('multidata')
                            ->whereIn('id', $properties)
                            ->get();
        return $ret;
    }

    private function getPropertiesList($categoryId)
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
}
