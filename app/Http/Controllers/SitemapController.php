<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Lang;
use App\Models\Post;
use App\Models\Brand;
use App\Models\Promotion;
use App\Models\ProductCategory;
use App\Models\Product;

class SitemapController extends Controller
{
    public function xml(Request $request)
    {
        $url = 'http://admin.likemedia.top/';

        $staticPages = ['', '/register', '/login', '/delivery', '/wholesalers', '/suppliers', '/howtomakeorder', '/about', '/advices'];

        $posts = Post::orderBy('created_at', 'desc')->get();
        $brands = Brand::orderBy('created_at', 'desc')->get();
        $promotions = Promotion::orderBy('created_at', 'desc')->get();
        $categories = ProductCategory::where('parent_id', 0)->orderBy('created_at', 'desc')->get();

        foreach (Lang::all() as $lang) {

            foreach ($staticPages as $staticPage) {
                $aSiteMap[$url.$lang->lang.$staticPage] = [
                    'added' => time(),
                    'lastmod' => Carbon::now()->toIso8601String(),
                    'priority' => 1 - substr_count($url.$lang->lang.'/'.$staticPage, '/') / 10,
                    'changefreq' => 'never'
                ];
            }

            if(count($posts) > 0) {
                foreach ($posts as $post) {
                    $aSiteMap[$url.$lang->lang.'/advices/'.$post->translationByLanguage($lang->id)->first()->url] = [
                        'added' => time(),
                        'lastmod' => Carbon::now()->toIso8601String(),
                        'priority' => 1 - substr_count($url.$lang->lang.'/advices/'.$post->translationByLanguage($lang->id)->first()->url, '/') / 10,
                        'changefreq' => 'always'
                    ];
                }
            }

            if(count($brands) > 0) {
                foreach ($brands as $brand) {
                    $aSiteMap[$url.$lang->lang.'/brands/'.$brand->alias] = [
                        'added' => time(),
                        'lastmod' => Carbon::now()->toIso8601String(),
                        'priority' => 1 - substr_count($url.$lang->lang.'/brands/'.$brand->alias, '/') / 10,
                        'changefreq' => 'always'
                    ];
                }
            }

            if(count($promotions) > 0) {
                foreach ($promotions as $promotion) {
                    $aSiteMap[$url.$lang->lang.'/promotions/'.$promotion->alias] = [
                        'added' => time(),
                        'lastmod' => Carbon::now()->toIso8601String(),
                        'priority' => 1 - substr_count($url.$lang->lang.'/promotions/'.$promotion->alias, '/') / 10,
                        'changefreq' => 'always'
                    ];
                }
            }

            if(count($categories) > 0) {
                foreach ($categories as $category) {
                    $aSiteMap[$url.$lang->lang.'/catalog/'.$category->alias] = [
                        'added' => time(),
                        'lastmod' => Carbon::now()->toIso8601String(),
                        'priority' => 1 - substr_count($url.$lang->lang.'/catalog/'.$category->alias, '/') / 10,
                        'changefreq' => 'always'
                    ];

                    $firstSubCategories = ProductCategory::where('parent_id', $category->id)->orderBy('created_at', 'desc')->get();

                    if(count($firstSubCategories) > 0) {
                        foreach ($firstSubCategories as $firstSubCategory) {
                            $aSiteMap[$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias] = [
                                'added' => time(),
                                'lastmod' => Carbon::now()->toIso8601String(),
                                'priority' => 1 - substr_count($url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias, '/') / 10,
                                'changefreq' => 'always'
                            ];

                            $secondSubCategories = ProductCategory::where('parent_id', $firstSubCategory->id)->orderBy('created_at', 'desc')->get();

                            if(count($secondSubCategories) > 0) {
                                foreach ($secondSubCategories as $secondSubCategory) {
                                    $aSiteMap[$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$secondSubCategory->alias] = [
                                        'added' => time(),
                                        'lastmod' => Carbon::now()->toIso8601String(),
                                        'priority' => 1 - substr_count($url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$secondSubCategory->alias, '/') / 10,
                                        'changefreq' => 'always'
                                    ];

                                    $products = Product::where('category_id', $secondSubCategory->id)->orderBy('created_at', 'desc')->get();
                                    if(count($products) > 0) {
                                        foreach ($products as $product) {
                                            $aSiteMap[$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$secondSubCategory->alias.'/'.$product->alias] = [
                                                'added' => time(),
                                                'lastmod' => Carbon::now()->toIso8601String(),
                                                'priority' => 1 - substr_count($url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$secondSubCategory->alias.'/'.$product->alias, '/') / 10,
                                                'changefreq' => 'always'
                                            ];
                                        }
                                    }
                                }
                            } else {
                                $products = Product::where('category_id', $firstSubCategory->id)->orderBy('created_at', 'desc')->get();
                                if(count($products) > 0) {
                                    foreach ($products as $product) {
                                        $aSiteMap[$url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$product->alias] = [
                                            'added' => time(),
                                            'lastmod' => Carbon::now()->toIso8601String(),
                                            'priority' => 1 - substr_count($url.$lang->lang.'/catalog/'.$category->alias.'/'.$firstSubCategory->alias.'/'.$product->alias, '/') / 10,
                                            'changefreq' => 'always'
                                        ];
                                    }
                                }
                            }
                        }
                    } else {
                        $products = Product::where('category_id', $category->id)->orderBy('created_at', 'desc')->get();
                        if(count($products) > 0) {
                            foreach ($products as $product) {
                                $aSiteMap[$url.$lang->lang.'/catalog/'.$category->alias.'/'.$product->alias] = [
                                    'added' => time(),
                                    'lastmod' => Carbon::now()->toIso8601String(),
                                    'priority' => 1 - substr_count($url.$lang->lang.'/catalog/'.$category->alias.'/'.$product->alias, '/') / 10,
                                    'changefreq' => 'always'
                                ];
                            }
                        }
                    }
                }
            }
        }

        \Cache::put('sitemap', $aSiteMap, 2880);

        return Response::view('front.sitemapxml')->header('Content-Type', 'application/xml');
    }

    public function html()
    {
      $url = 'http://admin.likemedia.top/';

      $staticPages = ['', '/register', '/login', '/delivery', '/wholesalers', '/suppliers', '/howtomakeorder', '/about'];

      $posts = Post::orderBy('created_at', 'desc')->get();
      $brands = Brand::orderBy('created_at', 'desc')->get();
      $promotions = Promotion::orderBy('created_at', 'desc')->get();
      $categories = ProductCategory::where('parent_id', 0)->orderBy('created_at', 'desc')->get();

      return Response::view('front.sitemap', compact('url', 'staticPages', 'posts', 'brands', 'promotions', 'categories'));
    }

}
