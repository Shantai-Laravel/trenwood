<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

use App\Models\Product;
use App\Models\Set;
use App\Models\SetProducts;
use App\Models\ProductTranslation;
use App\Models\ProductCategory;
use App\Models\PropertyCategory;
use App\Models\ProductProperty;
use App\Models\PropertyValue;
use App\Models\PropertyValueTranslation;
use App\Models\ProductImage;
use App\Models\ProductImageTranslation;
use App\Models\ProductVideo;
use App\Models\ProductCategoryTranslation;
use App\Models\Brand;
use App\Models\Promotion;
use App\Models\AutoAlt;
use App\Models\AutoMeta;
use App\Models\ProductSimilar;
use App\Models\AutoMetaCategory;
use App\Models\SubProductProperty;
use App\Models\SubProduct;
use App\Models\SubproductCombination;
use App\Models\PropertyMultiData;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('position', 'asc')->get();

        $category = null;

        return view('admin::admin.products.index', compact('products', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $allCategories = ProductCategory::pluck('parent_id')->toArray();
        $categories = ProductCategory::whereNotIn('id', $allCategories)->orderBy('position', 'asc')->get();
        $productCategory = $request->get('category');
        $poperties = $this->getProperties($productCategory);
        $sets = Set::all();
        $promotions = Promotion::all();
        $category = ProductCategory::with('translation')->find($request->get('category'));

        return view('admin::admin.products.create', compact('categories', 'poperties', 'sets', 'category', 'promotions'));
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
        foreach ($this->langs as $lang){
            $toValidate['name_'.$lang->lang] = 'required|max:255';
            $toValidate['slug_'.$lang->lang] = 'required|unique:product_translations,alias|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        $discount = $request->discount;

        if ($request->prommotion_id > 0) {
            $promo = Promotion::where('id', $request->prommotion_id)->first();
            if (!is_null($promo)) {
                $discount = $promo->discount;
            }
        }

        if ($request->hit == 'on') {
            $hit = 1;
        } else {
            $hit = 0;
        }

        if ($request->recomended == 'on') {
            $recomended = 1;
        } else {
          $recomended = 0;
        }

        $product = new Product();
        $product->category_id = $request->category_id;
        $product->promotion_id = $request->prommotion_id;
        $product->alias = $request->slug_ro;
        $product->stock = $request->stock;
        $product->actual_price = $request->price - ($request->price * $request->discount / 100);
        $product->price = $request->price;
        $product->discount = $discount;
        $product->hit = $hit;
        $product->recomended = $recomended;
        $product->save();

        foreach ($this->langs as $lang):
            $product->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'alias' => request('slug_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'seo_h1' => request('meta_h1_' . $lang->lang),
                'seo_title' => request('meta_title_' . $lang->lang),
                'seo_keywords' => request('meta_keywords_' . $lang->lang),
                'seo_description' => request('meta_description_' . $lang->lang),
            ]);
        endforeach;

        $this->saveProperties($request->get('prop'), $product->id);
        $this->addProductImages($request, $product->id, 'images');
        $this->addProductImages($request, $product->id, 'lifestyleImages');
        $this->addProductVideo($request, $product->id);
        $this->getPriceOfSet($product);

        if (count($request->set_id) > 0) {
            SetProducts::where('product_id', $product->id)->delete();
            foreach ($request->set_id as $key => $set) {
                SetProducts::create([
                    'set_id' => $set,
                    'product_id' => $product->id,
                ]);
            }
        }

        if ($request->get('propText')) {
            $this->savePropertiesText($request->get('propText'), $product->id);
        }

        $categories = $request->get('categories');

        if (!empty($categories)) {
            foreach ($categories as $key => $category) {
                $product->similar()->create([
                    'category_id' => $category
                ]);
            }
        }

        $isAutometas = AutoMetaCategory::join('autometas', 'autometa_categories.autometa_id', 'autometas.meta_id')
                                      ->where('category_id', $request->category_id)
                                      ->where('type', 2)
                                      ->pluck('autometa_id');

        $lang_arr = [];

        if(count($isAutometas) > 0) {
            foreach ($isAutometas as $isAutometa) {
              $autometa = Autometa::where('meta_id', $isAutometa)->firstOrFail();
              $productInfo = ProductTranslation::where('lang_id', $autometa->lang_id)->where('product_id', $product->id)->firstOrFail();

              $prodName = $productInfo->name;
              $catName = ProductCategoryTranslation::where('product_category_id', $request->category_id)->where('lang_id', $autometa->lang_id)->firstOrFail()->name;

              $productInfo->description = $autometa->generateMeta($prodName, $catName, 'product_description');
              $productInfo->seo_title = $autometa->generateMeta($prodName, $catName, 'title');
              $productInfo->seo_description = $autometa->generateMeta($prodName, $catName, 'description');
              $productInfo->seo_keywords = $autometa->generateMeta($prodName, $catName, 'keywords');
              $productInfo->save();

              foreach ($this->langs as $lang) {
                if($lang->id == $autometa->lang_id) {
                  array_push($lang_arr, $lang->lang);
                }
              }

            }
            session()->flash('message', 'New item has been created and autometa generated in '.strtoupper(implode(',', $lang_arr)).'!');
        } else {
            session()->flash('message', 'New item has been created, but there is no autometa in RU or RO for this category!');
        }

        $category = ProductCategory::find($request->category_id);
        $this->generateSubproduses($category);

        return redirect('back/products/'.$product->id.'/edit?category='.$product->category_id.'&set='.$product->set_id);
    }

    public function generateSubproduses($category)
    {
        if(count($category->products()->get()) > 0) {
            // SubproductCombination::where('category_id', $category->id)->delete();

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

                $getCombinations = SubproductCombination::where('category_id', $category->id)->pluck('id')->toArray();
                $product->subproducts()->where('product_id', $product->id)->whereNotIn('combination_id', $getCombinations)->delete();
            }
        }
    }

    private function setCombinations($category, $propValue_1, $propValue_2, $propValue_3, $product, $x)
    {
        // dd($propValue_2);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $product = Product::with(['translations'])->findOrFail($id);

        $allCategories = ProductCategory::pluck('parent_id')->toArray();
        $categories = ProductCategory::whereNotIn('id', $allCategories)->orderBy('position', 'asc')->get();

        $sets = Set::all();

        $promotions = Promotion::all();

        $productCategory = $product->category_id;

        $poperties = $this->getProperties($productCategory);

        $images = ProductImage::where('product_id', $id)->where('type', 'image')->orderBy('main', 'desc')->get();
        $lifestyleImages = ProductImage::where('product_id', $id)->where('type', 'lifestyleImage')->orderBy('main', 'desc')->get();

        $category = ProductCategory::with('translation')->find($request->get('category'));

        return view('admin::admin.products.edit', compact('product', 'categories', 'poperties', 'images', 'lifestyleImages', 'sets', 'category', 'promotions'));
    }

    public function addProductImages($request, $product, $type)
    {
       $input = $request->all();

       $images=array();
       if($files=$request->file($type)){
           foreach($files as $key => $file){
               $uniqueId = uniqid();
               $name = $uniqueId.$file->getClientOriginalName();

               $image_resize = Image::make($file->getRealPath());

               $product_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['product'];

               $image_resize->save(public_path('images/products/og/' .$name), 75);

               $image_resize->resize($product_image_size[0]['bgfrom'], $product_image_size[0]['bgto'])->save('images/products/bg/' .$name, 75);

               $image_resize->resize($product_image_size[1]['mdfrom'], $product_image_size[1]['mdto'])->save('images/products/md/' .$name, 75);

               $image_resize->resize($product_image_size[2]['smfrom'], $product_image_size[2]['smto'])->save('images/products/sm/' .$name, 85);

               $images[] = $name;

               $image = ProductImage::create( [
                   'product_id' =>  $product,
                   'type' => substr($type, 0, -1),
                   'src' =>  $name,
                   'main' => 0,
               ]);

               foreach ($this->langs as $lang){

                   ProductImageTranslation::create( [
                       'product_image_id' => $image->id,
                       'lang_id' =>  $lang->id,
                       'alt' => $request->get('alt_')[$lang->id][$key],
                       'title' => $request->get('title_')[$lang->id][$key],
                   ]);
                 $category_id = Product::where('id', $product)->pluck('category_id');
                 $autoAlt = AutoAlt::where('cat_id', $category_id)->where('lang_id', $lang->id)->pluck('keywords')->toArray();
                   if(count($autoAlt) > 0) {
                     if (count($autoAlt) == 1) {
                         ProductImageTranslation::create( [
                             'product_image_id' => $image->id,
                             'lang_id' =>  $lang->id,
                             'alt' => $autoAlt[0],
                             'title' => $autoAlt[0],
                         ]);
                     } else {
                       ProductImageTranslation::create( [
                           'product_image_id' => $image->id,
                           'lang_id' =>  $lang->id,
                           'alt' => $autoAlt[array_rand($autoAlt)],
                           'title' => $autoAlt[array_rand($autoAlt)],
                       ]);
                     }
                   } else {
                     ProductImageTranslation::create( [
                         'product_image_id' => $image->id,
                         'lang_id' =>  $lang->id,
                         'alt' => $request->text[$lang->id][$key],
                         'title' => $request->text[$lang->id][$key],
                     ]);
                   }
               }
           }
       }

    }

    public function addProductVideo($request, $productId) {
        if($request->get('video_'.$this->langs[0]->lang)) {
              $video = ProductVideo::create( [
                  'product_id' =>  $productId
              ]);

             foreach ($this->langs as $lang){
                 $video->translations()->create([
                   'lang_id' => $lang->id,
                   'src' =>  $request->get('video_'.$lang->lang)
                 ]);
             }
        }
    }

    public function getProperties($category_id)
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
        $properties = array_merge($properties, $this->getNoCategoryProperties());

        $properties = array_unique($properties);

        $ret = ProductProperty::with('translationByLanguage')
                            ->with('multidata')
                            ->whereIn('id', $properties)
                            ->get();

        return $ret;
    }

    public function getPropertiesList($categoryId)
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

    public function getNoCategoryProperties()
    {
        $noCatGroup = 14;
        $propertiesArr = [];
        $properties = ProductProperty::where('group_id', $noCatGroup)->get();
        if (!empty($properties)) {
            foreach ($properties as $key => $property) {
                $propertiesArr[] = $property->id;
            }
        }

        return $propertiesArr;
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
        $toValidate['qty'] = 'numeric';
        foreach ($this->langs as $lang){
            $toValidate['name_'.$lang->lang] = 'required|max:255';
            $toValidate['slug_'.$lang->lang] = 'required|max:255';
        }

        $validator = $this->validate($request, $toValidate);
        $discount = $request->discount;

        if ($request->prommotion_id > 0) {
            $promo = Promotion::where('id', $request->prommotion_id)->first();
            if (!is_null($promo)) {
                $discount = $promo->discount;
            }
        }

        if ($request->hit == 'on') { $hit = 1; }
        else { $hit = 0; }

        if ($request->recomended == 'on') { $recomended = 1; }
        else { $recomended = 0; }

        $product = Product::findOrFail($id);
        $product->category_id = $request->category_id;
        $product->alias = $request->slug_ro;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->actual_price = $request->price - ($request->price * $request->discount / 100);
        $product->discount = $discount;
        $product->hit = $hit;
        $product->recomended = $recomended;
        $product->promotion_id = $request->prommotion_id;

        $product->save();

        $product->translations()->delete();

        foreach ($this->langs as $lang):
            $product->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'alias' => request('slug_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'seo_h1' => request('meta_h1_' . $lang->lang),
                'seo_title' => request('meta_title_' . $lang->lang),
                'seo_keywords' => request('meta_keywords_' . $lang->lang),
                'seo_description' => request('meta_description_' . $lang->lang),
            ]);
        endforeach;

        if (count($request->set_id) > 0) {
            SetProducts::where('product_id', $product->id)->delete();
            foreach ($request->set_id as $key => $set) {
                SetProducts::create([
                    'set_id' => $set,
                    'product_id' => $product->id,
                ]);
            }
        }

        $this->saveProperties($request->get('prop'), $id);
        if ($request->get('propText')) {
            $this->savePropertiesText($request->get('propText'), $product->id);
        }
        $this->addProductImages($request, $product->id, 'images');
        $this->addProductImages($request, $product->id, 'lifestyleImages');
        $this->addProductVideo($request, $product->id);
        $this->addSubproductImages($request, $product->id);

        $categories = $request->get('categories');
        $allItems = [];

        if (!empty($categories)) {
            foreach ($categories as $key => $category) {
                $allItems[] = $category;
                $productSimilar = $product->similar()->where('category_id', $category)->first();
                if(count($productSimilar) > 0) {
                  $productSimilar->category_id = $category;
                  $productSimilar->save();
                } else {
                  $product->similar()->create([
                      'category_id' => $category
                  ]);
                }
            }
        }

        $product->similar()->whereNotIn('category_id', $allItems)->delete();

        $isAutometa = AutoMetaCategory::join('autometas', 'autometa_categories.autometa_id', 'autometas.meta_id')
                                      ->where('category_id', $request->category_id)
                                      ->where('type', 2)
                                      ->pluck('autometa_id');

        $category = ProductCategory::find($product->category_id);
        $subproduct_properties = $category->properties()->where('product_category_id', $category->id);

        if(count($subproduct_properties->get()) > 0) {
          foreach ($product->subproducts()->get() as $subproduct) {
              $subproduct->values()->delete();
          }
        }

        $product->subproducts()->update([ 'active' => 0,]);

        if (request('subproduct_active')) {
            foreach (request('subproduct_active') as $key => $activeItem) {
                $product->subproducts()->where('id', $activeItem)->update([
                    'active' => 1,
                ]);
            }
        }

        if (request('subprod')) {
            foreach (request('subprod') as $key => $subprod) {
                $product->subproducts()->where('id', $key)->update([
                    'price' => @$subprod['price'],
                    'discount' => @$subprod['discount'],
                    'stock' => @$subprod['stock'],
                ]);
            }
        }

        if(count($isAutometa) > 0) {
            session()->flash('message', 'Item has been updated!');
        } else {
            session()->flash('message', 'New item has been created, but there is no autometa in RU or RO for this category!');
        }

        $this->getPriceOfSet($product);

        return redirect()->back();
    }


    public function addSubproductImages($request, $product)
    {
       $input = $request->all();

       $images=array();
       if($files=$request->file('subprod_image')){
           foreach($files as $key => $image){
               $imageName = time() . '-' . $image->getClientOriginalName();
               $image_resize = Image::make($image->getRealPath());

               $product_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['product'];

               $image_resize->save(public_path('images/subproducts/og/' .$imageName), 75);

               $image_resize->resize($product_image_size[0]['bgfrom'], $product_image_size[0]['bgto'])->save('images/subproducts/bg/' .$imageName, 75);

               $image_resize->resize($product_image_size[1]['mdfrom'], $product_image_size[1]['mdto'])->save('images/subproducts/md/' .$imageName, 75);

               $image_resize->resize($product_image_size[2]['smfrom'], $product_image_size[2]['smto'])->save('images/subproducts/sm/' .$imageName, 85);

               $image = ProductImage::create( [
                   'product_id' =>  0,
                   'src' =>  $imageName,
                   'main' => 1,
               ]);

              SubProduct::where('product_id', $product)->where('combination', 'like', '%:' . $key . '%')->update([
                  'product_image_id' => $image->id,
              ]);

               foreach ($this->langs as $lang){
                   ProductImageTranslation::create( [
                       'product_image_id' => $image->id,
                       'lang_id' =>  $lang->id,
                       'alt' => $request->get('alt_')[$lang->id][$key],
                       'title' => $request->get('title_')[$lang->id][$key],
                   ]);
                   $category_id = Product::where('id', $product)->pluck('category_id');
                   $autoAlt = AutoAlt::where('cat_id', $category_id)->where('lang_id', $lang->id)->pluck('keywords')->toArray();

                   if(count($autoAlt) > 0) {
                     if (count($autoAlt) == 1) {
                         ProductImageTranslation::create( [
                             'product_image_id' => $image->id,
                             'lang_id' =>  $lang->id,
                             'alt' => $autoAlt[0],
                             'title' => $autoAlt[0],
                         ]);
                     } else {
                       ProductImageTranslation::create( [
                           'product_image_id' => $image->id,
                           'lang_id' =>  $lang->id,
                           'alt' => $autoAlt[array_rand($autoAlt)],
                           'title' => $autoAlt[array_rand($autoAlt)],
                       ]);
                     }
                   } else {
                     ProductImageTranslation::create( [
                         'product_image_id' => $image->id,
                         'lang_id' =>  $lang->id,
                         'alt' => $request->text[$lang->id][$key],
                         'title' => $request->text[$lang->id][$key],
                     ]);
                   }
               }
           }
       }

        // delete oldImages
        $subproductsImages = SubProduct::pluck('product_image_id')->toArray();
        $allImages = ProductImage::whereNotIn('id', $subproductsImages)->where('product_id', 0)->get();
        if (count($allImages) > 0) {
            foreach ($allImages as $key => $image) {
                if (file_exists(public_path('images/subproducts/bg/'.$image->src))) {
                    unlink(public_path('images/subproducts/bg/'.$image->src));
                }
                if (file_exists(public_path('images/subproducts/og/'.$image->src))) {
                    unlink(public_path('images/subproducts/og/'.$image->src));
                }
                if (file_exists(public_path('images/subproducts/md/'.$image->src))) {
                    unlink(public_path('images/subproducts/md/'.$image->src));
                }
                if (file_exists(public_path('images/subproducts/sm/'.$image->src))) {
                    unlink(public_path('images/subproducts/sm/'.$image->src));
                }
                ProductImageTranslation::where('product_image_id', $image->id)->delete();
                ProductImage::where('id', $image->id)->delete();
            }
        }

    }

    public function saveProperties($properties, $productId)
    {
        $propertyValues = PropertyValue::where('product_id', $productId)->get();

        if (!empty($propertyValues)) {
            foreach ($propertyValues as $key => $propertyValue) {
                PropertyValue::where('id', $propertyValue->id)->delete();
                PropertyValueTranslation::where('property_values_id', $propertyValue->id)->delete();
            }
        }


        if (!empty($properties)) {
            foreach ($properties as $key => $property) {
                if (is_array($property)) {
                    $property = json_encode($property);
                }
                $propertyValues = PropertyValue::create([
                    'property_id' => $key,
                    'product_id' => $productId,
                    'value_id' => $property
                ]);

                if (is_array($property)) {
                    foreach ($property as $key => $value) {
                        if (is_array($value)) {
                            $items = [];
                            foreach ($value as $key => $checkboxItem) {
                                $items[] = $checkboxItem;
                            }
                            $value = json_encode($items);
                        }
                        PropertyValueTranslation::create([
                            'property_values_id' => $propertyValues->id,
                            'lang_id' => $key,
                            'value' => $value
                        ]);
                    }
                }else{
                    PropertyValueTranslation::create([
                        'property_values_id' => $propertyValues->id,
                        'lang_id' => 0,
                        'value' => $property
                    ]);
                }
            }
        }
    }

    public function savePropertiesText($properties, $productId)
    {
        $propertyValues = PropertyValue::where('product_id', $productId)->whereIn('property_id', array_keys($properties))->get();

        if (!empty($propertyValues)) {
            foreach ($propertyValues as $key => $propertyValue) {
                PropertyValue::where('id', $propertyValue->id)->delete();
                PropertyValueTranslation::where('property_values_id', $propertyValue->id)->delete();
            }
        }

        if (!empty($properties)) {
            foreach ($properties as $key => $property) {
                $propertyValues = PropertyValue::create([
                    'property_id' => $key,
                    'product_id' => $productId,
                    'value_id' => 0
                ]);

                if (is_array($property)) {
                    foreach ($property as $key => $value) {
                        if (is_array($value)) {
                            $items = [];
                            foreach ($value as $key => $checkboxItem) {
                                $items[] = $checkboxItem;
                            }
                            $value = json_encode($items);
                        }
                        PropertyValueTranslation::create([
                            'property_values_id' => $propertyValues->id,
                            'lang_id' => $key,
                            'value' => $value
                        ]);
                    }
                }else{
                    $propertyValues = PropertyValue::create([
                        'property_id' => $key,
                        'product_id' => $productId,
                        'value_id' => $property
                    ]);

                    PropertyValueTranslation::create([
                        'property_values_id' => $propertyValues->id,
                        'lang_id' => 0,
                        'value' => $property
                    ]);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::where('id', $id)->delete();
        ProductTranslation::where('product_id', $id)->delete();
        ProductSimilar::where('product_id', $id)->delete();

        $video = ProductVideo::where('product_id', $id)->first();
        $video->delete();
        $video->translations()->delete();

        $images = ProductImage::where('product_id', $id)->get();
        if (!empty($images)) {
            foreach ($images as $key => $image) {
                ProductImage::where('id', $image->id)->delete();
                ProductImageTranslation::where('product_image_id', $image->id)->delete();

                if (file_exists(public_path('images/products/bg/'.$image->src))) {
                    unlink(public_path('images/products/bg/'.$image->src));
                }
                if (file_exists(public_path('images/products/og/'.$image->src))) {
                    unlink(public_path('images/products/og/'.$image->src));
                }
                if (file_exists(public_path('images/products/md/'.$image->src))) {
                    unlink(public_path('images/products/md/'.$image->src));
                }
                if (file_exists(public_path('images/products/sm/'.$image->src))) {
                    unlink(public_path('images/products/sm/'.$image->src));
                }
            }
        }

        return redirect()->back();
    }

    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->with('translation')->orderBy('position', 'asc')->get();
        $category = ProductCategory::with('translation')->find($categoryId);

        return view('admin::admin.products.index', compact('products', 'category'));
    }

    public function getProductsBySet($setId)
    {
        // $products = Product::where('set_id', $setId)->orderBy('position', 'asc')->get();
        $set = Set::where('id', $setId)->first();

        // dd($set);

        return view('admin::admin.products.productsSets', compact('set'));
    }

    public function editProductImages(Request $request, $product)
    {
       $inputs = $request->get('alt');

       if(!empty($inputs)){
           foreach($inputs as $key => $input){
               foreach ($this->langs as $lang){
                   ProductImageTranslation::where('product_image_id', $key)->where('lang_id', $lang->id)->update( [
                       'alt' => $request->get('alt')[$key][$lang->id],
                       'title' => $request->get('title')[$key][$lang->id],
                   ]);
              }
           }
       }

       return redirect()->back();
    }

    public function changePosition(Request $request)
    {
        $neworder = $request->get('neworder');
        $i = 1;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k => $v) {
            $id = str_replace("tablelistsorter[]=", "", $v);
            if (!empty($id)) {
                Product::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function addMainProductImages(Request $request)
    {
        $allImages = ProductImage::where('product_id', $request->get('productId'))->get();

        if (!empty($allImages)) {
            foreach ($allImages as $key => $image) {
                $image = ProductImage::where('id', $image->id)->update([
                    'main' => 0
                ]);
            }
        }

        $image = ProductImage::where('id', $request->get('id'))->update([
            'main' => 1
        ]);

        return "true";
    }

    public function getPriceOfSet($product)
    {
        $allProducts = Product::where('set_id', $product->set_id)->get();
        $price = 0;
        if (count($allProducts) > 0) {
            foreach ($allProducts as $key => $product) {
                $price += $product->price;
            }
        }

        Set::where('id', $product->set_id)->update(['price' => $price]);
    }

    public function deleteProductImages(Request $request)
    {
        $image = ProductImage::where('product_id', $request->get('productId'))->where('id', $request->get('id'))->first();
        ProductImage::where('product_id', $request->get('productId'))->where('id', $request->get('id'))->delete();
        $images = ProductImageTranslation::where('product_image_id', $request->get('id'))->get();

        if (file_exists(public_path('images/products/bg/'.$image->src))) {
            unlink(public_path('images/products/bg/'.$image->src));
        }
        if (file_exists(public_path('images/products/og/'.$image->src))) {
            unlink(public_path('images/products/og/'.$image->src));
        }
        if (file_exists(public_path('images/products/md/'.$image->src))) {
            unlink(public_path('images/products/md/'.$image->src));
        }
        if (file_exists(public_path('images/products/sm/'.$image->src))) {
            unlink(public_path('images/products/sm/'.$image->src));
        }

        if (!empty($images)) {
            foreach ($images as $key => $image) {
                ProductImage::where('id', $image->id)->delete();
            }
        }

        return "true";
    }

    public function deleteProductVideo($id) {
        $video = ProductVideo::find($id);
        if(count($video) > 0) {
            $video->delete();
            $video->translations()->delete();
        }

        return redirect()->back();
    }
}
