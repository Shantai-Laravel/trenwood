<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lang;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Cart;
use App\Models\WishList;
use App\Models\WishListSet;
use App\Models\Promocode;
use App\Models\FrontUser;
use App\Models\SubProduct;
use App\Models\UserField;
use App\Models\General;
use App\Models\Set;
use App\Models\CartSet;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;
use App\Models\Contact;
use Session;


class CartController extends Controller
{
    /**
     *  get action
     *  Render cart page
     */
    public function index() {
        $usercart = $this->checkIfLogged();

        $cartProducts = Cart::where('user_id', $usercart['user_id'])->where('set_id', 0)->orderBy('id', 'desc')->get();
        $cartSets = CartSet::where('user_id', $usercart['user_id'])->orderBy('id', 'desc')->get();

        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $userfields = UserField::where('in_cart', 1)->get();

        $loginFields = UserField::where('in_auth', 1)->get();

        $generalFields = General::all();

        unset($_COOKIE['promocode']);
        setcookie("promocode", "", time()-3600);

        if (!@$_COOKIE['promocode']) {
            setcookie('promocode', "", time() + 10000000, '/');
        }

        session()->forget('promocode');

        $promocode = $this->checkPromo();

        $countries = UserField::where('field', 'countries')->first();

        if(count($countries) > 0 && $countries->value != '') {
          $countries = Country::whereIn('id', json_decode($countries->value))->get();
        } else {
          $countries = Country::all();
        }

        if(count($userdata) > 0 && !empty($userdata->addresses()->get())) {
            foreach ($userdata->addresses()->get() as $address) {
                $regions[] = Region::where('location_country_id', $address->country)->get();
                $cities[] = City::where('location_region_id', $address->region)->get();
            }
        }

        $pickup = Contact::where('title', 'magazins')->first();

        if (view()->exists('front/orders/cart')) {
            return view('front.orders.cart', compact('cartProducts', 'cartSets', 'generalFields', 'userdata', 'userfields', 'loginFields', 'promocode', 'countries', 'regions', 'cities', 'pickup'));
        }else{
            echo "view for cart is not found";
        }
    }
    /**
     *  post action
     *  Filter regions by country
     */
    public function filterByCountries(Request $request)
    {
        $locationItems = Region::where('location_country_id', $request->get('value'))->get();

        if(!empty($request->get('address_id'))) {
            $address = FrontUserAddress::find($request->get('address_id'));
            $data['regions'] = view('front.inc.options', compact('locationItems', 'address'))->render();
        } else {
            $data['regions'] = view('front.inc.options', compact('locationItems'))->render();
        }

        return json_encode($data);
    }
    /**
     *  post action
     *  Filter locations by region
     */
    public function filterByRegions(Request $request) {
        $locationItems = City::where('location_region_id', $request->get('value'))->get();

        if(!empty($request->get('address_id'))) {
            $address = FrontUserAddress::find($request->get('address_id'));
            $data['cities'] = view('front.inc.options', compact('locationItems', 'address'))->render();
        } else {
            $data['cities'] = view('front.inc.options', compact('locationItems'))->render();
        }

        return json_encode($data);
    }
    /**
     *  post action
     *  Add products to cart
     */
    public function addToCart(Request $request)
    {
        $userdata =  $this->checkIfLogged();
        $promocode = $this->checkPromo();

        $checkStock = 'true';

        // return $request->all();

        $subproduct = SubProduct::where('id', $request->get('subproduct_id'))->where('product_id', $request->get('product_id'))->first();
        if(!is_null($subproduct)) {
            $cart = Cart::where('user_id', $userdata['user_id'])
                        ->where('product_id', $subproduct->product_id)
                        ->where('subproduct_id', $subproduct->id)->first();

            if (is_null($cart)) {
                Cart::create([
                    'product_id' => $subproduct->product_id,
                    'subproduct_id' => $subproduct->id,
                    'user_id' => $userdata['user_id'],
                    'qty' => 1,
                    'is_logged' => $userdata['is_logged']
                ]);
            } else {
                if ($subproduct->stock > $cart->qty) {
                    $cart->qty += 1;
                    $cart->save();
                }else{
                    $checkStock = 'false';
                }
            }
        } else {
            return response()->json('Something failed', 400);
        }

        $cartProducts = Cart::where('user_id', $userdata['user_id'])->where('set_id', 0)->orderBy('id', 'desc')->get();
        $cartSets = CartSet::where('user_id', $userdata['user_id'])->orderBy('id', 'desc')->get();

        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartQuick'] = view('front.modals.cartViewPop', compact('cartProducts', 'cartSets', 'checkStock', 'subproduct'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Add sets to cart
     */
    public function addSetToCart(Request $request)
    {
        $userdata = $this->checkIfLogged();
        $promocode = $this->checkPromo();

        $set = Set::find($request->get('setId'));
        if (!is_null($set)) {
            $cartSet = CartSet::where('user_id', $userdata['user_id'])->where('set_id', $set->id)->first();
            if (is_null($cartSet)) {

                $cartSet = CartSet::create([
                    'set_id' => $set->id,
                    'user_id' => $userdata['user_id'],
                    'qty' => 1,
                    'is_logged' => $userdata['is_logged'],
                    'price' => $set->price
                ]);

                if (count($request->get('subproductsId')) > 0) {
                    foreach ($request->get('subproductsId') as $key => $subProductId) {
                        $subproduct = SubProduct::find($subProductId);
                        if (!is_null($subproduct)) {
                            Cart::create([
                                'product_id' => $subproduct->product_id,
                                'subproduct_id' => $subproduct->id,
                                'user_id' => $userdata['user_id'],
                                'qty' => 1,
                                'is_logged' => $userdata['is_logged'],
                                'set_id' => $cartSet->id
                            ]);
                        }
                    }
                }

            } else {
                Cart::where('set_id', $cartSet->id)->delete();
                CartSet::where('id', $cartSet->id)->update([
                    'qty' => $cartSet->qty + 1
                ]);

                if (count($request->get('subproductsId')) > 0) {
                    foreach ($request->get('subproductsId') as $key => $subProductId) {
                        $subproduct = SubProduct::find($subProductId);
                        if (!is_null($subproduct)) {
                            Cart::create([
                                'product_id' => $subproduct->product_id,
                                'subproduct_id' => $subproduct->id,
                                'user_id' => $userdata['user_id'],
                                'qty' => 1,
                                'is_logged' => $userdata['is_logged'],
                                'set_id' => $cartSet->id
                            ]);
                        }
                    }
                }
            }

        } else {
            return response()->json('Something failed', 400);
        }

        $cartProducts = Cart::where('user_id', $userdata['user_id'])->where('set_id', 0)->orderBy('id', 'desc')->get();
        $cartSets = CartSet::where('user_id', $userdata['user_id'])->orderBy('id', 'desc')->get();

        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartQuick'] = view('front.modals.cartViewPopSet', compact('cartProducts', 'cartSets', 'cartSet'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Change cart products qty
     */
    public function changeQty(Request $request) {
        $userCart = $this->checkIfLogged();
        $promocode = $this->checkPromo();

        $cartItem = Cart::where('user_id', $userCart['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($cartItem)) {
            Cart::where('id', $cartItem->id)->update([
                'qty' => $request->get('value'),
            ]);
        }else{
            $cartItem = Cart::where('user_id', $userCart['user_id'])->where('product_id', $request->get('prod'))->where('subproduct_id', $request->get('subprod'))->first();
            if (!is_null($cartItem)) {
                Cart::where('id', $cartItem->id)->update([
                    'qty' => $request->get('value'),
                ]);
            }
        }

        $cartProducts = Cart::where('user_id', $userCart['user_id'])->where('set_id', 0)->orderBy('id', 'desc')->get();
        $cartSets = CartSet::where('user_id', $userCart['user_id'])->orderBy('id', 'desc')->get();
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $data['cartBlock'] = view('front.inc.cartBlock', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartBlockMob'] = view('front.inc.cartBlockMob', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartSummary'] = view('front.inc.cartSummary', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['promo'] = view('front.inc.promo', compact('cartProducts', 'cartSets', 'promocode', 'userdata'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Change cart sets qty
     */
    public function changeQtySet(Request $request)
    {
        $userCart = $this->checkIfLogged();
        $promocode = $this->checkPromo();

        $cartSet = CartSet::where('id', $request->get('id'))->first();

        if (!is_null($cartSet)) {
            CartSet::where('id', $cartSet->id)->update([
                'qty' => $request->get('value')
            ]);
        }

        $cartProducts = Cart::where('user_id', $userCart['user_id'])->where('set_id', 0)->orderBy('id', 'desc')->get();
        $cartSets = CartSet::where('user_id', $userCart['user_id'])->orderBy('id', 'desc')->get();
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $data['cartBlock'] = view('front.inc.cartBlock', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartBlockMob'] = view('front.inc.cartBlockMob', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartSummary'] = view('front.inc.cartSummary', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['promo'] = view('front.inc.promo', compact('cartProducts', 'cartSets', 'promocode', 'userdata'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Remove product from cart
     */
    public function removeItemCart(Request $request) {
        $userdata = $this->checkIfLogged();
        $promocode = $this->checkPromo();

        $cartItem = Cart::where('user_id', $userdata['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($cartItem)) {
            Cart::where('id', $cartItem->id)->delete();
        }

        $cartProducts = Cart::where('user_id', $userdata['user_id'])->where('set_id', 0)->orderBy('id', 'desc')->get();
        $cartSets = CartSet::where('user_id', $userdata['user_id'])->orderBy('id', 'desc')->get();
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $data['cartBlock'] = view('front.inc.cartBlock', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartBlockMob'] = view('front.inc.cartBlockMob', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartSummary'] = view('front.inc.cartSummary', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['promo'] = view('front.inc.promo', compact('cartProducts', 'cartSets', 'promocode', 'userdata'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Remove set from cart
     */
    public function removeSetCart(Request $request)
    {
        $userdata = $this->checkIfLogged();
        $promocode = $this->checkPromo();

        $cartSet = CartSet::where('user_id', $userdata['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($cartSet)) {
            Cart::where('set_id', $cartSet->id)->delete();
            CartSet::where('id', $cartSet->id)->delete();
        }

        $cartProducts = Cart::where('user_id', $userdata['user_id'])->where('set_id', 0)->orderBy('id', 'desc')->get();
        $cartSets = CartSet::where('user_id', $userdata['user_id'])->orderBy('id', 'desc')->get();
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $data['cartBlock'] = view('front.inc.cartBlock', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartBlockMob'] = view('front.inc.cartBlockMob', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartSummary'] = view('front.inc.cartSummary', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['promo'] = view('front.inc.promo', compact('cartProducts', 'cartSets', 'promocode', 'userdata'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Set promocode in cart
     */
    public function setPromocode(Request $request) {
        $userCart =  $this->checkIfLogged();

        $promocode = Promocode::where('name', $request->get('promocode'))
                                ->where(function($query){
                                    $query->where('status', 'valid');
                                    $query->orWhere('status', 'partially');
                                })->first();

        if (!is_null($promocode)) {
            $promocodeId = $promocode->id;
            setcookie('promocode', $promocodeId, time() + 10000000, '/');
            Session::flash('promocode', $request->get('promocode'));

            $cartProducts = Cart::where('user_id', $userCart['user_id'])->where('set_id', 0)->get();
            $cartSets = CartSet::where('user_id', $userCart['user_id'])->get();
            $userdata = FrontUser::where('id', auth('persons')->id())->first();

            $data['cartBlock'] = view('front.inc.cartBlock', compact('cartProducts', 'cartSets', 'promocode'))->render();
            $data['cartBlockMob'] = view('front.inc.cartBlockMob', compact('cartProducts', 'cartSets', 'promocode'))->render();
            $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets', 'promocode'))->render();
            $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets', 'promocode'))->render();
            $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();
            $data['cartSummary'] = view('front.inc.cartSummary', compact('cartProducts', 'cartSets', 'promocode'))->render();
            $data['promo'] = view('front.inc.promo', compact('cartProducts', 'cartSets', 'promocode', 'userdata'))->render();

            return json_encode($data);
        }
        return 'false';
    }
    /**
     *  private method
     *  Check if user is logged
     */
    private function checkIfLogged() {
        if(auth('persons')->guest()) {
          return array('is_logged' => 0, 'user_id' => @$_COOKIE['user_id']);
        } else {
          return array('is_logged' => 1, 'user_id' => auth('persons')->id());
        }
    }
    /**
     *  post action
     *  Check if promocode is valid
     */
    private function checkPromo() {
      return Promocode::where('id', @$_COOKIE['promocode'])
                              ->where(function($query){
                                  $query->where('status', 'valid');
                                  $query->orWhere('status', 'partially');
                              })->first();
    }
    /**
     *  post action
     *  Move products from cart to wishlist
     */
    public function moveFromCartToWishList(Request $request)
    {
        $userdata = $this->checkIfLogged();
        $promocode = $this->checkPromo();

        $cartProduct = Cart::where('user_id', $userdata['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($cartProduct) && $cartProduct->subproduct) {
            WishList::create([
                'product_id' => $cartProduct->product_id,
                'subproduct_id' => $cartProduct->subproduct_id,
                'user_id' => $userdata['user_id'],
                'is_logged' => $userdata['is_logged']
            ]);

            $cartProduct->delete();
        } else {
            return response()->json('Something failed', 400);
        }

        $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

        $data['wishListBox'] = view('front.inc.wishListBox', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCount'] = view('front.inc.wishListCount', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCountMob'] = view('front.inc.wishListCountMob', compact('wishListProducts', 'wishListSets'))->render();

        $cartProducts = Cart::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $cartSets = CartSet::where('user_id', $userdata['user_id'])->get();
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $data['cartBlock'] = view('front.inc.cartBlock', compact('cartProducts', 'cartSets'))->render();
        $data['cartBlockMob'] = view('front.inc.cartBlockMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartSummary'] = view('front.inc.cartSummary', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['promo'] = view('front.inc.promo', compact('cartProducts', 'cartSets', 'promocode', 'userdata'))->render();

        return json_encode($data);
    }
    /**
     *  post action
     *  Move sets from cart to wishlist
     */
    public function moveSetFromCartToWishList(Request $request)
    {
        $userdata = $this->checkIfLogged();
        $promocode = $this->checkPromo();

        $cartSet = CartSet::where('user_id', $userdata['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($cartSet)) {

            foreach ($cartSet->cart as $cartProduct):
                if(!$cartProduct->subproduct) {
                    return response()->json('Something failed', 400);
                }
            endforeach;

            $wishListSet = WishListSet::create([
                'set_id' => $cartSet->set_id,
                'user_id' => $userdata['user_id'],
                'is_logged' => $userdata['is_logged']
            ]);

            foreach ($cartSet->cart as $cartProduct):
                WishList::create([
                    'product_id' => $cartProduct->product_id,
                    'subproduct_id' => $cartProduct->subproduct_id,
                    'user_id' => $userdata['user_id'],
                    'is_logged' => $userdata['is_logged'],
                    'set_id' => $wishListSet->id
                ]);
            endforeach;
            $cartSet->delete();
            $cartSet->cart()->delete();
        }

        $wishListProducts = WishList::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $wishListSets = WishListSet::where('user_id', $userdata['user_id'])->get();

        $data['wishListBox'] = view('front.inc.wishListBox', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCount'] = view('front.inc.wishListCount', compact('wishListProducts', 'wishListSets'))->render();
        $data['wishListCountMob'] = view('front.inc.wishListCountMob', compact('wishListProducts', 'wishListSets'))->render();

        $cartProducts = Cart::where('user_id', $userdata['user_id'])->where('set_id', 0)->get();
        $cartSets = CartSet::where('user_id', $userdata['user_id'])->get();
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $data['cartBlock'] = view('front.inc.cartBlock', compact('cartProducts', 'cartSets'))->render();
        $data['cartBlockMob'] = view('front.inc.cartBlockMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartBox'] = view('front.inc.cartBox', compact('cartProducts', 'cartSets'))->render();
        $data['cartCount'] = view('front.inc.cartCount', compact('cartProducts', 'cartSets'))->render();
        $data['cartCountMob'] = view('front.inc.cartCountMob', compact('cartProducts', 'cartSets'))->render();
        $data['cartSummary'] = view('front.inc.cartSummary', compact('cartProducts', 'cartSets', 'promocode'))->render();
        $data['promo'] = view('front.inc.promo', compact('cartProducts', 'cartSets', 'promocode', 'userdata'))->render();

        return json_encode($data);
    }

}
