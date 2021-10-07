<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lang;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Promocode;
use App\Models\FrontUser;
use App\Models\SubProduct;
use App\Models\WishList;
use App\Models\WishListSet;
use App\Models\Cart;
use App\Models\CartSet;
use App\Models\Set;
use Session;


class WishListController extends Controller
{
  /**
   *  get action
   *  Render wishlist page
   */
    public function index() {
        $userdata = $this->checkIfLogged();
        $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

        if (view()->exists('front/orders/wishList')) {
            return view('front.orders.wishList', compact('wishListProducts', 'wishListSets'));
        }else{
            echo "view for cart is not found";
        }
    }
    /**
     *  post action
     *  Add product to wishlist
     */
    public function addToWishList(Request $request)
    {
        $userdata = $this->checkIfLogged();

        $product = Product::find($request->get('productId'));

        if (!is_null($product)) {
            $wishlist = WishList::where('user_id', $userdata['user_id'])
                                ->where('product_id', $product->id)->first();
            if (is_null($wishlist)) {
                WishList::create([
                    'product_id' => $product->id,
                    'subproduct_id' => 0,
                    'user_id' => $userdata['user_id'],
                    'is_logged' => $userdata['is_logged']
                ]);
            } else {
              $wishlist->delete();
            }
        }

        $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

        $data['wishListBox'] = view('front.inc.wishListBox', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCount'] = view('front.inc.wishListCount', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCountMob'] = view('front.inc.wishListCountMob', compact('wishListProducts', 'wishListSets'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Add set to wishlist
     */
    public function addSetToWishList(Request $request) {
        $userdata = $this->checkIfLogged();

        $set = Set::find($request->get('setId'));

        if (!is_null($set)) {
            $wishListSet = WishListSet::where('user_id', $userdata['user_id'])->where('set_id', $set->id)->first();
            if (is_null($wishListSet)) {

                $wishListSet = WishListSet::create([
                    'set_id' => $set->id,
                    'user_id' => $userdata['user_id'],
                    'is_logged' => $userdata['is_logged']
                ]);

                foreach ($set->products as $product) {
                    WishList::create([
                        'product_id' => $product->id,
                        'subproduct_id' => 0,
                        'user_id' => $userdata['user_id'],
                        'is_logged' => $userdata['is_logged'],
                        'set_id' => $wishListSet->id
                    ]);
                }

            } else {
                $wishListSet->delete();
                $wishListSet->wishlist()->delete();
            }
        } else {
            return response()->json('Something failed', 400);
        }

        $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

        $data['wishListBox'] = view('front.inc.wishListBox', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCount'] = view('front.inc.wishListCount', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCountMob'] = view('front.inc.wishListCountMob', compact('wishListProducts', 'wishListSets'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Remove product from wishlist
     */
    public function removeItemWishList(Request $request) {
        $userdata = $this->checkIfLogged();
        $wishlistItem = WishList::where('user_id', $userdata['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($wishlistItem)) {
            WishList::where('id', $wishlistItem->id)->delete();
        } else {
            return response()->json('Such subproduct does not exist', 400);
        }

        $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

        $data['wishListBlock'] = view('front.inc.wishListBlock', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListBox'] = view('front.inc.wishListBox', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCount'] = view('front.inc.wishListCount', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCountMob'] = view('front.inc.wishListCountMob', compact('wishListProducts', 'wishListSets'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Remove set from wishlist
     */
    public function removeSetWishList(Request $request) {
        $userdata = $this->checkIfLogged();
        $wishListSet = WishListSet::where('user_id', $userdata['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($wishListSet)) {
            $wishListSet->delete();
            $wishListSet->wishlist()->delete();
        }

        $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

        $data['wishListBlock'] = view('front.inc.wishListBlock', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListBox'] = view('front.inc.wishListBox', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCount'] = view('front.inc.wishListCount', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCountMob'] = view('front.inc.wishListCountMob', compact('wishListProducts', 'wishListSets'))->render();
        return json_encode($data);
    }
    /**
     *  post action
     *  Change subproduct in select on wishlist page
     */
    public function changeSubproductSizeWishList(Request $request) {
        $userdata = $this->checkIfLogged();
        $subproduct = SubProduct::find($request->get('subproductId'));

        if (!is_null($subproduct)) {
          $wishList = WishList::where('user_id', $userdata['user_id'])->where('id', $request->get('wishListId'))->first();

          if(!is_null($wishList)) {
              $wishList->subproduct_id = $subproduct->id;
              $wishList->save();

              $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
              $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

              $data['wishListBox'] = view('front.inc.wishListBox', compact('wishListProducts', 'wishListSets'))->render();

              $data['subproduct'] = $subproduct;

              return json_encode($data);
          } else {
              return response()->json('Something failed', 400);
          }
        } else {
            return response()->json('Something failed', 400);
        }
    }
    /**
     *  post action
     *  Move products from wishlist to cart
     */
    public function moveFromWishListToCart(Request $request)
    {
        $userdata = $this->checkIfLogged();

        $wishListProduct = WishList::where('user_id', $userdata['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($wishListProduct) && $wishListProduct->subproduct) {
            Cart::create([
                'product_id' => $wishListProduct->product_id,
                'subproduct_id' => $wishListProduct->subproduct_id,
                'user_id' => $userdata['user_id'],
                'qty' => 1,
                'is_logged' => $userdata['is_logged']
            ]);

            $wishListProduct->delete();
        } else {
            return response()->json('Something failed', 400);
        }

        $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

        $data['wishListBlock'] = view('front.inc.wishListBlock', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListBox'] = view('front.inc.wishListBox', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCount'] = view('front.inc.wishListCount', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCountMob'] = view('front.inc.wishListCountMob', compact('wishListProducts', 'wishListSets'))->render();

        $cartProducts = Cart::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $cartSets = CartSet::where('user_id', $userdata['user_id'])->get();

        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Move sets from wishlist to cart
     */
    public function moveSetFromWishListToCart(Request $request)
    {
        $userdata = $this->checkIfLogged();

        $wishListSet = WishListSet::where('user_id', $userdata['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($wishListSet)) {

            foreach ($wishListSet->wishlist as $wishListProduct):
                if(!$wishListProduct->subproduct) {
                    return response()->json('Something failed', 400);
                }
            endforeach;

            $cartSet = CartSet::create([
                'set_id' => $wishListSet->set_id,
                'user_id' => $userdata['user_id'],
                'qty' => 1,
                'price' => $wishListSet->set->price,
                'is_logged' => $userdata['is_logged']
            ]);

            foreach ($wishListSet->wishlist as $wishListProduct):
                Cart::create([
                    'product_id' => $wishListProduct->product_id,
                    'subproduct_id' => $wishListProduct->subproduct_id,
                    'user_id' => $userdata['user_id'],
                    'qty' => 1,
                    'is_logged' => $userdata['is_logged'],
                    'set_id' => $cartSet->id
                ]);
            endforeach;
            $wishListSet->delete();
            $wishListSet->wishlist()->delete();
        }

        $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

        $data['wishListBlock'] = view('front.inc.wishListBlock', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListBox'] = view('front.inc.wishListBox', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCount'] = view('front.inc.wishListCount', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCountMob'] = view('front.inc.wishListCountMob', compact('wishListProducts', 'wishListSets'))->render();

        $cartProducts = Cart::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $cartSets = CartSet::where('user_id', $userdata['user_id'])->get();

        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();

        return json_encode($data);
    }
    /**
     *  private method
     *  Check if user is logged
     */
    private function checkIfLogged() {
        if(auth('persons')->guest()) {
          return array('is_logged' => 0, 'user_id' => $_COOKIE['user_id']);
        } else {
          return array('is_logged' => 1, 'user_id' => auth('persons')->id());
        }
    }
}
