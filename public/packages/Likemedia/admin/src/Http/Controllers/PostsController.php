<?php

namespace Admin\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['translation', 'tags'])->paginate(15);

        return view('admin::admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('level', 1)->get();

        $tags = Tag::distinct()->get(['name', 'lang_id']);

        return view('admin::admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['title_'.$lang->lang] = 'required|max:255';
            $toValidate['url_'.$lang->lang] = 'required|unique:posts_translation,slug|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        foreach ($this->langs as $lang):
            $name[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $name[$lang->lang] = time() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/posts', $name[$lang->lang]);
            }
        endforeach;

        $post = new Post();
        $post->category_id = $request->category_id;
        $post->image = $name['ro'];

        $post->save();


        foreach ($this->langs as $lang):
            $post->translations()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'slug' => request('slug_' . $lang->lang),
                'url' => request('url_' . $lang->lang),
                'meta_h1' => request('meta_h1_' . $lang->lang),
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang),
                'video' => request('video_' . $lang->lang),
                'image' => $name[$lang->lang],
                'image_title' => request('img_title_' . $lang->lang),
                'image_alt' => request('img_alt_' . $lang->lang),
            ]);

            if ( (request('tag_' . $lang->lang) != null) && !(request('tag_' . $lang->lang)[0] == "") ) {
                $tagname = request('tags_' . $lang->lang);
                $tag = Tag::where('post_id', $post->id)->get();
                if(count($tag) == 0) {
                  foreach ($tagname as $newTag):
                      $tag = new Tag();
                      $tag->lang_id = $lang->id;
                      $tag->post_id = $post->id;
                      $tag->name = $newTag;
                      $tag->save();
                  endforeach;
                }
            }

            if ( request('tags_' . $lang->lang) != null ) {
                $tagname = request('tags_' . $lang->lang);
                $tag = Tag::where('post_id', $post->id)->get();
                if(count($tag) == 0) {
                  foreach ($tagname as $newTag):
                      $tag = new Tag();
                      $tag->lang_id = $lang->id;
                      $tag->post_id = $post->id;
                      $tag->name = $newTag;
                      $tag->save();
                  endforeach;
                }
            }

        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('posts.category', $request->category_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //show method
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::with(['translations', 'tags'])->findOrFail($id);

        $categories = Category::all();

        $tags = Tag::distinct()->get(['name', 'lang_id']);

        return view('admin::admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['title_'.$lang->lang] = 'required|max:255';
            $toValidate['url_'.$lang->lang] = 'required|unique:posts_translation,slug|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        $post = Post::findOrFail($id);
        $post->category_id = $request->category_id;

        if ($request->image != null) {
            if (file_exists('images/posts/' . $post->image)) {
                unlink('images/posts/' . $post->image);
            }

            $name = time() . '-' . $request->image->getClientOriginalName();
            $request->image->move('images/posts', $name);

            $post->image = $name;
        }

        foreach ($this->langs as $lang):
            $name[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $name[$lang->lang] = time() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/posts', $name[$lang->lang]);
            }
        endforeach;

        $post->save();

        $post->translations()->delete();


        foreach ($this->langs as $lang):
            $post->translations()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'slug' => request('slug_' . $lang->lang),
                'url' => request('url_' . $lang->lang),
                'meta_h1' => request('meta_h1_' . $lang->lang),
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang),
                'image' => !empty($name[$lang->lang]) ? $name[$lang->lang] : request('image_old_' . $lang->lang),
                'video' => request('video_' . $lang->lang),
                'image_title' => request('img_title_' . $lang->lang),
                'image_alt' => request('img_alt_' . $lang->lang),
            ]);

            if ( (request('tag_' . $lang->lang) != null) && !(request('tag_' . $lang->lang)[0] == "") ) {
                $tagname = request('tags_' . $lang->lang);
                $tag = Tag::where('post_id', $post->id)->get();
                if(count($tag) == 0) {
                  foreach ($tagname as $newTag):
                      $tag = new Tag();
                      $tag->lang_id = $lang->id;
                      $tag->post_id = $post->id;
                      $tag->name = $newTag;
                      $tag->save();
                  endforeach;
                }
            }

            if ( request('tags_' . $lang->lang) != null ) {
                $tagname = request('tags_' . $lang->lang);
                $tag = Tag::where('post_id', $post->id)->get();
                if(count($tag) == 0) {
                  foreach ($tagname as $newTag):
                      $tag = new Tag();
                      $tag->lang_id = $lang->id;
                      $tag->post_id = $post->id;
                      $tag->name = $newTag;
                      $tag->save();
                  endforeach;
                }
            }

        endforeach;


        session()->flash('message', 'Item has been edited!');

        return redirect()->route('posts.category', $request->category_id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (file_exists('/images/posts' . $post->image)) {
            unlink('/images/posts' . $post->image);
        }

        $post->delete();
        $post->translations()->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('posts.index');
    }

    public function getPostsByCategory($categoryId)
    {
        $posts = Post::where('category_id', $categoryId)->with('translation')->paginate(15);
        $category = Category::with('translation')->find($categoryId);

        return view('admin::admin.posts.index', compact('posts', 'category'));
    }

}
