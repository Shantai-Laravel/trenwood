<?php

namespace App\Providers;

use App\Models\Lang;
use App\Models\Module;
use App\Models\Cart;
use App\Models\CartSet;
use App\Models\WishList;
use App\Models\WishListSet;
use App\Models\Promocode;
use App\Models\Collection;
use App\Models\Contact;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if(\Request::segment(1) != 'back' && \Request::segment(1) != 'auth'){
        //     $ip = $_SERVER['REMOTE_ADDR'];
    
        //     $details = @json_decode(file_get_contents("https://ipinfo.io/{$ip}"));
    
        //     if ($details) {
        //         if ($details->country != 'MD') {
        //             $path = str_replace('/ro/', '/en/', $_SERVER['REQUEST_URI']);
        //             header('Location: https://trenwood.com'.$path);
        //         }
        //     }else{
        //         $details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"));
        //         if ($details) {
        //             if ($details->geoplugin_countryCode != 'MD') {
        //                 $path = str_replace('/ro/', '/en/', $_SERVER['REQUEST_URI']);
        //                 header('Location: https://trenwood.com'.$path);
        //             }
        //         }
        //     }
        // }

        $this->app['request']->server->set('HTTPS','on');

        // TEMP:
        session(['applocale' => Lang::where('default', 1)->first()->lang]);

        $currentLang = Lang::where('lang', \Request::segment(1))->first()->lang ?? session('applocale');

        session(['applocale' => $currentLang]);

         \App::setLocale($currentLang);

        // ENDTEMP

        View::share('langs', Lang::all());

        View::share('lang', Lang::where('lang', session('applocale') ?? Lang::first()->lang)->first());

        View::share('menu', Module::where('parent_id', 0)->orderBy('position')->get());

        $langForURL = '';
        if ($currentLang != 'ro') {
            $langForURL = $currentLang;
        }
        View::share('urlLang', $langForURL);

        $seo['title'] = 'boiar.md';
        $seo['description'] = 'boiar.md';
        $seo['keywords'] = 'boiar.md';

        View::share('seo', $seo);

        $this->getUserId();

        View::composer('*', function ($view)
        {
            if(auth('persons')->guest() && isset($_COOKIE['user_id'])) {
              $cartProducts = Cart::where('user_id', $_COOKIE['user_id'])->where('set_id', 0)->orderBy('id', 'desc')->get();
              $cartSets = CartSet::where('user_id', $_COOKIE['user_id'])->orderBy('id', 'desc')->get();
              $wishListProducts = WishList::where('user_id', $_COOKIE['user_id'])->where('set_id', 0)->orderBy('id', 'desc')->get();
              $wishListSets = WishListSet::where('user_id', $_COOKIE['user_id'])->orderBy('id', 'desc')->get();
            } else {
              $cartProducts = Cart::where('user_id', auth('persons')->id())->where('set_id', 0)->orderBy('id', 'desc')->get();
              $cartSets = CartSet::where('user_id', auth('persons')->id())->orderBy('id', 'desc')->get();
              $wishListProducts = WishList::where('user_id', auth('persons')->id())->where('set_id', 0)->orderBy('id', 'desc')->get();
              $wishListSets = WishListSet::where('user_id', auth('persons')->id())->orderBy('id', 'desc')->get();
            }

            $promocode = Promocode::where('id', @$_COOKIE['promocode'])
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })->first();

            $collections = Collection::orderBy('position', 'asc')->get();

            $contacts = Contact::all();

            View::share('contacts', $contacts);
            View::share('collections', $collections);
            View::share('promocode', $promocode);
            View::share('wishListProducts', $wishListProducts);
            View::share('wishListSets', $wishListSets);
            View::share('cartProducts', $cartProducts);
            View::share('cartSets', $cartSets);
        });

        View::share('pureAlias', false);
        View::share('prefix', '');
    }


    public function getUserId()
    {
        $user_id = md5(rand(0, 9999999).date('Ysmsd'));

        if (\Cookie::has('user_id')) {
            $value = \Cookie::get('user_id');
        }else{
            setcookie('user_id', $user_id, time() + 10000000, '/');
            $value = \Cookie::get('user_id');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
