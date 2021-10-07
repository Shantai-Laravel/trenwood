<?php

namespace Admin\Http\Controllers;

use App\Models\ProductProperty;
use App\Models\PropertyGroup;
use App\Models\ProductPropertyTranslation;
use App\Models\ProductCategory;
use App\Models\PropertyMultiData;
use App\Models\PropertyMultiDataTranslation;
use App\Models\PropertyCategory;
use App\Models\SubProductProperty;
use App\Models\ProductCategoryTranslation;
use App\Models\Product;
use App\Models\SubproductCombination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class ProductPropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = ProductProperty::paginate(15);
        $categories = ProductCategory::get();
        $groups = PropertyGroup::get();

        if(count($groups) == 0) {
          $group = new PropertyGroup();
          $group->save();

          foreach ($this->langs as $lang):
              $group->translations()->create([
                  'lang_id' => $lang->id,
                  'name' => 'noCategory',
              ]);
          endforeach;
        }


        return view('admin::admin.productProperties.index', compact('products', 'categories', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::get();
        $groups = PropertyGroup::get();

        return view('admin::admin.productProperties.create', compact('categories', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $toValidate = [];
        $toValidate['key'] = 'required|max:255|unique:product_properies';
        foreach ($this->langs as $lang){
            $toValidate['name_'.$lang->lang] = 'required|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        $multilingual = 0;
        if ($request->get('multilingual') == 'on') { $multilingual = 1; }

        $property = new ProductProperty();
        $property->multilingual = $multilingual;
        $property->type = $request->type;
        $property->key = $request->key;
        $property->group_id = $request->group_id;
        $property->save();

        if (request('categories')) {
            $categories = request('categories');
            $allItems = [];

            if (!empty($categories)) {
                foreach ($categories as $key => $category) {
                    $allItems[] = $category;
                    $productProperty = PropertyCategory::where('property_id', $property->id)->where('category_id', $category)->first();

                    if (!is_null($productProperty)) {
                        PropertyCategory::where('id', $productProperty->id)->update([
                            'property_id' => $property->id,
                            'category_id' => $category
                        ]);
                    }else{
                        PropertyCategory::create([
                            'property_id' => $property->id,
                            'category_id' => $category
                        ]);
                    }
                }
            }
            PropertyCategory::where('property_id', $property->id)->whereNotIn('category_id', $allItems)->delete();
        }

        if (($request->type == 'select') || ($request->type == 'checkbox') || ($request->type == 'radio') || ($request->type == 'images') ) {
            return $this->saveMultiData($request, $property);
        }

        foreach ($this->langs as $lang):
            $property->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'value' => request('value_' . $lang->lang),
                'unit' => request('unit_' . $lang->lang),
                'multi_data' => 0,
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');
        return redirect()->route('properties.index');
    }


    public function saveMultiData($request, $property)
    {
        $cases = $request->get('case_' . $this->lang->lang);

        if (!empty($cases)) {
            foreach ($cases as $key => $case) {
                if ($key !== 0) {
                    $multidata = PropertyMultiData::create([
                        'property_id' => $property->id,
                    ]);

                    foreach ($this->langs as $lang){
                        PropertyMultiDataTranslation::create([
                            'property_multidata_id' => $multidata->id,
                            'lang_id' => $lang->id,
                            'name' => str_slug($request->get('case_' . $lang->lang)[$key]),
                            'value' => $request->get('case_' . $lang->lang)[$key]
                        ]);
                    }
                }
            }
        }

        foreach ($this->langs as $lang):
            $value = json_encode(explode(",", $request->get('value_' . $lang->lang)));

            $property->translations()->create([
                'lang_id' => $lang->id,
                'name' => $request->get('name_' . $lang->lang),
                'value' => $value,
                'multi_data' => 1,
                'unit' => request('unit_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('properties.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $property = ProductProperty::with(['translations'])->findOrFail($id);
        $categories = ProductCategory::all();
        $groups = PropertyGroup::get();
        $multidatas = PropertyMultiData::where('property_id', $id)->get();

        return view('admin::admin.productProperties.edit', compact('property', 'categories', 'groups', 'multidatas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $multilingual = 0;
        if ($request->get('multilingual') == 'on') { $multilingual = 1; }

        $property = ProductProperty::findOrFail($id);
        $property->multilingual = $multilingual;
        $property->type = $request->type;
        $property->key = $request->key;
        $property->group_id = $request->group_id;
        $property->save();

        $categories = request('categories');
        $allItems = [];


        if (!empty($categories)) {
            foreach ($categories as $key => $category) {
                $allItems[] = $category;
                $productProperty = PropertyCategory::where('property_id', $property->id)->where('category_id', $category)->first();

                if (!is_null($productProperty)) {
                    PropertyCategory::where('id', $productProperty->id)->update([
                        'property_id' => $property->id,
                        'category_id' => $category
                    ]);
                }else{
                    PropertyCategory::create([
                        'property_id' => $property->id,
                        'category_id' => $category
                    ]);
                }
            }
        }

        PropertyCategory::where('property_id', $property->id)->whereNotIn('category_id', $allItems)->delete();

        if (($request->type == 'select') || ($request->type == 'checkbox')) {
            return $this->updateMultiData($request, $property);
        }else{
            $delMultiDatas = PropertyMultiData::where('property_id', $property->id)->get();

            if (!empty($delMultiDatas)) {
                foreach ($delMultiDatas as $key => $delMultiData) {
                    PropertyMultiData::where('id', $delMultiData->id)->delete();
                    PropertyMultiDataTranslation::where('property_multidata_id', $delMultiData->id)->delete();
                }
            }
        }

        foreach ($this->langs as $lang):
            $property->translations()->where('property_id', $property->id)->where('lang_id', $lang->id)->update([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'value' => request('value_' . $lang->lang),
                'multi_data' => 0,
                'unit' => request('unit_' . $lang->lang),
            ]);
        endforeach;

        $category = ProductCategory::find($category);
        $this->changeSubproductsCombinations($category);

        session()->flash('message', 'Item has been edited!');

        return redirect()->route('properties.index', $request->category_id);
    }

    public function changeSubproductsCombinations($category)
    {
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
                                        $this->setCombinations($category, $propValue_1->id, $propValue_2->id, $propValue_3->id, $product, $x);
                                        $x++;
                                    }
                                }else{
                                    $this->setCombinations($category, $propValue_1->id, $propValue_2->id, 0, $product, $x);
                                    $x++;
                                }
                            }
                        }else{
                            $this->setCombinations($category, $propValue_1->id, 0, 0, $product, $x);
                            $x++;
                        }
                    }
                }

                $getCombinations = SubproductCombination::where('category_id', $category->id)->pluck('id')->toArray();
                $product->subproducts()->where('product_id', $product->id)->whereNotIn('combination_id', $getCombinations)->delete();
            }
        }
    }

    private function setCombinations($category, $propValue_1, $propValue_2, $propValue_3, $product, $x)
    {
        $combination = SubproductCombination::create([
            'category_id' => $category->id,
            'case_1' => $propValue_1,
            'case_2' => $propValue_2,
            'case_3' => $propValue_3,
        ]);

        $subproduct = $product->subproducts()->where('product_id', $product->id)->where('code', $product->id.'-'.$x)->first();
        if (!is_null($subproduct)) {
            $subprod = $product->subproducts()->where('id', $subproduct->id)->update([
                'combination_id' => $combination->id,
            ]);
        }else{
            $subprod = $product->subproducts()->create([
                'code' => $product->id.'-'.$x,
                'combination_id' => $combination->id,
            ]);
        }
    }

    public function updateMultiData($request, $property)
    {
        $cases = $request->get('case_' . $this->lang->lang);
        $countItems = [];

        if (!empty($cases)) {
            foreach ($cases as $key => $case) {
                if ($key !== 0) {

                    $multidataExists = PropertyMultiData::where('id', $key)->where('property_id', $property->id)->first();

                    if (is_null($multidataExists)) {
                        $multidata = PropertyMultiData::create([
                            'property_id' => $property->id,
                        ]);

                        foreach ($this->langs as $lang){
                            PropertyMultiDataTranslation::create([
                                'property_multidata_id' => $multidata->id,
                                'lang_id' => $lang->id,
                                'name' => str_slug($request->get('case_' . $lang->lang)[$key]),
                                'value' => $request->get('case_' . $lang->lang)[$key]
                            ]);
                        }
                        $countItems[] = $multidata->id;
                    }else{
                        $countItems[] = $key;
                        $multidata = PropertyMultiData::where('id', $multidataExists->id)->update([
                            'property_id' => $property->id,
                        ]);

                        foreach ($this->langs as $lang){
                            PropertyMultiDataTranslation::where('property_multidata_id', $multidataExists->id)->where('lang_id', $lang->id)->update([
                                'property_multidata_id' => $multidataExists->id,
                                'lang_id' => $lang->id,
                                'name' => str_slug($request->get('case_' . $lang->lang)[$key]),
                                'value' => $request->get('case_' . $lang->lang)[$key]
                            ]);
                        }
                    }
                }
            }
        }

        $delMultiDatas = PropertyMultiData::whereNotIn('id', $countItems)->where('property_id', $property->id)->get();

        if (!empty($delMultiDatas)) {
            foreach ($delMultiDatas as $key => $delMultiData) {
                PropertyMultiData::where('id', $delMultiData->id)->delete();
                PropertyMultiDataTranslation::where('property_multidata_id', $delMultiData->id)->delete();
            }
        }

        foreach ($this->langs as $lang):
            $value = json_encode(explode(",", $request->get('value_' . $lang->lang)));

            $property->translations()->where('property_id', $property->id)->where('lang_id', $lang->id)->update([
                'lang_id' => $lang->id,
                'name' => $request->get('name_' . $lang->lang),
                'value' => $value,
                'multi_data' => 1,
                'unit' => request('unit_' . $lang->lang),
            ]);
        endforeach;

        $categories = SubProductProperty::where('property_id', $property->id)->groupBy('property_id')->get();

        if (count($categories)) {
            foreach ($categories as $key => $cat) {
                $category = ProductCategory::find($cat->product_category_id);
                $this->changeSubproductsCombinations($category);
            }
        }

        session()->flash('message', 'New item has been created!');

        return redirect()->route('properties.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $checkSubproducts = SubProductProperty::where('property_id', $id)->where('show_property', 1)->get();
        $categories = false;
        if (count($checkSubproducts) > 0) {
            $categories = "parametrul este folosit la subproduse, atrubuit la urmatoarele categorii: <hr>";
            foreach ($checkSubproducts as $key => $checkSubproduct) {
                $categories .= '- ';
                $categories .= ProductCategoryTranslation::where('product_category_id', $checkSubproduct->product_category_id)->first()->name;
                $categories .= '<br>';
            }
        }
        if ($categories !== false) {
            Session::flash('alert', $categories);
            return redirect()->back();
        }
        ProductProperty::where('id', $id)->delete();
        ProductPropertyTranslation::where('property_id', $id)->delete();

        return redirect()->back();
    }

    public function makeFilter($id)
    {
      $property = ProductProperty::findOrFail($id);

      if ($property->filter == 1) {
          $property->filter = 0;
      } else {
          $property->filter = 1;
      }

      $property->save();

      return redirect()->back();
    }
}
