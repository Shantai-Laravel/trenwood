<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AutoAlt;
use App\Models\ProductCategoryTranslation;
use App\Models\ProductCategory;
use App\Models\PropertyCategory;
use App\Models\ProductProperty;
use App\Models\Brand;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\PropertyValue;
use App\Models\PropertyValueTranslation;
use App\Models\ProductTranslation;
use App\Models\ProductImage;
use App\Models\ProductImageTranslation;
use App\Models\GalleryImageTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Intervention\Image\ImageManagerStatic as Image;
use Excel;


class QuickUploadController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::get();
        $brands = Brand::get();
        $promotions = Promotion::get();
        $products = Product::where('category_id', $request->get('category'))->get();
        $properties = $this->getProperties($request->get('category'));

        return view('admin::admin.quickUpload.index', compact('products', 'categories', 'brands', 'promotions', 'properties'));
    }


    public function saveProducts(Request $request)
    {
        $id     = json_decode($request->get('id'));
        $catId  = json_decode($request->get('catID'));
        $name   = json_decode($request->get('name'));
        $body   = json_decode($request->get('body'));
        $price  = json_decode($request->get('price'));
        $brand  = json_decode($request->get('brand'));
        $promo  = json_decode($request->get('promo'));
        $discount = json_decode($request->get('discount'));
        $props  = json_decode($request->get('props'));


        if (strlen(get_object_vars($name)[1]) > 0) {

        $product = Product::select('id')->where('id', $id)->first();

        if (!is_null($product)) {
            Product::where('id', $id)->update([
                        'price' => $price,
                        'discount' => $discount,
                        'brand_id' => $brand,
                        'promotion_id' => $promo,
                    ]);

            $products = Product::select('id')->where('id', $id)->get();

            if (!empty($products)) {
                foreach ($products as $key => $product) {
                    foreach ($name as $langId => $value) {
                        ProductTranslation::where('product_id', $product->id)->where('lang_id', $langId)->update([
                            'name' => $name->$langId,
                            'body' => $body->$langId,
                        ]);
                    }
                }
            }

            $this->saveProperties(get_object_vars($props), $id);
        }else{
            $name = get_object_vars($name);
            $body = get_object_vars($body);

            $product = new Product();

            $product->category_id = $catId;
            $product->brand_id = $brand;
            $product->promotion_id = $promo;
            $product->alias = str_slug($name[1]);
            $product->price = $price ?? 0;
            $product->discount = $discount ?? 0;

            $product->save();

            foreach ($this->langs as $lang):
                $product->translations()->create([
                    'lang_id' => $lang->id,
                    'name' => $name[$lang->id],
                    'body' => $body[$lang->id],
                    'alias' => str_slug($name[$lang->id]),
                ]);
            endforeach;

            $this->saveProperties(get_object_vars($props), $product->id);
        }

    }else{
        return 'false';
    }

        $categories = ProductCategory::select('id')->get();
        $brands = Brand::select('id')->get();
        $promotions = Promotion::select('id')->get();
        $products = Product::where('category_id', $catId)->get();
        $properties = $this->getProperties($catId);

        return view('admin::admin.quickUpload.toClone', compact('products', 'categories', 'brands', 'promotions', 'properties'))->render();
    }


    public function saveProperties($properties, $productId)
    {
        $propertyValues = PropertyValue::select('id')->where('product_id', $productId)->get();

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


    public function getProperties($category_id)
    {
        $properties = [];
        $category = ProductCategory::select('id')->where('id', $category_id)->first();

        if (!is_null($category)) {
            $properties = array_merge($properties, $this->getPropertiesList($category->id));
            $category1 = ProductCategory::select('id')->where('id', $category->id)->first();
            if (!is_null($category1)) {
                $properties = array_merge($properties, $this->getPropertiesList($category1->id));
                $category2 = ProductCategory::select('id')->where('id', $category1->id)->first();
                if (!is_null($category2)) {
                    $properties = array_merge($properties, $this->getPropertiesList($category2->id));
                    $category3 = ProductCategory::select('id')->where('id', $category2->id)->first();
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
        $properties = PropertyCategory::select('property_id')->where('category_id', $categoryId)->get();
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
        $properties = ProductProperty::select('id')->where('group_id', $noCatGroup)->get();
        if (!empty($properties)) {
            foreach ($properties as $key => $property) {
                $propertiesArr[] = $property->id;
            }
        }

        return $propertiesArr;
    }

    public function uploadFiles(Request $request)
    {
        $productItem = Product::where('id', $request->get('product_id'))->first();

        if (!is_null($productItem)) {

        $product = $request->get('product_id');

        if($files=$request->file('file')){
            foreach($files as $key => $file){
                $uniqueId = uniqid();
                $name = $uniqueId.$file->getClientOriginalName();

                $image_resize = Image::make($file->getRealPath());

                $product_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['product'];

                $image_resize->save(public_path('images/products/og/' .$name), 75);

                $image_resize->resize($product_image_size[0]['bgfrom'], $product_image_size[0]['bgto'])->save(public_path('images/products/bg/' .$name), 75);

                $image_resize->resize($product_image_size[1]['mdfrom'], $product_image_size[1]['mdto'])->save(public_path('images/products/md/' .$name), 75);

                $image_resize->resize($product_image_size[2]['smfrom'], $product_image_size[2]['smto'])->save(public_path('images/products/sm/' .$name), 85);

                $images[] = $name;

                $image = ProductImage::create( [
                    'product_id' =>  $product,
                    'src' =>  $name,
                    'main' => 0,
                ]);

                foreach ($this->langs as $lang){
                  $category_id = Product::where('id', $product)->pluck('category_id');
                  $autoAlt = AutoAlt::where('cat_id', $category_id)->where('lang_id', $lang->id)->pluck('keywords')->toArray();

                  if(count($autoAlt) == 0) {
                    ProductImageTranslation::create( [
                        'product_image_id' => $image->id,
                        'lang_id' =>  $lang->id,
                        'alt' => $request->text[$lang->id][$key],
                        'title' => $request->text[$lang->id][$key],
                    ]);
                  }

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
                  }
                }
            }
        }

     }

     $product = $productItem;
     return view('admin::admin.quickUpload.imagesLiveUpdate', compact('product'))->render();

    }

    public function downloadCSV(Excel $excel, $id)
    {
        $category = ProductCategory::where('id', $id)->first();

        if (!is_null($category)) {
            $filename = "categories.csv";
            $handle = fopen($filename, 'w+');
            fprintf($handle, "\xEF\xBB\xBF");

            // default fields
            $data = ['Сategorie/Категория', 'Сode', 'Title (ro)', 'Title (ru)', 'Description (ro)', 'Description (ru)', 'Price', 'Discount', 'Stock' ];
            $dataImage = ['Image-1 (name)', 'Image-2 (name)', 'Image-3 (name)', 'Image-4 (name)', 'Image-5 (name)'];

            $propertiesId = PropertyCategory::where('category_id', $category->id)->pluck('property_id')->toArray();
            $properties = ProductProperty::whereIn('id', $propertiesId)->get();

            if (count($properties) > 0) {
                foreach ($properties as $key => $property) {
                    if (($property->type == 'select') || ($property->type == 'checkbox')) {
                        array_push($data, $property->key.' ('. $property->id .') '.$property->translationByLanguage(1)->first()->unit);
                    }else{
                        array_push($data, $property->key.' (ro) ');
                        array_push($data, $property->key.' (ru)');
                    }
                }
            }

            $data = array_merge($data, $dataImage);

            // default values
            $values = [$category->translationByLanguage(1)->first()->name, 'int', 'стриг', 'стриг', 'стриг', 'стриг', 'int (lei)', 'int (%)', 'int' ];

            if (count($properties) > 0) {
                foreach ($properties as $key => $property) {
                    if (($property->type == 'select') || ($property->type == 'checkbox')) {
                        $multidatas = $property->multidata;
                        if (count($multidatas) > 0) {
                            $ret = '';
                            foreach ($multidatas as $key => $multidata) {
                                $ret .= $multidata->translationByLanguage(1)->first()->value.'( '.$multidata->id.' ), ';
                            }
                        }
                        array_push($values, $ret);
                    }else{
                        array_push($values, '');
                        array_push($values, '');
                    }
                }
            }

            $valueImage = ['/', '/', '/', '/', '/'];
            $values = array_merge($values, $valueImage);

            fputcsv($handle, $data, ';', '"');
            fputcsv($handle, $values, ';', '"');

            fclose($handle);

            $headers = array(
                "Content-type" => "text/csv;  charset=UTF-8",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            return response()->download($filename, 'categories.csv', $headers);
        }
    }

    public function uploadCSV(Request $request)
    {
        $handle = fopen($request->file('file')->getRealPath(), "r");
        $header = true;

        // dd($handle);
        while ($row = fgetcsv($handle, 0, ",")) {
            if ($header) {
                $header = false;
            } else {
                $row = array_map("utf8_encode", $row); //added
                echo "<pre>";

                print_r( $row );
                echo "<br>";
                    $product = new Product();
                    $product->category_id = $request->get('categoryId');
                    $product->alias = str_slug(mb_convert_encoding($row[2], 'utf8', 'cp1251'));
                    $product->stock = mb_convert_encoding($row[8], 'utf8', 'cp1251');
                    $product->price = mb_convert_encoding($row[6], 'utf8', 'cp1251');
                    $product->discount = mb_convert_encoding($row[7], 'utf8', 'cp1251');
                    $product->code = mb_convert_encoding($row[1], 'utf8', 'cp1251');
                    $product->stock = mb_convert_encoding($row[8], 'utf8', 'cp1251');
                    $product->save();

                    foreach ($this->langs as $lang):
                        if ($lang->id == 1) {
                            $product->translations()->create([
                                'lang_id' => $lang->id,
                                'name' => mb_convert_encoding($row[2], 'utf8', 'cp1251'),
                                'body' => mb_convert_encoding($row[4], 'utf8', 'cp1251'),
                                'alias' => str_slug(mb_convert_encoding($row[2], 'utf8', 'cp1251')),
                            ]);
                        }else{
                            $product->translations()->create([
                                'lang_id' => $lang->id,
                                'name' => mb_convert_encoding($row[3], 'utf8', 'cp1251'),
                                'body' => mb_convert_encoding($row[5], 'utf8', 'cp1251'),
                                'alias' => str_slug(mb_convert_encoding($row[3], 'utf8', 'cp1251')),
                            ]);
                        }
                    endforeach;
            }
        }

        fclose ($handle);
    }

}
