<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = \DB::select("SELECT DISTINCT name, id, COUNT('post_id') as 'count' FROM tags WHERE post_id IS NOT NULL GROUP BY name ORDER BY COUNT('post_id') DESC");

        $ts = Tag::where('post_id', null)->get();

        $ids = [];
        foreach ($ts as $t) {
            foreach ($tags as $tag) {
                if ($tag->name == $t->name) {
                    $ids[] = $t->id;
                }
            }
        }

        $zeroCountTags = Tag::where('post_id', null)->whereNotIn('id', $ids)->get();


        return view('admin::admin.tags.index', compact('tags', 'zeroCountTags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin::admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($this->langs as $lang):
            $tag = new Tag();
            $tag->name = request('name_' . $lang->lang);
            $tag->lang_id = $lang->id;
            $tag->save();
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('tags.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);

        return view('admin::admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->name = $request->name;
        $tag->save();

        session()->flash('message', 'Item has been updated!');

        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tag::findOrFail($id)->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('tags.index');
    }
}
