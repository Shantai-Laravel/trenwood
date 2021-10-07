<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Traduction;
use App\Models\TraductionTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;


class CollectionsController extends Controller
{
    public function index()
    {
        $collections = Collection::orderBy('position', 'asc')->get();

        return view('admin::admin.collections.index', compact('collections'));
    }

    public function create()
    {
        return view('admin::admin.collections.create');
    }

    public function store(Request $request)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['title_'.$lang->lang] = 'required|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        if ($request->banner) {
            $banner = time() . '-' . $request->banner->getClientOriginalName();
            $request->banner->move('images/collections', $banner);
        }else{
            $banner = "";
        }

        foreach ($this->langs as $lang):
            $image[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $image[$lang->lang] = time() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/collections', $image[$lang->lang]);
            }
        endforeach;

        $collection = new Collection();
        $collection->alias = str_slug(request('title_ro'));
        $collection->position = 1;
        $collection->banner = $banner;
        $collection->save();

        foreach ($this->langs as $lang):
            $collection->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'image' => $image[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_description' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('collections.index');
    }

    public function show($id)
    {
        return redirect()->route('collections.index');
    }

    public function edit($id)
    {
        $collection = Collection::findOrFail($id);

        return view('admin::admin.collections.edit', compact('collection'));
    }

    public function update(Request $request, $id)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['title_'.$lang->lang] = 'required|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        $name = $request->logo_old;

        if ($request->banner) {
            $banner = time() . '-' . $request->banner->getClientOriginalName();
            $request->banner->move('images/collections', $banner);
        }else{
            $banner = $request->old_banner;
        }

        foreach ($this->langs as $lang):
            $image[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $image[$lang->lang] = time() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/collections', $image[$lang->lang]);
            }else{
                if ($request->get('old_image_'. $lang->lang)) {
                    $image[$lang->lang] = $request->get('old_image_'. $lang->lang);
                }
            }
        endforeach;


        $collection = Collection::findOrFail($id);
        $collection->alias = str_slug(request('title_ro'));
        $collection->banner = $banner;
        $collection->save();

        $collection->translations()->delete();

        foreach ($this->langs as $lang):
            $collection->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'image' => $image[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_descr' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;

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
                Collection::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }


    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);

        if (file_exists('/images/collections' . $collection->banner)) {
            unlink('/images/collections' . $collection->banner);
        }

        foreach ($this->langs as $lang):
            if (file_exists('/images/collections' . $collection->translationByLanguage($lang->id)->first()->image)) {
                unlink('/images/collections' . $collection->translationByLanguage($lang->id)->first()->image);
            }
            $collection->translationByLanguage($lang->id)->delete();
        endforeach;

        $collection->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('collections.index');
    }



}
