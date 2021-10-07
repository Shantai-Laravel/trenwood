<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lang;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\SetTranslation;
use App\Models\Set;
use App\Models\SubProduct;

class SearchController extends Controller
{
    /**
     *  post action
     *  Search items by value
     */
    public function index(Request $request) {
        $searchResult = ProductTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                    ->orWhere('body', 'like',  '%'.$request->get('value').'%')
                                    ->pluck('product_id')->toArray();

        $findProducts = Product::whereIn('id', $searchResult)->limit(5)->with('translations')->get();

        $searchResultSet = SetTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                    ->orWhere('addInfo', 'like',  '%'.$request->get('value').'%')
                                    ->orWhere('description', 'like',  '%'.$request->get('value').'%')
                                    ->pluck('set_id')->toArray();

        $findSets = Set::whereIn('id', $searchResultSet)->limit(5)->get();

        $search = $request->get('value');

        $data = view('front.inc.searchResults', compact('findProducts', 'findSets', 'search'))->render();

        return json_encode($data);
    }
    /**
     *  get action
     *  Render search page
     */
    public function search(Request $request) {
        $findProducts = [];
        $findSets = [];

        if ($request->get('value')) {
          $searchResult = ProductTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                      ->orWhere('body', 'like',  '%'.$request->get('value').'%')
                                      ->pluck('product_id')->toArray();

          $findProducts = Product::whereIn('id', $searchResult)->limit(5)->get();

          $searchResultSet = SetTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                      ->orWhere('addInfo', 'like',  '%'.$request->get('value').'%')
                                      ->orWhere('description', 'like',  '%'.$request->get('value').'%')
                                      ->pluck('set_id')->toArray();

          $findSets = Set::whereIn('id', $searchResultSet)->limit(5)->get();
        }

        $search = $request->get('value');
        return view('front.products.search', compact('search', 'findProducts', 'findSets'));
    }
    /**
     *  post action
     *  Sort items by high price
     */
    public function sortByHighPrice(Request $request) {
        $findProducts = [];
        $findSets = [];

        if($request->get('value')) {
            $searchResult = ProductTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                        ->orWhere('body', 'like',  '%'.$request->get('value').'%')
                                        ->pluck('product_id')->toArray();

            $subproducts = SubProduct::whereIn('product_id', $searchResult)->groupBy('product_id')->orderBy('price', 'desc')->limit(5)->get();
            foreach ($subproducts as $subproduct) {
                $findProducts[] = $subproduct->product;
            }

            $searchResultSet = SetTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                        ->orWhere('addInfo', 'like',  '%'.$request->get('value').'%')
                                        ->orWhere('description', 'like',  '%'.$request->get('value').'%')
                                        ->pluck('set_id')->toArray();

            $findSets = Set::whereIn('id', $searchResultSet)->orderBy('price', 'desc')->limit(5)->get();
        }

        $data['searchResults'] = view('front.inc.searchBox', compact('findProducts', 'findSets'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Sort items by low price
     */
    public function sortByLowPrice(Request $request) {
        $findProducts = [];
        $findSets = [];

        if($request->get('value')) {
            $searchResult = ProductTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                        ->orWhere('body', 'like',  '%'.$request->get('value').'%')
                                        ->pluck('product_id')->toArray();

            $subproducts = SubProduct::whereIn('product_id', $searchResult)->groupBy('product_id')->orderBy('price', 'asc')->limit(5)->get();
            foreach ($subproducts as $subproduct) {
                $findProducts[] = $subproduct->product;
            }

            $searchResultSet = SetTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                        ->orWhere('addInfo', 'like',  '%'.$request->get('value').'%')
                                        ->orWhere('description', 'like',  '%'.$request->get('value').'%')
                                        ->pluck('set_id')->toArray();

            $findSets = Set::whereIn('id', $searchResultSet)->orderBy('price', 'asc')->limit(5)->get();
        }

        $data['searchResults'] = view('front.inc.searchBox', compact('findProducts', 'findSets'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Sort items by id
     */
    public function sortByDesc(Request $request) {
        $findProducts = [];
        $findSets = [];

        if($request->get('value')) {
            $searchResult = ProductTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                        ->orWhere('body', 'like',  '%'.$request->get('value').'%')
                                        ->pluck('product_id')->toArray();

            $findProducts = Product::whereIn('id', $searchResult)->orderBy('id', 'desc')->limit(5)->get();

            $searchResultSet = SetTranslation::where('name', 'like',  '%'.$request->get('value').'%')
                                        ->orWhere('addInfo', 'like',  '%'.$request->get('value').'%')
                                        ->orWhere('description', 'like',  '%'.$request->get('value').'%')
                                        ->pluck('set_id')->toArray();

            $findSets = Set::whereIn('id', $searchResultSet)->orderBy('id', 'desc')->limit(5)->get();
        }

        $data['searchResults'] = view('front.inc.searchBox', compact('findProducts', 'findSets'))->render();

        return json_encode($data);
    }
}
