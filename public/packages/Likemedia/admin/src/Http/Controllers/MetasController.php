<?php

namespace Admin\Http\Controllers;

use App\Models\Meta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MetasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $metas = Meta::first();

        return view('admin.metas.index', compact('metas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $meta = Meta::first() ?? new Meta();
        $meta->save();
        $meta->translations()->delete();

        foreach ($this->langs as $lang):
            $meta->translations()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'keywords' => request('keywords_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('metas.index');
    }
}
