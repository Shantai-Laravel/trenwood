<?php

function chechSubproductVals($filter, $currentVal, $productId, $itemId){
    $flag = false;
    $vals = [];
    if ($filter) {
        foreach ($filter as $key => $value) {
            $vals[] = $value['valueId'];
        }
    }

    $inactive = DB::table('subproducts')
        ->select('combination')
        ->where('active', 0)
        ->where('product_id', $productId)
        ->get();

        if (count($inactive) > 0) {
            foreach ($inactive as $key => $inactiv) {
                if (!in_array($itemId, $vals)) {
                $comb = (array) json_decode($inactiv->combination);
                    if (diffArray($vals, $comb) && in_array($itemId, $comb)) {
                        return true;
                    }
                }
            }
        }

        return false;
}

function diffArray($vals, $combs)
{
    $rezult = [];
    foreach ($combs as $key => $comb) {
        if (in_array($comb, $vals)) {
            if ($comb !== 0) {
                $rezult[] = $comb;
            }
        }
    }
    if (count($rezult) > 0) {
        return true;
    }
    return false;
}

function checkValue($currentValue, $value, $productId)
{
    if ($currentValue == null) {
        return false;
    }
    $inactive = DB::table('subproducts')
        ->where('active', 0)
        ->where('product_id', $productId)
        ->pluck('combination_id')
        ->toArray();

        if (count($inactive) > 0) {
            foreach ($inactive as $key => $inactiv) {
                $configuration = DB::table('subproduct_combinations')
                    ->where('id', $inactiv)
                    ->first();

                    if (!is_null($configuration)) {
                        $valuesList = $configuration->case_1 + $configuration->case_2 + $configuration->case_3;
                        $existsList = $currentValue + $value;
                        if ($valuesList == $existsList) {
                            return true;
                        }
                    }
            }
        }
        return false;
}
/**
 * @param $category_id
 * @param $lang_id
 * @return boolean
 */
function checkAutometasCategoryEdit($category_id, $lang_id, $type, $meta_id)
{
  $checked = DB::table('autometa_categories')
      ->join('autometas', 'autometa_categories.autometa_id', 'autometas.meta_id')
      ->where('lang_id', $lang_id)
      ->where('type', $type)
      ->where('category_id', $category_id)
      ->where('autometa_id', $meta_id)
      ->get();

  if(count($checked) > 0) {
    return true;
  }
  return false;
}

/**
 * @return int
 */
function genMetaId()
{
  $meta_id = rand(1, 1000);
  $temp_id = DB::table('autometas')->where('meta_id', $meta_id)->get();
  if(count($temp_id) > 0) {
    genMetaId();
  } else {
    return $meta_id;
  }
}

function checkAutometasCategoryCreate($category_id, $lang_id, $type){
    $row = DB::table('autometa_categories')
        ->join('autometas', 'autometa_categories.autometa_id', 'autometas.meta_id')
        ->where('lang_id', $lang_id)
        ->where('type', $type)
        ->where('category_id', $category_id)
        ->first();

    if (!is_null($row)) {
        return true;
    }
    return false;
}


function Label($pageId, $langId, $number)
{
    $table = "traductions";

    $row = DB::table($table)
        ->select('value')
        ->join('traductions_translations', 'traductions_translations.traduction_id', '=', $table . '.id')
        ->where('lang_id', $langId)
        ->where('number', $number)
        ->where('page_id', $pageId)
        ->first();

        if (!is_null($row)) {
            return $row->value;
        }

        return false;
}


function GetParameter($key, $langId)
{
    $table = "product_properies";

    $row = DB::table($table)
        ->select('name')
        ->join('product_properies_translation', 'product_properies_translation.property_id', '=', $table . '.id')
        ->where('lang_id', $langId)
        ->where('key', $key)
        ->first();

        if (!is_null($row)) {
            return $row->name;
        }

        return false;
}

function ParameterId($key)
{
    $table = "product_properies";

    $row = DB::table($table)
        ->select('id')
        ->where('key', $key)
        ->first();

        if (!is_null($row)) {
            return $row->id;
        }

        return false;
}


function GetParamValue($property_id, $product_id, $langId = 0)
{
    $table = "property_values";

    $row = DB::table($table)
        ->select('value')
        ->join('property_values_translation', 'property_values_translation.property_values_id', '=', $table . '.id')
        ->where('lang_id', $langId)
        ->where('property_id', $property_id)
        ->where('product_id', $product_id)
        ->first();

        if (!is_null($row)) {
            return $row->value;
        }

        return false;
}

function GetGallery($shot_code, $langId)
{
    $gallery = DB::table('galleries')
        ->select('id')
        ->where('alias', $shot_code)
        ->first();

    if (!is_null($gallery)) {

        $table = "gallery_images";

        $row = DB::table($table)
            ->join('gallery_images_translation', 'gallery_images_translation.gallery_image_id', '=', $table . '.id')
            ->where('lang_id', $langId)
            ->where('gallery_id', $gallery->id)
            ->limit(4)
            ->get();

            return $row;
    }

        return false;
}

function GetGalleryById($id, $langId)
{
    $gallery = DB::table('galleries')
        ->select('id')
        ->where('id', $id)
        ->first();

    if (!is_null($gallery)) {

        $table = "gallery_images";

        $row = DB::table($table)
            ->join('gallery_images_translation', 'gallery_images_translation.gallery_image_id', '=', $table . '.id')
            ->where('lang_id', $langId)
            ->where('gallery_id', $gallery->id)
            ->limit(4)
            ->get();

            return $row;
    }

        return false;
}

function getProductsByCategory($categoryId, $langId)
{
    $table = "products";

    $row = DB::table($table)
        ->join('product_translations', 'product_translations.product_id', '=', $table . '.id')
        ->where('lang_id', $langId)
        ->where('category_id', $categoryId)
        ->get();

        if (!is_null($row)) {
            return $row;
        }

        return false;
}

function getMainProductImage($productId, $lang_id)
{
    $table = "product_images";

    $row = DB::table($table)
        ->join('product_images_translation', 'product_images_translation.product_image_id', '=', $table . '.id')
        ->where('product_id', $productId)
        ->where('lang_id', $lang_id)
        ->first();

        if (!is_null($row)) {
            return $row;
        }

        return null;
}

function getMainSubProductImage($subproductId)
{
    $table = "subproducts";

    $row = DB::table($table)
        ->where('id', $subproductId)
        ->where('image', '!=', null)
        ->first();

        if (!is_null($row)) {
            return $row;
        }

        return null;
}

function getMultiData($propertyId, $langId)
{
    $table = "property_multidatas";

    $row = DB::table($table)
        ->join('property_multidatas_translation', 'property_multidatas_translation.property_multidata_id', '=', $table . '.id')
        ->where('property_id', $propertyId)
        ->where('lang_id', $langId)
        ->get();

    return $row;
}

function getMultiDataList($propertyId, $langId)
{
    $table = "property_multidatas";

    $row = DB::table($table)
        ->select('property_multidata_id', 'name', 'property_multidatas.id', 'value')
        ->join('property_multidatas_translation', 'property_multidatas_translation.property_multidata_id', '=', $table . '.id')
        ->where('property_multidata_id', $propertyId)
        ->where('lang_id', $langId)
        ->first();

    return $row;
}

function getPropertiesData($productId, $propertyId)
{
    $table = "property_values";

    $row = DB::table($table)
        ->join('property_values_translation', 'property_values_translation.property_values_id', '=', $table . '.id')
        // ->where('lang_id', $langId)
        ->where('product_id', $productId)
        ->where('property_id', $propertyId)
        ->first();

    if (!is_null($row)) {
        if (isJson($row->value)) {
            return json_decode($row->value);
        }
        return $row->value;
    }

    return null;
}

function getCategories($parent_id, $lang_id) {
  $row = DB::table('product_categories')
      ->join('product_categories_translation', 'product_categories_translation.product_category_id', '=', 'product_categories.id')
      ->where('parent_id', $parent_id)
      ->where('lang_id', $lang_id)
      ->get();
    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getMainMenu($level, $lang_id, $menu_id) {
    if($menu_id) {
      $row = DB::table('menus')
          ->join('menus_translation', 'menus.id', '=', 'menus_translation.menu_id')
          ->where('level', $level)
          ->where('lang_id', $lang_id)
          ->where('parent_id', $menu_id)
          ->get();
    } else {
      $row = DB::table('menus')
          ->join('menus_translation', 'menus.id', '=', 'menus_translation.menu_id')
          ->where('level', $level)
          ->where('lang_id', $lang_id)
          ->where('parent_id', 0)
          ->get();
    }

    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getHitProducts($lang_id) {
  $row = DB::table('products')
      ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
      ->where('lang_id', $lang_id)
      ->where('hit', 1)
      ->orderBy('products.created_at', 'desc')
      ->limit(15)
      ->select('products.*', 'products.alias as productAlias','product_translations.*')
      ->get();

    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getProductImages($product_id, $lang_id) {
  $row = DB::table('product_images')
      ->join('product_images_translation', 'product_images.id', '=', 'product_images_translation.product_image_id')
      ->where('lang_id', $lang_id)
      ->where('product_id', $product_id)
      ->get();
    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getPromotionProducts($lang_id) {
  $row = DB::table('products')
      ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
      ->where('lang_id', $lang_id)
      ->where('promotion_id', '!=', 0)
      ->orderBy('products.created_at', 'desc')
      ->select('products.*', 'products.alias as productAlias','product_translations.*')
      ->paginate(12);
    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getRecomendedProducts($lang_id) {
  $row = DB::table('products')
      ->join('product_translations', 'products.id', '=', 'product_translations.product_id')
      ->where('lang_id', $lang_id)
      ->where('recomended', 1)
      ->orderBy('products.created_at', 'desc')
      ->limit(15)
      ->select('products.*', 'products.alias as productAlias','product_translations.*')
      ->get();

     if (!empty($row)) {
        return $row;
    }
    return false;
}

function getAdvices($lang_id) {
  $row = DB::table('posts')
      ->join('posts_translation', 'posts.id', '=', 'posts_translation.post_id')
      ->where('lang_id', $lang_id)
      ->orderBy('posts.created_at', 'desc')
      ->limit(3)
      ->get();
    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getPromosImage($lang_id) {
  $row = DB::table('promotions')
      ->join('promotions_translation', 'promotions.id', '=', 'promotions_translation.promotion_id')
      ->where('lang_id', $lang_id)
      ->orderBy('promotions.created_at', 'desc')
      ->limit(5)
      ->get();
    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getBrandsImage($lang_id) {
  $row = DB::table('brands')
      ->join('brands_translation', 'brands.id', '=', 'brands_translation.brand_id')
      ->where('lang_id', $lang_id)
      ->orderBy('brands.created_at', 'desc')
      ->get();
    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getCategoryName($alias, $lang_id) {
  $category = DB::table('product_categories')
      ->join('product_categories_translation', 'product_categories.id', '=', 'product_categories_translation.product_category_id')
      ->where('lang_id', $lang_id)
      ->where('alias', $alias)
      ->first();
    if (count($category) > 0) {
        return $category->name;
    }
    return false;
}

function getParentCategory($category_id, $lang_id) {
    $categoryArr = [];

    if(count(hasParent($category_id, $lang_id)) > 0) {
      $temp = hasParent($category_id);
      array_push($categoryArr, $temp->alias);

      if(count(hasParent($temp->parent_id, $lang_id)) > 0) {
        $temp = hasParent($temp->parent_id);
        array_push($categoryArr, $temp->alias);

        if(count(hasParent($temp->parent_id, $lang_id)) > 0) {
          $temp = hasParent($temp->parent_id);
          array_push($categoryArr, $temp->alias);
        }
      }
    }
    return implode('/', array_reverse($categoryArr));
}

function hasParent($category_id) {
    $hasParent = DB::table('product_categories')
          ->where('id', $category_id)
          ->first();
    return $hasParent;
}

function getContactInfo($title) {
    $contactModel = new App\Models\Contact();
    $row = $contactModel->where('title', $title)->first();
    return $row;
}

function getCeneralInfo($title) {
    $contactModel = new App\Models\General();
    $row = $contactModel->where('name', $title)->first();
    return $row;
}

function getYouTubeId($url)
{
  $url_string = parse_url($url, PHP_URL_QUERY);
  parse_str($url_string, $args);
  return isset($args['v']) ? $args['v'] : false;
}

function pathWithoutLang($path, $langs)
{
    $pathWithBar = '|'.$path;

    if (!empty($langs)) {
        foreach ($langs as $key => $lang) {
            if (strpos($pathWithBar, '|'.$lang->lang) !== false) {
                return substr($path, 3);
            } else {
                continue;
            }
        }
    }
}

function getProducts()
{
    $productModel = new App\Models\Product();
    $row = $productModel::all();
    return $row;
}

function checkProductsSimilar($product_id, $category_id) {
  $row = DB::table('products_similar')
        ->where('product_id', $product_id)
        ->where('category_id', $category_id)
        ->first();

  if(count($row) > 0) {
    return true;
  }
  return false;
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function checkProductInProperty($propertyId, $productsArr)
{
    $table = "property_values";

    $row = DB::table($table)
        ->select('id')
        ->where('value_id', '!=', 0)
        ->where('property_id', $propertyId)
        ->whereIn('product_id', $productsArr)
        ->first();

    if (!is_null($row)) {
        return $row->id;
    }

    return false;
}

function checkProductInPropertyValue($propertyId, $valueId, $productsArr)
{

    $table = "property_values";

    $row = DB::table($table)
        ->select('id')
        ->where('property_id', $propertyId)
        ->where('value_id', $valueId)
        ->whereIn('product_id', $productsArr)
        ->first();

    if (!is_null($row)) {
        return $row->id;
    }

    return false;
}


function getBrandsChilds($brand_id)
{
  $row = DB::table('brands')
      ->join('brands_translation', 'brands_translation.brand_id', 'brands.id')
      ->where('lang_id', 1)
      ->where('parent_id', $brand_id)
      ->get();

  if(!empty($row)) {
    return $row;
  }

  return false;
}

function getBrands($langId)
{
  $row = DB::table('brands')
      ->join('brands_translation', 'brands_translation.brand_id', 'brands.id')
      ->where('lang_id', 1)
      ->where('parent_id', 0)
      ->get();

  if(!empty($row)) {
    return $row;
  }

  return false;
}


function getSolutions($langId)
{
  $row = DB::table('solutions')
      ->join('solutions_translation', 'solutions_translation.solution_id', 'solutions.id')
      ->where('lang_id', 1)
      ->get();


  if(!empty($row)) {
      return $row;
  }

  return false;
}

function getProductLink($categoryId)
{
  $subcat = DB::table('product_categories')
      ->select('alias', 'parent_id')
      ->where('id', $categoryId)
      ->first();

  if(!is_null($subcat)) {
      $cat = DB::table('product_categories')
          ->select('alias')
          ->where('id', $subcat->parent_id)
          ->first();

      if(!is_null($cat)) {
         return $cat->alias.'/'.$subcat->alias.'/';
      }
  }

  return false;
}

// function getCartProducts($userId)
// {
//     $carts = DB::where('user_id', $userId)->get();
//     if (!empty($carts)) {
//
//     }
//   $row = DB::table('brands')
//       ->join('brands_translation', 'brands_translation.brand_id', 'brands.id')
//       ->where('lang_id', 1)
//       ->where('parent_id', 0)
//       ->get();
//
//   if(!empty($row)) {
//     return $row;
//   }
//
//   return false;
// }

function checkProductInProprty($propertyId, $productsArr)
{
    $table = "property_values";

    $row = DB::table($table)
        ->select('id')
        ->where('value_id', '!=', 0)
        ->where('property_id', $propertyId)
        ->whereIn('product_id', $productsArr)
        ->first();

    if (!is_null($row)) {
        return $row->id;
    }

    return false;
}

function checkProductInProprtyValue($propertyId, $valueId, $productsArr)
{
    $table = "property_values";

    $row = DB::table($table)
        ->select('id')
        ->where('property_id', $propertyId)
        ->where('value_id', $valueId)
        ->whereIn('product_id', $productsArr)
        ->first();

    if (!is_null($row)) {
        return $row->id;
    }

    return false;
}


function getSubcats($categoryId, $langId)
{
    $table = "product_categories";

    $row = DB::table($table)
        ->join('product_categories_translation', 'product_categories_translation.product_category_id', '=', $table . '.id')
        ->where('lang_id', $langId)
        ->where('parent_id', $categoryId)
        ->get();

    if (!is_null($row)) {
        return $row;
    }

    return false;
}

function getParamCategory($param, $categ)
{
    $table = "subproduct_properties";

    $row = DB::table($table)
        ->where('product_category_id', $categ)
        ->where('property_id', $param)
        ->first();

    if (!is_null($row)) {
        return $row;
    }

    return null;
}

function getParamById($property_id, $lang_id) {
    $table = "product_properies";

    $row = DB::table($table)
        ->join('product_properies_translation', 'product_properies_translation.property_id', '=', $table . '.id')
        ->where('product_properies.id', $property_id)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($row)) {
        return $row;
    }

    return null;
}

function getParamValueById($property_id, $lang_id) {
    $table = "property_multidatas";

    $row = DB::table($table)
        ->join('property_multidatas_translation', 'property_multidatas_translation.property_multidata_id', '=', $table . '.id')
        ->where('property_multidatas.id', $property_id)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($row)) {
        return $row;
    }

    return null;
}

function getLangById($langId) {
    $table = "lang";

    $row = DB::table($table)
        ->where('id', $langId)
        ->first();

    if (!is_null($row)) {
        return $row;
    }

    return null;
}

function getFullParameterById($property_id, $product_id, $langId = 0)
{
    $property = DB::table('product_properies_translation')
        ->select('id', 'name')
        ->where('lang_id', $langId)
        ->where('property_id', $property_id)
        ->first();

    if (!is_null($property)) {
        $valueId = DB::table('property_values')
            ->select('value_id')
            ->where('property_id', $property_id)
            ->where('product_id', $product_id)
            ->first();

        if (!is_null($valueId)) {
            $value = DB::table('property_multidatas')
                ->select('value')
                ->join('property_multidatas_translation', 'property_multidatas_translation.property_multidata_id', '=','property_multidatas.id')
                ->where('lang_id', $langId)
                ->where('property_multidatas.id', $valueId->value_id)
                ->first();

            if (!is_null($value)) {
                $data['prop'] = $property->name;
                $data['val'] = $value->value;
                return $data;
            }
        }
    }
    return false;
}

function propByCombination($subproductId)
{
    $array[1] = '';
    $array[2] = '';
    $array[3] = '';

    $subproduct = DB::table('subproducts')
        ->select('combination')
        ->where('id', $subproductId)
        ->first();

    if (!is_null($subproduct)) {
        $combination = (array) json_decode($subproduct->combination);

        if (count($combination) > 0) {
            foreach ($combination as $key => $item) {

                $val = DB::table('property_multidatas_translation')
                        ->select('value')
                        ->where('property_multidata_id', (int) $item)
                        ->first();

                if (!is_null($val)) {
                    $array[$key] = $val->value;
                }
            }
        }
    }

    return $array;
}

?>
