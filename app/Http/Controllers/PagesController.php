<?php

namespace App\Http\Controllers;

use App\Models\Lang;
use App\Models\Page;
use App\Models\Collection;
use App\Models\Promocode;

class PagesController extends Controller
{
  /**
   *  get action
   *  Render home page
   */
    public function index() {
        $page = Page::where('alias', 'home')->first();
        if (is_null($page)) {
            return redirect()->route('404');
        }

        $vintage = Collection::where('alias', 'vintage')->first()->sets->first();
        $business = Collection::where('alias', 'business')->first()->sets->first();
        $casual = Collection::where('alias', 'casual')->first()->sets->first();

        $seoData = $this->getSeo($page);
        return view('front.pages.home', compact('seoData', 'page', 'vintage', 'business', 'casual'));
    }
    /**
     *  get action
     *  Get promocode message after order
     */
    public function getPromocode($promocodeId) {
        $promocode = Promocode::find($promocodeId);

        if(count($promocode) > 0) {
            session(['promocode' => $promocode]);
            return redirect()->route('home');
        }
    }
    /**
     *  get action
     *  Render static pages
     */
    public function getPages($slug)
    {
        $page = Page::where('alias', $slug)->first();
        if (is_null($page)) {
            return redirect()->route('404');
        }

        if (view()->exists('front/pages/'.$slug)) {
            $seoData = $this->getSeo($page);
            return view('front.pages.'.$slug, compact('seoData', 'page'));
        }else{
            $seoData = $this->getSeo($page);
            return view('front.pages.default', compact('seoData', 'page'));
        }
    }

    // get SEO data for a page
    private function getSeo($page){
        $seo['seo_title'] = $page->translationByLanguage($this->lang->id)->first()->meta_title;
        $seo['seo_keywords'] = $page->translationByLanguage($this->lang->id)->first()->meta_keywords;
        $seo['seo_description'] = $page->translationByLanguage($this->lang->id)->first()->meta_description;

        return $seo;
    }

    public function get404()
    {
        return view('front.404');
    }

}
