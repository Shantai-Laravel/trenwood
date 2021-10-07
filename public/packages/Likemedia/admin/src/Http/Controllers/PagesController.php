<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Models\Page;
use App\Models\Traduction;
use App\Models\TraductionTranslation;
use App\Models\Gallery;


class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::with('translation')->orderBy('position', 'asc')->get();

        return view('admin::admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $galleries = Gallery::get();

        return view('admin::admin.pages.create', compact('galleries'));
    }

    public function store(Request $request)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['title_'.$lang->lang] = 'required|max:255';
            $toValidate['slug_'.$lang->lang] = 'required|unique:pages_translation,slug|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        $page = new Page();
        $page->alias = request('alias');
        $page->active = 1;
        $page->position = 1;
        $page->gallery_id = request('gallery_id');
        $page->save();

        foreach ($this->langs as $lang):
            $page->translations()->create([
                'lang_id' => $lang->id,
                'slug' => request('slug_' . $lang->lang),
                'title' => request('title_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'image' => 'tmp',
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('pages.index');
    }

    public function edit($id)
    {
        $page = Page::with('translations')->findOrFail($id);
        $translations = Traduction::where('page_id', $id)->with(['translations'])->get();
        $galleries = Gallery::get();

        return view('admin::admin.pages.edit', compact('page', 'translations', 'galleries'));
    }

    public function update(Request $request, $id)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['title_'.$lang->lang] = 'required|max:255';
            $toValidate['slug_'.$lang->lang] = 'required|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        $page = Page::findOrFail($id);
        $page->alias = request('alias');
        $page->active = 1;
        $page->position = $request->active;
        $page->gallery_id = request('gallery_id');
        $page->save();

        $page->translations()->delete();

        foreach ($this->langs as $lang):
            $page->translations()->create([
                'lang_id' => $lang->id,
                'slug' => request('slug_' . $lang->lang),
                'title' => request('title_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'image' => 'tmp',
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang)
            ]);
        endforeach;

        $this->saveTraductions($request);

        return redirect()->back();
    }

    public function changePosition()
    {
        $neworder = Input::get('neworder');
        $i = 1;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k => $v) {
            $id = str_replace("tablelistsorter[]=", "", $v);
            if (!empty($id)) {
                Page::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function status($id)
    {
        $page = Page::findOrFail($id);

        if ($page->active == 1) {
            $page->active = 0;
        } else {
            $page->active = 1;
        }

        $page->save();

        return redirect()->route('pages.index');
    }


    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        if (file_exists('/images/pages' . $page->image)) {
            unlink('/images/pages' . $page->image);
        }

        $page->delete();
        $page->translations()->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('pages.index');
    }

    public function saveTraductions(Request $request)
    {
        $trads = Traduction::where('page_id', $request->get('page_id'))->get();

        if (!empty($trads)) {
            foreach ($trads as $key => $trad) {
                TraductionTranslation::where('traduction_id', $trad->id)->delete();
            }
            Traduction::where('page_id', $request->get('page_id'))->delete();
        }

        $cases = $request->get('case_' . $this->lang->lang);


        if (!empty($cases)) {
            foreach ($cases as $key => $case) {
                if ($key !== 0) {
                    $multidata = Traduction::create([
                        'page_id' => $request->get('page_id'),
                        'number' => $key
                    ]);

                    foreach ($this->langs as $lang){
                        TraductionTranslation::create([
                            'traduction_id' => $multidata->id,
                            'lang_id' => $lang->id,
                            'value' => $request->get('case_' . $lang->lang)[$key],
                        ]);
                    }
                }
            }
        }
        return redirect()->back();
    }

}
