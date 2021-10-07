<?php

require('helpersNew.php');

function getPage($alias, $langId)
{
    $page = DB::table('pages')
        ->join('pages_translation', 'pages_translation.page_id', '=', 'pages.id')
        ->where('active', 1)
        ->where('alias', $alias)
        ->where('lang_id', $langId)
        ->first();

    return $page;
}

function getHeaderMenu($langId){
    $table = "menus_groups";

    $group = DB::table($table)
        ->where('slug', 'headerMenu')
        ->first();

    if (!is_null($group)) {
        $menus = DB::table('menus')
            ->join('menus_translation', 'menus_translation.menu_id', '=', 'menus.id')
            ->where('group_id', $group->id)
            ->where('lang_id', $langId)
            ->get();

        if (!empty($menus)) {
            return $menus;
        }
    }

    return null;
}

function getLangsUrl($lang)
{
    $defaultLang =  DB::table('lang')->where('default', 1)->first();
    $allLangs = DB::table('lang')->get();

    $urlLang = "";

    if ($lang == $defaultLang->lang) {

        dd(request()->url());
    }else{

    }

    $url = $urlLang;

    if (request()->segment(2)) {
        $url = $urlLang.'/'.request()->segment(1).'/'.request()->segment(2);
    }elseif (request()->segment(3)) {
        $url = $urlLang.'/'.request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3);
    }elseif (request()->segment(4)) {
        $url = $urlLang.'/'.request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3).'/'.request()->segment(4);
    }

    return $url;
}


// end of new functions

function isJson($string) {
     json_decode($string);
     return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Verify if element has name
 * @param $id
 * @param $lang_id
 * @param $table
 * @return mixed
 */
function IfHasName($id, $lang_id, $table)
{
    $table_id = $table . "_id";

    $row = DB::table($table)
        ->select('name')
        ->where($table_id, $id)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($row)) {
        $row = $row->name;
    } else {
        $row = '';
    }
    return $row;
}

/**
 * Get max value of position
 * @param $table
 * @return mixed
 */
function GetMaxPosition($table)
{

    $row = DB::table($table)
        ->max('position');

    return $row;
}

/**
 * Resize image by max size
 */
function resizeIMGbyMaxSize($relative_path_to_file, $relative_output_to_file, $file_name, $maxsize, $rgb = 0xFFFFFF, $quality = 90)
{

    $src = DOC_ROOT . $relative_path_to_file . $file_name;
    $dest = DOC_ROOT . $relative_output_to_file . $file_name;

    if (!file_exists($src)) return false;

    $size = @getimagesize($src);


    if ($size === false) return false;

    $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
    $icfunc = "imagecreatefrom" . $format;
    if (!function_exists($icfunc)) return false;

    if ($size[0] > $size[1]) {
        $ratio = $size[0] / $size[1];

        $new_width = $maxsize;
        $new_height = floor($maxsize / $ratio);
    } else {
        $ratio = $size[1] / $size[0];

        $new_height = $maxsize;
        $new_width = floor($maxsize / $ratio);
    }

    $isrc = $icfunc($src);
    $idest = imagecreatetruecolor($new_width, $new_height);

    imagefill($idest, 0, 0, $rgb);
    imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);

    imagejpeg($idest, $dest, $quality);

    imagedestroy($isrc);
    imagedestroy($idest);

    return true;

}

/**
 * @param $menu_id
 * @return null
 */
function GetPidId($menu_id, $table)
{
    $query = DB::table($table)
        ->select('p_id')
        ->where('id', $menu_id)
        ->first();
    if (!is_null($query)) {
        $query = $query->p_id;
    } else {
        $query = null;
    }
    return $query;
}

/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */
function SelectGoodsCatsTree($lang_id, $id, $curr_id = null, $level = 0)
{

    $menu_id_by_level = DB::table('categories')
        ->where('deleted', 0)
        ->where('parent_id', $id)
        ->orderBy('position', 'asc')
        ->get();


    $menu_by_level = [];
    foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {
        $menu_by_level[$key] = DB::table('categories_translation')
            ->join('categories', 'categories_translation.category_id', '=', 'categories.id')
            ->where('category_id', $one_menu_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $result = array();

    $menu_by_level = array_filter($menu_by_level);
    $level++;

    if (sizeof($menu_by_level) > 0) {
        $result[] = '<ol class="dd-list">';
        foreach ($menu_by_level as $entry) {

            $edit = route('categories.edit', $entry->category_id);
            $delete = route('categories.destroy', $entry->category_id);

            if ((!checkPosts($entry->id)) && ($level != 4)) {
                $addNew = '#addCategory';
                $postsLink = '';
            } else {
                $addNew = '#warning';
                $postsLink = '<a href="/back/posts/category/' . $entry->category_id . '"><i class="fa fa-bars"></i></a>';
            }

            $result[] = sprintf(
                '<li class="dd-item dd3-item" data-id="' . $entry->category_id . '">
                %s
                <div class="dd-handle dd3-handle">
                <i class="fa fa-bars"></i>
                </div><div class="dd3-content">
                </div>
                %s
            </li>',
                '<span>' . $entry->name . '</span><div class="buttons">

              ' . $postsLink . '

               <a href="' . $edit . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>

               <a href=""><i class="fa fa-eye" aria-hidden="true"></i></a>

               <a class="btn-link modal-id" data-toggle="modal" data-target="' . $addNew . '" data-id="' . $entry->category_id . '" data-name="' . $entry->name . '">
               <i class="fa fa-plus" aria-hidden="true"></i>
               </a>

               <form method="post" action=" ' . $delete . '">
                 ' . csrf_field() . method_field("DELETE") . '
               <button type="submit" class="btn-link"><a class="modal-id" data-toggle="modal" data-target="' . $addNew . '_delete" data-id="' . $entry->category_id . '" data-name="' . $entry->name . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a></button>
               </form>

           </div>',

                SelectGoodsCatsTree($lang_id, $entry->category_id, 0, $level)
            );
        }
        $result[] = '</ol>';
    }

    return implode($result);
}


/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */
function SelectMenusTree($lang_id, $id, $curr_id = null, $level = 0, $groupId)
{
    $menu_id_by_level = DB::table('menus')
        ->where('group_id', $groupId)
        ->where('parent_id', $id)
        ->orderBy('position', 'asc')
        ->get();


    $menu_by_level = [];
    foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {
        $menu_by_level[$key] = DB::table('menus_translation')
            ->join('menus', 'menus_translation.menu_id', '=', 'menus.id')
            ->where('menu_id', $one_menu_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $result = array();

    $menu_by_level = array_filter($menu_by_level);
    $level++;

    if (sizeof($menu_by_level) > 0) {
        $result[] = '<ol class="dd-list">';
        foreach ($menu_by_level as $entry) {

            $edit = route('menus.edit', $entry->menu_id);
            $delete = route('menus.destroy', $entry->menu_id);

            if ((!checkPosts($entry->id)) && ($level != 4)) {
                $addNew = '#addCategory';
            } else {
                $addNew = '#warning';
            }

            $result[] = sprintf(
                '<li class="dd-item dd3-item" data-id="' . $entry->menu_id . '">
                %s
                <div class="dd-handle dd3-handle">
                <i class="fa fa-bars"></i>
                </div><div class="dd3-content">
                </div>
                %s
            </li>',
                '<span>' . $entry->name .' - ' . $entry->url. '</span><div class="buttons">

               <a href="' . $edit . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>

               <a href=""><i class="fa fa-eye" aria-hidden="true"></i></a>

               <a class="btn-link modal-id" data-toggle="modal" data-target="' . $addNew . '" data-id="' . $entry->menu_id . '" data-name="' . $entry->name . '">
               <i class="fa fa-plus" aria-hidden="true"></i>
               </a>

               <form method="post" action=" ' . $delete . '">
                 ' . csrf_field() . method_field("DELETE") . '
               <button type="submit" class="btn-link"><a class="modal-id" data-toggle="modal" data-target="' . $addNew . '_delete" data-id="' . $entry->menu_id . '" data-name="' . $entry->name . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a></button>
               </form>

           </div>',

                SelectMenusTree($lang_id, $entry->menu_id, 0, $level, $groupId)
            );
        }
        $result[] = '</ol>';
    }

    return implode($result);
}

/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */
function SelectProductCategoriesTree($lang_id, $id, $curr_id = null, $level = 0)
{
    $menu_id_by_level = DB::table('product_categories')
        ->where('parent_id', $id)
        ->orderBy('position', 'asc')
        ->get();


    $menu_by_level = [];
    foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {
        $menu_by_level[$key] = DB::table('product_categories_translation')
            ->join('product_categories', 'product_categories_translation.product_category_id', '=', 'product_categories.id')
            ->where('product_category_id', $one_menu_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $result = array();

    $menu_by_level = array_filter($menu_by_level);
    $level++;

    if (sizeof($menu_by_level) > 0) {
        $result[] = '<ol class="dd-list">';
        foreach ($menu_by_level as $entry) {

            $edit = route('product-categories.edit', $entry->product_category_id);
            $delete = route('product-categories.destroy', $entry->product_category_id);
            // $eye = route('product-categories.destroy', $entry->product_category_id);


            if ((!checkProducts($entry->id)) && ($level != 4)) {
                $addNew = '#addCategory';
                $postsLink = '';
            } else {
                $addNew = '#warning';
                $postsLink = '<a href="/back/products/category/' . $entry->product_category_id . '"><i class="fa fa-bars"></i></a>';
            }

            $result[] = sprintf(
                '<li class="dd-item dd3-item" data-id="' . $entry->product_category_id . '">
                %s
                <div class="dd-handle dd3-handle">
                <i class="fa fa-bars"></i>
                </div><div class="dd3-content">
                </div>
                %s
            </li>',
                '<span>' . $entry->name .' - ' . $entry->url. '</span><div class="buttons">
                '. $postsLink .'
               <a href="' . $edit . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>

               <a href=""><i class="fa fa-eye" aria-hidden="true"></i></a>

               <a class="btn-link modal-id" data-toggle="modal" data-target="' . $addNew . '" data-id="' . $entry->product_category_id . '" data-name="' . $entry->name . '">
               <i class="fa fa-plus" aria-hidden="true"></i>
               </a>

               <form method="post" action=" ' . $delete . '">
                 ' . csrf_field() . method_field("DELETE") . '
               <button type="submit" class="btn-link"><a class="modal-id" data-toggle="modal" data-target="' . $addNew . '_delete" data-id="' . $entry->product_category_id . '" data-name="' . $entry->name . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a></button>
               </form>

           </div>',

                SelectProductCategoriesTree($lang_id, $entry->product_category_id, 0, $level)
            );
        }
        $result[] = '</ol>';
    }

    return implode($result);
}


/**
 * @param $lang_id
 * @param $id
 * @return string
 */
function SelectCatsTree($lang_id, $id)
{
    $categories = DB::table('categories_translation')
        ->join('categories', 'categories_translation.category_id', '=', 'categories.id')
        ->where('parent_id', $id)
        ->where('lang_id', $lang_id)
        ->get();

    return $categories ?? null;
}

/**
 * @param $lang_id
 * @param $id
 * @return string
 */
function SelectProdsCatsTree($lang_id, $id)
{
    $categories = DB::table('product_categories_translation')
        ->join('product_categories', 'product_categories_translation.product_category_id', '=', 'product_categories.id')
        ->where('parent_id', $id)
        ->where('lang_id', $lang_id)
        ->orderBy('position', 'asc')
        ->get();

    return $categories ?? null;
}

function checkProducts($id)
{
    $row = DB::table('products')
        ->where('category_id', $id)
        ->first();

    if (!is_null($row)) {
        return true;
    }
    return false;
}

function checkPosts($id)
{
    $row = DB::table('posts')
        ->where('category_id', $id)
        ->first();

    if (!is_null($row)) {
        return true;
    }
    return false;
}

function checkPropertyCat($category_id, $property_id){
    $row = DB::table('properties_categories')
        ->where('property_id', $property_id)
        ->where('category_id', $category_id)
        ->first();

    if (!is_null($row)) {
        return true;
    }
    return false;
}

function checkPropertyCatGroup($category_id, $group_id){
    $properties = DB::table('product_properies')
        ->select('id')
        ->where('group_id', $group_id)
        ->get();

    if (!empty($properties)) {
        foreach ($properties as $key => $property) {
            $row = DB::table('properties_categories')
                ->select('id')
                ->where('property_id', $property->id)
                ->where('category_id', $category_id)
                ->first();

            if (is_null($row)) {
                return false;
            }
        }
    }

    return true;
}


 function getModuleName($src, $lang)
 {
     $query = DB::table('modules')
         ->where('src', $src)
         ->first();

     dd($query);

     if (!is_null($query)) {
         $name = 'name';
         $icon = "<i class='fa " . $query->icon . "'></i>";
         return $icon . '  ' . $query->$name;
     }
     return '';
 }

 function countTableItems($table)
 {
     return count(DB::table($table)->get());
 }


  function hasSubmodule($id)
  {
      $table = "modules";

      $row = DB::table($table)
          ->where('parent_id', $id)
          ->get();

      return $row;
  }
