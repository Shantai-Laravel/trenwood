<?php

namespace Admin\Http\Controllers;

use App\Models\Lang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguagesController extends Controller
{

    public function index()
    {
        $languages = Lang::all();

        return view('admin::admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin::admin.languages.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|size:2|alpha',
            'description' => 'required|alpha'
        ]);

        $language = new Lang();
        $language->lang = $request->name;
        $language->description = $request->description;
        $language->active = 1;
        $language->save();

        return redirect()->route('languages.index');

        dd($request->all());
    }

    public function destroy($id)
    {
        $lang = Lang::findOrFail($id);

        if ($lang->default == 1) {
            session('flash', "Can't delete default language");

            return back();
        }
        $lang->delete();

        return back();
    }

    public function setDefault($id)
    {
        $current = Lang::where('default', '1')->first();
        $current->default = 0;
        $current->save();

        $new = Lang::findOrFail($id);
        $new->default = 1;
        $new->save();

        return back();
    }

}
