<?php

namespace Admin\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class CategoriesController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $categories = Category::all();
        foreach ($categories as $category):

            $c = Category::where('parent_id', $category->id)->first();

            if (is_null($c)) {
                Category::find($category->id)->update([
                    'level' => 1,
                ]);
            } else {
                Category::find($category->id)->update([
                    'level' => 0,
                ]);
            }
        endforeach;
    }

    public function index()
    {
        $categories = Category::with('translation')->where('level', 1)->get();

        return view('admin::admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::with('translation')->get();

        return view('admin::admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $name = time() . '-' . $request->image->getClientOriginalName();
        $request->image->move('images/categories', $name);

        $category = new Category();
        $category->parent_id = $request->parent_id;
        $category->image = $name;
        $category->save();

        foreach ($this->langs as $lang):
            $category->translations()->create([
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

        return redirect()->route('categories.index');
    }

    public function edit($id)
    {
        $category = Category::with('translations')->findOrFail($id);

        return view('admin::admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['name_'.$lang->lang] = 'required|max:255';
            $toValidate['slug_'.$lang->lang] = 'required|max:255';
        }

        $category = Category::findOrFail($id);

        // if ($request->file('image') != null) {
        //     if (file_exists('/images/categories/' . $category->image)) {
        //         unlink('/images/categories/' . $category->image);
        //     }
        //     $name = time() . '-' . $request->image->getClientOriginalName();
        //     $request->image->move('images/categories', $name);
        //
        //     $category->image = $name;
        // }

        if ($request->file('image')) {
            $name = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move('images/categories', $name);
            Category::where('id', $id)->update(['image' => $name]);
        }

        $category->translations()->delete();

        foreach ($this->langs as $lang):
            $category->translations()->create([
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

        return redirect()->route('categories.index');

        dd($id);
    }

    public function destroy(Request $request, $id)
    {
        if($id == 0){
            $id = $request->parent_id;
            $posts = Post::where('category_id', $id)->get();

            $addToId = $request->add;
            if (!empty($posts)) {
                if ($addToId != 0) {
                    if (!empty($posts)) {
                        foreach ($posts as $key => $post) {
                            Post::where('id', $post->id)->update([
                                'category_id' => $addToId,
                            ]);
                        }
                    }
                }else{
                    foreach ($posts as $key => $post) {
                        Post::where('id', $post->id)->delete();
                    }
                }
            }
        }


        $category = Category::findOrFail($id);

        $categories = Category::all();

        foreach ($categories as $cat):
            if ($category->id == $cat->parent_id) {
                session()->flash('message', 'Can\'t delete this item, because it has children.');

                return redirect()->route('categories.index');
            }
        endforeach;

        if (file_exists('/images/categories/' . $category->image)) {
            unlink('/images/categories/' . $category->image);
        }

        $category->delete();

        return redirect()->back();
    }

    public function partialSave(Request $request)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['name_'.$lang->lang] = 'required|max:255';
            $toValidate['slug_'.$lang->lang] = 'required|unique:categories_translation,slug|max:255';
        }

        $validator = $this->validate($request, $toValidate);
        $category = new Category();
        $category->parent_id = $request->parent_id;
        $category->save();

        foreach ($this->langs as $lang):
            $category->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'slug' => request('slug_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('categories.index');
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
                Category::where('id', $value['id'])->update(['parent_id' => 0, 'position' => $positon]);
                if (array_key_exists('children', $value)) {
                    foreach ($value['children'] as $key1 => $value1) {
                        if (!checkPosts($value['id'])) {
                            $positon++;
                            Category::where('id', $value1['id'])->update(['parent_id' => $value['id'], 'position' => $positon]);
                        }else{
                            $response = false;
                            $parentId = $value['id'];
                            $childId = $value1['id'];
                        }
                        if (array_key_exists('children', $value1)) {
                            foreach ($value1['children'] as $key2 => $value2) {
                                if (!checkPosts($value1['id'])) {
                                    $positon++;
                                    Category::where('id', $value2['id'])->update(['parent_id' => $value1['id'], 'position' => $positon]);
                                }else{
                                    $response = false;
                                    $parentId = $value1['id'];
                                    $childId = $value2['id'];
                                }
                                if (array_key_exists('children', $value2)) {
                                    foreach ($value2['children'] as $key3 => $value3) {
                                        if (!checkPosts($value2['id'])) {
                                            $positon++;
                                            Category::where('id', $value3['id'])->update(['parent_id' => $value2['id'], 'position' => $positon]);
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

        return  json_encode (['text' => SelectGoodsCatsTree(1, 0, $curr_id=null), 'message' => $response, 'parentId' =>  $parentId, 'childId' => $childId]);
    }

    public function movePosts(Request $request)
    {
        $category = new Category();
        $category->parent_id = $request->parent_id;
        $category->save();

        foreach ($this->langs as $lang):
            $category->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
                'slug' => request('slug_' . $lang->lang),
            ]);
        endforeach;

        $posts = Post::where('category_id', $request->parent_id)->get();

        $addToId = $category->id;

        if ($request->add != 0) {
            $addToId = $request->add;
        }

        if (!empty($posts)) {
            foreach ($posts as $key => $post) {
                Post::where('id', $post->id)->update([
                    'category_id' => $addToId,
                ]);
            }
        }

        session()->flash('message', 'New item has been created!');

        return redirect()->route('categories.index');
    }

    public function movePosts_(Request $request)
    {
        // dd($request->all());
        $posts = Post::where('category_id', $request->parent_id)->get();

        $addToId = $request->add;

        if (!empty($posts)) {
            foreach ($posts as $key => $post) {
                Post::where('id', $post->id)->update([
                    'category_id' => $addToId,
                ]);
            }
        }

        Category::where('id', $request->child_id)->update([
            'parent_id' =>  $request->parent_id,
        ]);

        session()->flash('message', 'New item has been created!');

        return redirect()->route('categories.index');
    }

}
