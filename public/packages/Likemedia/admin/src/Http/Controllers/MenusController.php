<?php

namespace Admin\Http\Controllers;

use App\Models\Menu;
use App\Models\Post;
use App\Models\Category;
use App\Models\ProductCategory;
use App\Models\Page;
use App\Models\MenuTranslation;
use App\Models\PageTranslation;
use App\Models\CategoryTranslation;
use App\Models\MenuGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class MenusController extends Controller
{
    public function __construct()
    {

        parent::__construct();

        $menus = Menu::all();
        foreach ($menus as $menu):

            $c = Menu::where('parent_id', $menu->id)->first();

            if (is_null($c)) {
                Menu::find($menu->id)->update([
                    'level' => 1,
                ]);
            } else {
                Menu::find($menu->id)->update([
                    'level' => 0,
                ]);
            }
        endforeach;
    }

    public function index()
    {
        // redirect to menu groups
        return redirect()->route('groups.index');
        $menus = Menu::where('level', 1)->get();
        $categories = Category::where('parent_id', 0)->get();
        $pages = Page::with('translation')->where('active', 1)->get();

        return view('admin::admin.menus.index', compact('menus', 'categories', 'pages'));
    }

    public function getMenuByGroup($groupId)
    {
      $menus = Menu::where('level', 1)->get();
      $categories = Category::where('parent_id', 0)->get();
      $pages = Page::with('translation')->where('active', 1)->get();
      $menuGroup = MenuGroup::findOrFail($groupId);
      $productCategories = ProductCategory::where('parent_id', 0)->get();
      $general = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['changeCategory'];
      return view('admin::admin.menus.index', compact('menus', 'categories', 'pages', 'groupId', 'menuGroup', 'productCategories', 'general'));
    }

    public function create(Request $request)
    {
        $menus = Menu::with('translation')->get();
        $categories = Category::where('parent_id', 0)->get();
        $pages = Page::with('translation')->where('active', 1)->get();
        $menuGroup = MenuGroup::where('id', $request->get('group'))->first();

        return view('admin::admin.menus.create', compact('menus', 'categories', 'pages', 'menuGroup'));
    }

    public function store(Request $request)
    {
        $name = time() . '-' . $request->image->getClientOriginalName();
        $request->image->move('images/menus', $name);

        $menu = new menu();
        $menu->parent_id = $request->parent_id;
        $menu->image = $name;
        $menu->group_id = $request->groupId;
        $menu->save();

        foreach ($this->langs as $lang):
            $menu->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'slug' => request('slug_' . $lang->lang),
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang),
                'alt_attribute' => request('alt_text_' . $lang->lang),
                'image_title' => request('title_' . $lang->lang)
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->back();
    }

    public function edit($id)
    {
        $menus = Menu::with('translation')->get();
        $menuItem = Menu::with('translations')->findOrFail($id);
        $categories = Category::where('parent_id', 0)->get();
        $pages = Page::with('translation')->where('active', 1)->get();
        $menuGroup = MenuGroup::where('id', $menuItem->group_id)->first();

        return view('admin::admin.menus.edit', compact('menus', 'menuItem', 'categories', 'pages', 'menuGroup'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $this->addImages($request, $menu->id);

        foreach ($this->langs as $lang):
            $menu->translations()->where('menu_id', $id)->where('lang_id', $lang->id)->update([
                'url' => request('link'),
                'name' => request('name_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->back();

    }

    public function addImages($request, $id)
    {
        $image = $request->file('image');
        $imageHover = $request->file('image-hover');

        if ($image) {
            $icon = $image->getClientOriginalName();
            $image->move('upload/menuIcons', $icon);
        }

        if ($imageHover) {
            $iconHover = $imageHover->getClientOriginalName();
            $imageHover->move('upload/menuIcons', $iconHover);
        }

        $menu = Menu::where('id', $id)->update([
            'icon' => $icon,
            'icon_hover' => $iconHover
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if($id == 0){ $id = $request->parent_id; }

        $menu = Menu::findOrFail($id);

        if ($request->get('with_children') == 'on') {
          // level 1
          if (!is_null($menu)) {
              $parent = $this->deleteOneMenuItem($menu, (int)$id);
              // level 2
              $submenus1 = Category::where('parent_id', $id)->get();
              if (!empty($submenus1)) {
                  foreach ($submenus1 as $submenu1) {
                      $parent = $this->deleteOneMenuItem($submenu1, $parent);
                      // level 3
                      $submenus2 = Category::where('parent_id', $submenu1->id)->get();
                      if (!empty($submenus2)) {
                          foreach ($submenus2 as $key => $submenus2->id) {
                              $parent = $this->deleteOneMenuItem($submenu2, $parent);
                              // level 3
                              $submenus3 = Category::where('parent_id', $submenu2->id)->get();
                              if (!empty($submenus3)) {
                                  foreach ($submenus3 as $key => $submenus3) {
                                      $parent = $this->deleteOneMenuItem($submenu3, $parent);
                                      // level 4
                                      $submenus = Category::where('parent_id', $submenu->id)->get();
                                      if (!empty($submenus)) {
                                          foreach ($submenus as $key => $submenus) {
                                              $parent = $this->deleteOneMenuItem($submenu, $parent);
                                          }
                                      }
                                  }
                              }
                          }
                      }
                  }
              }
          }

        }

        $menu->delete();

        $menu->translations()->delete();

        return redirect()->back();
    }


    public function deleteOneMenuItem($menu, $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return $menu;
    }

    public function partialSave(Request $request)
    {
        if ($request->get('type') == 'category') {
            if ($request->get('subcategories') == 'on') {
                $parentId = $request->get('parent_id') ?? 0;
                return $this->assignmentCategory($request->get('categoryId'), $parentId, $request->get('groupId'));
            }
        }

        $menu = new menu();
        $menu->parent_id = $request->parent_id;
        $menu->group_id = $request->groupId;
        $menu->save();

        foreach ($this->langs as $lang):
            $menu->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'url' => request('link'),
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->back();
    }

    public function assignmentCategory($id, $parentId, $groupId)
    {
        // level 1
        $category = Category::find($id);
        if (!is_null($category)) {
            $parent = $this->addOneMenuItem($category, (int)$parentId, $groupId);
            // level 2
            $subCategories1 = Category::where('parent_id', $id)->get();
            if (!empty($subCategories1)) {
                foreach ($subCategories1 as $subcategory1) {
                    $parent = $this->addOneMenuItem($subcategory1, $parent, $groupId);
                    // level 3
                    $subCategories2 = Category::where('parent_id', $subcategory1->id)->get();
                    if (!empty($subCategories2)) {
                        foreach ($subCategories2 as $key => $subCategory2->id) {
                            $parent = $this->addOneMenuItem($subcategory2, $parent, $groupId);
                            // level 3
                            $subCategories3 = Category::where('parent_id', $subcategory2->id)->get();
                            if (!empty($subCategories3)) {
                                foreach ($subCategories3 as $key => $subCategory3) {
                                    $parent = $this->addOneMenuItem($subcategory3, $parent, $groupId);
                                    // level 4
                                    $subCategories = Category::where('parent_id', $subcategory->id)->get();
                                    if (!empty($subCategories)) {
                                        foreach ($subCategories as $key => $subCategory) {
                                            $parent = $this->addOneMenuItem($subcategory, $parent, $groupId);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        session()->flash('message', 'New item has been created!');

        return redirect()->back();

    }

    public function addOneMenuItem($category, $parent, $groupId)
    {
        $url = '';
        $parentId = $parent;
        $parentId = !is_int($parent) ? $parent->id : $parent;

        $menu = new menu();
        $menu->parent_id = $parentId;
        $menu->group_id = $groupId;

        $menu->save();

        foreach ($this->langs as $lang):
            $url = !is_int($parent) ? $parent->translationByLanguage($lang->id)->first()->url : '';
            $menu->translations()->create([
                'lang_id' => $lang->id,
                'name' => $category->translationByLanguage($lang->id)->first()->name,
                'url' => '/'.$category->translationByLanguage($lang->id)->first()->slug,
            ]);
        endforeach;

        return Menu::find($menu->id);
    }

    public function change()
    {
        $list = Input::get('list');
        $positon = 1;
        $response = true;
        $parentId = 0;
        $childId = 0;

        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $positon++;
                Menu::where('id', $value['id'])->update(['parent_id' => 0, 'position' => $positon]);
                if (array_key_exists('children', $value)) {
                    foreach ($value['children'] as $key1 => $value1) {
                        if (!checkPosts($value['id'])) {
                            $positon++;
                            Menu::where('id', $value1['id'])->update(['parent_id' => $value['id'], 'position' => $positon]);
                        }else{
                            $response = false;
                            $parentId = $value['id'];
                            $childId = $value1['id'];
                        }
                        if (array_key_exists('children', $value1)) {
                            foreach ($value1['children'] as $key2 => $value2) {
                                if (!checkPosts($value1['id'])) {
                                    $positon++;
                                    Menu::where('id', $value2['id'])->update(['parent_id' => $value1['id'], 'position' => $positon]);
                                }else{
                                    $response = false;
                                    $parentId = $value1['id'];
                                    $childId = $value2['id'];
                                }
                                if (array_key_exists('children', $value2)) {
                                    foreach ($value2['children'] as $key3 => $value3) {
                                        if (!checkPosts($value2['id'])) {
                                            $positon++;
                                            Menu::where('id', $value3['id'])->update(['parent_id' => $value2['id'], 'position' => $positon]);
                                        }else{
                                            $response = false;
                                            $parentId = $value2['id'];
                                            $childId = $value3['id'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return  json_encode (['text' => SelectMenusTree(1, 0, $curr_id=null), 'message' => $response, 'parentId' =>  $parentId, 'childId' => $childId]);
    }

    public function cleanMenus()
    {
        $menus = MenuTranslation::get();
        if (!empty($menus)) {
            foreach ($menus as $key => $menu) {
                $page = PageTranslation::where('slug', str_replace('/page/', '', $menu->url))->first();
                $category = CategoryTranslation::where('slug', str_replace('/', '', $menu->url))->first();

                if ((is_null($page)) && (is_null($category))) {
                    $menuItem = Menu::find($menu->menu_id);
                    if (!is_null($menuItem)) {
                        $menusToDelete = MenuTranslation::where('menu_id', $menuItem->id)->get();
                        if (!empty($menusToDelete)) {
                            foreach ($menusToDelete as $key => $menuToDelete) {
                                MenuTranslation::where('id', $menuToDelete->id)->delete();
                            }
                        }
                    }
                }
            }
        }
        session()->flash('message', 'Menus  cleaned');

        return redirect()->route('menus.index');
    }
}
