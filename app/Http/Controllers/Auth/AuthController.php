<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartSet;
use App\Models\WishList;
use App\Models\WishListSet;
use App\Models\FrontUser;
use Socialite;
use Session;
use App\Models\UserField;
use App\Models\Lang;

class AuthController extends Controller
{
  /**
   *  get action
   *  Render Login page
   */
  public function create()
  {
      $userfields = UserField::where('in_auth', 1)->get();

      return view('auth.front.login', compact('userfields'));
  }
  /**
   *  post action
   *  Authentificate user
   */
  public function store()
  {
      $toValidate = [];

      $uniquefields = UserField::where('in_auth', 1)->get();

      if(count($uniquefields) > 0) {
          foreach ($uniquefields as $uniquefield) {
              if($uniquefield->field == 'email') {
                  $toValidate[$uniquefield->field] = 'required|email';
              } else {
                  $toValidate[$uniquefield->field] = 'required';
              }
          }
      }

      $toValidate['password'] = 'required';

      $validator = $this->validate(request(), $toValidate);

      if (Auth::guard('persons')->attempt(request()->except('_token'))) {
          $this->checkWishList(Auth::guard('persons')->id());
          $this->checkCart(Auth::guard('persons')->id());
          $this->checkStockOfCart(Auth::guard('persons')->id());
          $lang = Lang::find(Auth::guard('persons')->user()->lang);
          return redirect($lang->lang.'/cabinet/personalData');
      }
      else {
          return redirect()->back()->withErrors(['authErr' => [trans('front.login.error')]]);
      }
  }
  /**
   *  post action
   *  Logout
   */
  public function logout()
  {
      Auth::guard('persons')->logout();

      return redirect()->route('home');
  }
  /**
   *  get action
   *  Google, facebook auth
   */
  public function redirectToProvider($provider)
  {
        return Socialite::driver($provider)->redirect();
  }
  /**
   *  post action
   *  Authentificate with google, facebook
   */
  public function handleProviderCallback($provider)
  {
        $user = Socialite::driver($provider)->user();
        $authUser = FrontUser::where('email', $user->getEmail())->first();

        if (count($authUser) > 0) {
            $this->checkCart($authUser->id);
            $this->checkWishList($authUser->id);
            $this->checkStockOfCart($authUser->id);
        } else {
            $username = explode(' ', $user->getName());
            $password = str_random(12);

            $authUser = FrontUser::create([
                'lang' => 1,
                'name' => count($username) > 0 ? $username[0] : $user->getName(),
                'surname' => count($username) > 1 ? $username[1] : '',
                'email' => $user->getEmail(),
                'password' => bcrypt($password),
                'remember_token' => $user->token
            ]);

            session()->put(['token' => str_random(60), 'user_id' => $authUser->id]);

            $to = $authUser->email;
            $subject = trans('auth.register.subject');
            $message = view('front.emailTemplates.register', compact('user', 'password'))->render();
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text; charset=utf-8' . "\r\n";

            mail($to, $subject, $message, $headers);
        }

        Auth::guard('persons')->login($authUser);
        $lang = Lang::find(Auth::guard('persons')->user()->lang);
        return redirect($lang->lang.'/cabinet/personalData');
  }
  /**
   *  private method
   *  Set guest cartProducts to authentificated user
   */
  private function checkCart($user_id) {
      $products = Cart::where('user_id', @$_COOKIE['user_id'])->get();
      $products_id = Cart::where('user_id', $user_id)->pluck('product_id')->toArray();

      $sets = CartSet::where('user_id', @$_COOKIE['user_id'])->get();
      $sets_id = CartSet::where('user_id', $user_id)->pluck('set_id')->toArray();

      if(count($products) > 0) {
          Session::flash('message', trans('front.cart.warning'));
          foreach ($products as $key => $product) {
              if(in_array($product->product_id, $products_id)) {

                  Cart::where('id', $product->id)->delete();
                  Cart::where('user_id', $user_id)->where('product_id', $product->product_id)->increment('qty', $product->qty);
              } else {
                  Cart::where('id', $product->id)->update([
                        'is_logged' => 1,
                        'user_id' => $user_id
                  ]);
              }
          }
      }

      if(count($sets) > 0) {
          Session::flash('message', trans('front.cart.warning'));
          foreach ($sets as $key => $set) {
              if(in_array($set->set_id, $sets_id)) {

                  CartSet::where('id', $set->id)->delete();
                  CartSet::where('user_id', $user_id)->where('product_id', $set->product_id)->increment('qty', $set->qty);

              } else {
                  CartSet::where('id', $set->id)->update([
                        'is_logged' => 1,
                        'user_id' => $user_id
                  ]);
              }
          }
      }
  }
  /**
   *  private method
   *  Check stock of cartProducts
   */
  public function checkStockOfCart($user_id){
      $cartProducts = Cart::where('user_id', $user_id)->get();
      $message = trans('front.cart.error');
      if (count($cartProducts) > 0) {
          foreach ($cartProducts as $key => $cartProduct) {
              if (is_null($cartProduct->product)) {
                  if ($cartProduct->product->stock == 0) {
                      Session::flash('messageStok', $message);
                      return flase;
                  }
              }
              if (is_null($cartProduct->subproduct)) {
                  if ($cartProduct->subproduct->stock == 0) {
                      Session::flash('messageStok', $message);
                      return false;
                  }
              }
          }
      }

      return true;
  }
  /**
   *  private method
   *  Set guest wishProducts to authentificated user
   */
  private function checkWishList($user_id) {
      $products = WishList::where('user_id', @$_COOKIE['user_id'])->get();

      $sets = WishListSet::where('user_id', @$_COOKIE['user_id'])->get();

      if(count($products) > 0) {
          foreach ($products as $key => $product) {
              WishList::where('id', $product->id)->update([
                    'is_logged' => 1,
                    'user_id' => $user_id
              ]);
          }
      }

      if(count($sets) > 0) {
          foreach ($sets as $key => $set) {
              WishListSet::where('id', $set->id)->update([
                    'is_logged' => 1,
                    'user_id' => $user_id
              ]);
          }
      }
  }

}
