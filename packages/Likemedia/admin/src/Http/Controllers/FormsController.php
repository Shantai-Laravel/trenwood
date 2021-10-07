<?php

namespace Admin\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class FormsController extends Controller
{
    public function index()
    {
        $forms = Form::with(['translation' => function($query) {
            $query->where('lang_id', $this->lang);
        }])->get();

        return view('admin::admin.forms.index', compact('forms'));
    }

    public function create()
    {
        return view('admin::admin.forms.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'short_code' => 'required'
        ]);

        $form = new Form();
        $form->short_code = $request->short_code;
        $form->save();

        foreach ($this->langs as $lang) :
            $form->translation()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'description' => request('body_' . $lang->lang),
                'code' => request('build_wrap_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('forms.index');
    }

    public function edit($id) {

        $form = Form::with('translation')->findOrFail($id);

        return view('admin::admin.forms.edit', compact('form'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'short_code' => 'required'
        ]);

        $form = Form::findOrFail($id);
        $form->short_code = $request->short_code;
        $form->save();

        $form->translation()->delete();

        foreach ($this->langs as $lang) :
            $form->translation()->create([
                'lang_id' => $lang->id,
                'title' => request('title_' . $lang->lang),
                'description' => request('body_' . $lang->lang),
                'code' => request('build_wrap_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'Item has been updated!');

        return redirect()->route('forms.index');
    }

    public function destroy($id) {
        Form::findOrFail($id)->delete();

        Session::flash('message', 'Item has been deleted!');

        return redirect()->route('forms.index');
    }
}
