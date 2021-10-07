<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Traduction;
use App\Models\Product;
use App\Models\TraductionTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;


class PromotionsController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('translation')->orderBy('position', 'asc')->get();

        return view('admin::admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin::admin.promotions.create');
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
            $request->logo->move('images/promotions', $name);
        }else{
            $name = "";
        }

        if ( $request->picture) {
            $picture= time() . '-' . $request->picture->getClientOriginalName();
            $request->logo->move('images/promotions', $picture);
        }else{
            $picture = "";
        }

        foreach ($this->langs as $lang):
            $banner[$lang->lang] = '';
            $bannermob[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $banner[$lang->lang] = time() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/promotions', $banner[$lang->lang]);
            }
            if ($request->file('image_mob_'. $lang->lang)) {
              $bannermob[$lang->lang] = time() . '-' . $request->file('image_mob_'. $lang->lang)->getClientOriginalName();
              $request->file('image_mob_'. $lang->lang)->move('images/promotions', $bannermob[$lang->lang]);
            }
        endforeach;

        $promotion = new Promotion();
        $promotion->alias = str_slug(request('title_ro'));
        $promotion->active = 1;
        $promotion->position = 1;
        $promotion->img = $name;
        $promotion->discount  = $request->discount;
        $promotion->save();

        foreach ($this->langs as $lang):
            $promotion->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'banner' => $banner[$lang->lang],
                'banner_mob' => $bannermob[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_description' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('promotions.index');
    }

    public function show($id)
    {
        return redirect()->route('promotions.index');
    }

    public function edit($id)
    {
        $promotion = Promotion::with('translations')->findOrFail($id);

        return view('admin::admin.promotions.edit', compact('promotion', 'translations'));
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
            $request->logo->move('images/promotions', $name);
        }

        $picture = $request->picture_old;

        if (!empty($request->file('picture'))) {
            $picture = time() . '-' . $request->picture->getClientOriginalName();
            $request->picture->move('images/promotions', $picture);
        }

        foreach ($this->langs as $lang):
            $banner[$lang->lang] = '';
            $bannermob[$lang->lang] = '';
            if ($request->file('image_'. $lang->lang)) {
              $banner[$lang->lang] = time() . '-' . $request->file('image_'. $lang->lang)->getClientOriginalName();
              $request->file('image_'. $lang->lang)->move('images/promotions', $banner[$lang->lang]);
            }else{
                if ($request->get('old_image_'. $lang->lang)) {
                    $banner[$lang->lang] = $request->get('old_image_'. $lang->lang);
                }
            }
            if ($request->file('image_mob_'. $lang->lang)) {
              $bannermob[$lang->lang] = time() . '-' . $request->file('image_mob_'. $lang->lang)->getClientOriginalName();
              $request->file('image_mob_'. $lang->lang)->move('images/promotions', $bannermob[$lang->lang]);
            }else{
                if ($request->get('old_image_mob_'. $lang->lang)) {
                    $bannermob[$lang->lang] = $request->get('old_image_mob_'. $lang->lang);
                }
            }
        endforeach;

        $promotion = Promotion::findOrFail($id);
        $promotion->alias = str_slug(request('title_ro'));
        $promotion->active = 1;
        $promotion->position = 1;
        $promotion->img = $name;
        $promotion->discount  = $request->discount;
        $promotion->save();

        $promotion->translations()->delete();

        foreach ($this->langs as $lang):
            $promotion->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'description' => request('description_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'banner' => $banner[$lang->lang],
                'banner_mob' => $bannermob[$lang->lang],
                'seo_text' => request('seo_text_' . $lang->lang),
                'seo_title' => request('seo_title_' . $lang->lang),
                'seo_description' => request('seo_descr_' . $lang->lang),
                'seo_keywords' => request('seo_keywords_' . $lang->lang)
            ]);
        endforeach;

        $products = Product::where('promotion_id', $id)->get();
        if (!empty($products)) {
            foreach ($products as $key => $product) {
                Product::where('promotion_id', $id)->update([
                    'discount' => $request->discount,
                ]);
            }
        }

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
                Promotion::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function status($id)
    {
        $promotion = Promotion::findOrFail($id);

        if ($promotion->active == 1) {
            $promotion->active = 0;
        } else {
            $promotion->active = 1;
        }

        $promotion->save();

        return redirect()->route('promotions.index');
    }


    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);

        foreach ($promotion->translations()->get() as $promotion_translation) {
            if ($promotion_translation->banner != '' && file_exists(public_path('images/promotions/'.$promotion_translation->banner))) {
                unlink(public_path('images/promotions/'.$promotion_translation->banner));
            }
            if ($promotion_translation->banner_mob != '' && file_exists(public_path('images/promotions/'.$promotion_translation->banner_mob))) {
                unlink(public_path('images/promotions/'.$promotion_translation->banner_mob));
            }
        }

        $promotion->delete();
        $promotion->translations()->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('promotions.index');
    }



}
