<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Traduction;
use App\Models\TraductionTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;


class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brand::with('translation')->orderBy('position', 'asc')->where('parent_id', 0)->get();

        return view('admin::admin.brands.index', compact('brands'));
    }

    public function create()
    {
        $allBrands = Brand::where('parent_id', 0)->get();

        return view('admin::admin.brands.create', compact('allBrands'));
    }

    public function store(Request $request)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['title_'.$lang->lang] = 'required|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        if ( $request->logo) {
            $name = time() . '-' . $request->logo->getClientOriginalName();
            $request->logo->move('images/brands', $name);
        }else{
            $name = "";
        }

        if ( $request->picture) {
            $picture= time() . '-' . $request->picture->getClientOriginalName();
            $request->logo->move('images/brands', $picture);
        }else{
            $picture = "";
        }

        foreach ($this->langs as $lang):
            $banner[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $banner[$lang->lang] = time() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/brands', $banner[$lang->lang]);
            }
        endforeach;

        $brand = new Brand();
        $brand->alias = str_slug(request('title_ro'));
        $brand->active = 1;
        $brand->position = 1;
        $brand->img = $name;
        $brand->picture  = $picture;
        $brand->parent_id  = request('parent_id');
        $brand->save();

        foreach ($this->langs as $lang):
            $brand->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'grapes' => request('grapes_' . $lang->lang),
                'production' => request('production_' . $lang->lang),
                'temperature' => request('temperature_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'banner' => $banner[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_descr' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('brands.index');
    }

    public function show($id)
    {
        return redirect()->route('brands.index');
    }

    public function edit($id)
    {
        $brand = Brand::with('translations')->findOrFail($id);
        $allBrands = Brand::where('parent_id', 0)->where('id', '!=', $id)->get();

        return view('admin::admin.brands.edit', compact('brand', 'allBrands', 'translations'));
    }

    public function update(Request $request, $id)
    {
        $toValidate = [];
        foreach ($this->langs as $lang){
            $toValidate['title_'.$lang->lang] = 'required|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        $name = $request->logo_old;

        if (!empty($request->file('logo'))) {
            $name = time() . '-' . $request->logo->getClientOriginalName();
            $request->logo->move('images/brands', $name);
        }

        $picture = $request->picture_old;

        if (!empty($request->file('picture'))) {
            $picture = time() . '-' . $request->picture->getClientOriginalName();
            $request->picture->move('images/brands', $picture);
        }

        foreach ($this->langs as $lang):
            $banner[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $banner[$lang->lang] = time() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/brands', $banner[$lang->lang]);
            }else{
                if ($request->get('old_image_'. $lang->lang)) {
                    $banner[$lang->lang] = $request->get('old_image_'. $lang->lang);
                }
            }
        endforeach;


        $brand = Brand::findOrFail($id);
        $brand->alias = str_slug(request('title_ro'));
        $brand->active = 1;
        $brand->position = 1;
        $brand->img = $name;
        $brand->picture = $picture;
        $brand->parent_id  = request('parent_id');
        $brand->save();

        $brand->translations()->delete();

        foreach ($this->langs as $lang):
            $brand->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'grapes' => request('grapes_' . $lang->lang),
                'production' => request('production_' . $lang->lang),
                'temperature' => request('temperature_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'banner' => $banner[$lang->lang],
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
                Brand::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function status($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->active == 1) {
            $brand->active = 0;
        } else {
            $brand->active = 1;
        }

        $brand->save();

        return redirect()->route('brands.index');
    }


    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if (file_exists('/images/brands' . $brand->image)) {
            unlink('/images/brands' . $brand->image);
        }

        $brand->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('brands.index');
    }



}
