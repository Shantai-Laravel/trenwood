<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AutoMeta;
use App\Models\AutoMetaCategory;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\ProductCategoryTranslation;
use App\Models\Category;
use App\Models\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class AutoMetasController extends Controller
{
    public function index()
    {
      $metas = AutoMeta::all();

      if(count($metas)) {
        foreach ($metas as $meta) {
          $var1 = explode('#', $meta->var1);
        	$var2 = explode('#', $meta->var2);
        	$var3 = explode('#', $meta->var3);
        	$var4 = explode('#', $meta->var4);
        	$var5 = explode('#', $meta->var5);
          $var6 = explode('#', $meta->var6);
        	$var7 = explode('#', $meta->var7);
        	$var8 = explode('#', $meta->var8);
        	$var9 = explode('#', $meta->var9);
        	$var10 = explode('#', $meta->var10);
          $var11 = explode('#', $meta->var11);
        	$var12 = explode('#', $meta->var12);
        	$var13 = explode('#', $meta->var13);
        	$var14 = explode('#', $meta->var14);
        	$var15 = explode('#', $meta->var15);

          $meta->description = str_replace('{{1}}', $var1[array_rand($var1)], $meta->description);
          $meta->description = str_replace('{{2}}', $var2[array_rand($var2)], $meta->description);
          $meta->description = str_replace('{{3}}', $var3[array_rand($var3)], $meta->description);
          $meta->description = str_replace('{{4}}', $var4[array_rand($var4)], $meta->description);
          $meta->description = str_replace('{{5}}', $var5[array_rand($var5)], $meta->description);
          $meta->description = str_replace('{{6}}', $var6[array_rand($var6)], $meta->description);
          $meta->description = str_replace('{{7}}', $var7[array_rand($var7)], $meta->description);
          $meta->description = str_replace('{{8}}', $var8[array_rand($var8)], $meta->description);
          $meta->description = str_replace('{{9}}', $var9[array_rand($var9)], $meta->description);
          $meta->description = str_replace('{{10}}', $var10[array_rand($var10)], $meta->description);
          $meta->description = str_replace('{{11}}', $var11[array_rand($var11)], $meta->description);
          $meta->description = str_replace('{{12}}', $var12[array_rand($var12)], $meta->description);
          $meta->description = str_replace('{{13}}', $var13[array_rand($var13)], $meta->description);
          $meta->description = str_replace('{{14}}', $var14[array_rand($var14)], $meta->description);
          $meta->description = str_replace('{{15}}', $var15[array_rand($var15)], $meta->description);
          $meta->description = str_replace('{{', '', $meta->description);
          $meta->description = str_replace('}}', '', $meta->description);
          $meta->description = trim($meta->description);

          $meta->lang = Lang::findOrFail($meta->lang_id)->lang;

        }
      }

    	return view('admin::admin.autometas.index', compact('metas'));
    }

    public function create()
    {
      return view('admin::admin.autometas.create',  ['lang_id' => 1, 'type' => 1]);
    }

    public function changeCategory(Request $request)
    {
      $returnHTML = view('admin::admin.autometas.categoriesTreeNoMeta')->with(['property' => 0, 'lang_id' => $request->lang_id, 'type' => $request->type])->render();
      return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    public function store(Request $request)
    {
      $toValidate = [];
      $toValidate['lang_id'] = 'required';
      $toValidate['autometa_type'] = 'required';
      $toValidate['categories_id'] = 'required';
      $toValidate['name'] = 'required';
      $toValidate['title'] = 'required';
      $toValidate['description'] = 'required';
      $toValidate['keywords'] = 'required';

      $validator = $this->validate($request, $toValidate);

      $categories_id = explode(',',$request->categories_id);

      foreach ($categories_id as $category_id) {
        $categories = AutoMetaCategory::join('autometas', 'autometa_categories.autometa_id', 'autometas.meta_id')
                      ->where('category_id', $category_id)
                      ->where('type', $request->type)
                      ->where('lang_id', $request->lang_id)
                      ->get();

        if(count($categories) > 0) {
          $meta_id = $categories[0]->autometa_id;
          AutoMetaCategory::where('autometa_id', $meta_id)->delete();
          AutoMeta::where('meta_id', $meta_id)->delete();
        }
      }

      $meta_id = genMetaId();

      $meta = new AutoMeta();
      $meta->meta_id = $meta_id;
      $meta->name = $request->name;
      $meta->seotext = $request->seotext;
      $meta->product_description = $request->product_description;
      $meta->title = $request->title;
      $meta->description = $request->description;
      $meta->keywords = $request->keywords;
      $meta->type = $request->autometa_type;
      $meta->lang_id = $request->lang_id;
      $meta->var1 = $request->var1;
      $meta->var2 = $request->var2;
      $meta->var3 = $request->var3;
      $meta->var4 = $request->var4;
      $meta->var5 = $request->var5;
      $meta->var6 = $request->var6;
      $meta->var7 = $request->var7;
      $meta->var8 = $request->var8;
      $meta->var9 = $request->var9;
      $meta->var10 = $request->var10;
      $meta->var11 = $request->var11;
      $meta->var12 = $request->var12;
      $meta->var13 = $request->var13;
      $meta->var14 = $request->var14;
      $meta->var15 = $request->var15;
      $meta->save();

      foreach ($categories_id as $category_id) {
        $autometa_category = new AutoMetaCategory();
        $autometa_category->autometa_id = $meta_id;
        $autometa_category->category_id = $category_id;
        $autometa_category->save();
      }

      if($request->autometa_type == 1) {
        foreach ($categories_id as $category_id) {
          $categoryInfo = ProductCategoryTranslation::where('lang_id', $request->lang_id)->where('product_category_id', $category_id)->firstOrFail();
          $catName = $categoryInfo->name;

          $categoryInfo->seo_text = $meta->generateMeta('', $catName, 'seotext');
          $categoryInfo->seo_title = $meta->generateMeta('', $catName, 'title');
          $categoryInfo->seo_description = $meta->generateMeta('', $catName, 'description');
          $categoryInfo->seo_keywords = $meta->generateMeta('', $catName, 'keywords');
          $categoryInfo->save();
        }
      } else {
        $products_id = Product::whereIn('category_id', $categories_id)->pluck('id');

        foreach ($products_id as $product_id) {
          $productInfo = ProductTranslation::where('lang_id', $request->lang_id)->where('product_id', $product_id)->firstOrFail();
          $prodName = $productInfo->name;
          $catName = ProductCategoryTranslation::whereIn('product_category_id', $categories_id)->where('lang_id', $request->lang_id)->firstOrFail()->name;

          $productInfo->description = $meta->generateMeta($prodName, $catName, 'product_description');
          $productInfo->seo_title = $meta->generateMeta($prodName, $catName, 'title');
          $productInfo->seo_description = $meta->generateMeta($prodName, $catName, 'description');
          $productInfo->seo_keywords = $meta->generateMeta($prodName, $catName, 'keywords');
          $productInfo->save();
        }
      }

    	session()->flash('message', 'New item has been created!');

    	return redirect()->route('autometa.index')->withInput();
    }

    public function edit($id)
    {
    	$meta = AutoMeta::where('meta_id', $id)->firstOrFail();
    	return view('admin::admin.autometas.edit', ['meta' => $meta, 'lang_id' => $meta->lang_id, 'type' => $meta->type]);
    }

    public function changeCategoryEdit(Request $request)
    {
      $returnHTML = view('admin::admin.autometas.editCategoriesTreeNoMeta')->with(['property' => 0, 'lang_id' => $request->lang_id, 'type' => $request->type, 'meta_id' => $request->meta])->render();
      return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    public function update(Request $request, $id)
    {
      $toValidate = [];
      $toValidate['lang_id'] = 'required';
      $toValidate['autometa_type'] = 'required';
      $toValidate['categories_id'] = 'required';
      $toValidate['name'] = 'required';
      $toValidate['title'] = 'required';
      $toValidate['description'] = 'required';
      $toValidate['keywords'] = 'required';

      $validator = $this->validate($request, $toValidate);

      $newcategories_id = explode(',',$request->categories_id);

      foreach ($newcategories_id as $category_id) {
        $categories = AutoMetaCategory::join('autometas', 'autometa_categories.autometa_id', 'autometas.meta_id')
                      ->where('autometa_id', '!=', $id)
                      ->where('type', $request->type)
                      ->where('category_id', $category_id)
                      ->where('lang_id', $request->lang_id)
                      ->get();

        if(count($categories) > 0) {
          $meta_id = $categories[0]->autometa_id;
          AutoMetaCategory::where('autometa_id', $meta_id)->delete();
          AutoMeta::where('meta_id', $meta_id)->delete();
        }
      }

      $meta = AutoMeta::where('meta_id', $id)->firstOrFail();
      $meta->name = $request->name;
      $meta->seotext = $request->seotext;
      $meta->product_description = $request->product_description;
      $meta->title = $request->title;
      $meta->description = $request->description;
      $meta->keywords = $request->keywords;
      $meta->type = $request->autometa_type;
      $meta->lang_id = $request->lang_id;
      $meta->var1 = $request->var1;
      $meta->var2 = $request->var2;
      $meta->var3 = $request->var3;
      $meta->var4 = $request->var4;
      $meta->var5 = $request->var5;
      $meta->var6 = $request->var6;
      $meta->var7 = $request->var7;
      $meta->var8 = $request->var8;
      $meta->var9 = $request->var9;
      $meta->var10 = $request->var10;
      $meta->var11 = $request->var11;
      $meta->var12 = $request->var12;
      $meta->var13 = $request->var13;
      $meta->var14 = $request->var14;
      $meta->var15 = $request->var15;
      $meta->save();

      if($request->autometa_type == 1) {
        foreach ($newcategories_id as $category_id) {
          $categoryInfo = ProductCategoryTranslation::where('lang_id', $request->lang_id)->where('product_category_id', $category_id)->firstOrFail();
          $catName = $categoryInfo->name;

          $categoryInfo->seo_text = $meta->generateMeta('', $catName, 'seotext');
          $categoryInfo->seo_title = $meta->generateMeta('', $catName, 'title');
          $categoryInfo->seo_description = $meta->generateMeta('', $catName, 'description');
          $categoryInfo->seo_keywords = $meta->generateMeta('', $catName, 'keywords');
          $categoryInfo->save();
        }
      } else {
        $products_id = Product::whereIn('category_id', $newcategories_id)->pluck('id');

        foreach ($products_id as $product_id) {
          $productInfo = ProductTranslation::where('lang_id', $request->lang_id)->where('product_id', $product_id)->firstOrFail();
          $prodName = $productInfo->name;
          $catName = ProductCategoryTranslation::whereIn('product_category_id', $newcategories_id)->where('lang_id', $request->lang_id)->firstOrFail()->name;

          $productInfo->description = $meta->generateMeta($prodName, $catName, 'product_description');
          $productInfo->seo_title = $meta->generateMeta($prodName, $catName, 'title');
          $productInfo->seo_description = $meta->generateMeta($prodName, $catName, 'description');
          $productInfo->seo_keywords = $meta->generateMeta($prodName, $catName, 'keywords');
          $productInfo->save();
        }
      }

      $oldcategories_id = AutoMetaCategory::where('autometa_id', $meta->meta_id)->pluck('category_id')->toArray();

      $deletecategories_id = array_values(array_diff($oldcategories_id, $newcategories_id));
      $insertcategories_id = array_values(array_diff($newcategories_id, $oldcategories_id));

      if(count($deletecategories_id) > 0) {
        AutoMetaCategory::where('autometa_id', $meta->meta_id)->whereIn('category_id', $deletecategories_id)->delete();
      }

      if(count($insertcategories_id) > 0) {
        foreach ($insertcategories_id as $category_id) {
          $autometa_category = new AutoMetaCategory();
          $autometa_category->autometa_id = $meta->meta_id;
          $autometa_category->category_id = $category_id;
          $autometa_category->save();
        }
      }

    	session()->flash('message', 'Item has been updated!');

    	return redirect()->route('autometa.index');
    }

    public function destroy($meta_id)
    {
      AutoMetaCategory::where('autometa_id', $meta_id)->delete();
      AutoMeta::where('meta_id', $meta_id)->delete();

    	session()->flash('message', 'Item has been deleted!');

    	return redirect()->route('autometa.index');
    }

    public function checkAutometasCategory(Request $request)
    {
      $categories_id = Autometa::join('autometa_categories', 'autometas.meta_id', 'autometa_categories.autometa_id')
                      ->where('meta_id', '!=', $request->meta_id)
                      ->where('type', $request->type)
                      ->where('lang_id', $request->lang_id)
                      ->whereIn('category_id', explode(',', $request->categories_id))
                      ->pluck('category_id');

      if(count($categories_id) > 0) {
        return $categories_id;
      }
      return 'false';
    }
}
